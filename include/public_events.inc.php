<?php
defined('OAUTH_PATH') or die('Hacking attempt!');

/**
 * identification page
 */
function oauth_begin_identification()
{
  global $template, $conf, $hybridauth_conf;
  
  if ($hybridauth_conf['enabled'] == 0)
  {
    return;
  }

  $u_redirect = !empty($_GET['redirect']) ? urldecode($_GET['redirect']) : get_gallery_home_url();
  oauth_assign_template_vars($u_redirect);

  $template->set_prefilter('identification', 'oauth_add_buttons_prefilter');
}

/**
 * interrupt normal login if corresponding to an oauth user
 */
function oauth_try_log_user($success, $username)
{
  global $conf, $redirect_to;
  
  $query = '
SELECT oauth_id FROM ' . USERS_TABLE . '
  WHERE ' . $conf['user_fields']['username'] . ' = "' . pwg_db_real_escape_string($username) . '"
  AND oauth_id != ""
;';
  $result = pwg_query($query);
  
  if (pwg_db_num_rows($result))
  {
    list($oauth_id) = pwg_db_fetch_row($result);
    list($provider) = explode('---', $oauth_id, 2);
    $_SESSION['page_errors'][] = l10n('You registered with a %s account, please sign in with the same account.', $provider);
    
    $redirect_to = get_root_url().'identification.php'; // variable used by identification.php
    return true;
  }
  
  return false;
}


/**
 * register page
 */
function oauth_begin_register()
{
  global $conf, $template, $hybridauth_conf, $page;
  
  if ($hybridauth_conf['enabled'] == 0)
  {
    return;
  }
  
  // coming from identification page
  if (pwg_get_session_var('oauth_new_user') != null)
  {
    list($provider, $user_identifier) = pwg_get_session_var('oauth_new_user');
    
    try {
      if ($provider == 'Persona')
      {
        $template->assign('OAUTH_USER', array(
          'provider' => 'Persona',
          'username' => $user_identifier,
          'u_profile' => null,
          'avatar' => null,
          ));
        
        oauth_assign_template_vars();
        $template->append('OAUTH', array('persona_email'=>$user_identifier), true);
      
        $conf['oauth']['include_common_template'] = true;
      }
      else
      {
        require_once(OAUTH_PATH . 'include/hybridauth/Hybrid/Auth.php');
        
        $hybridauth = new Hybrid_Auth($hybridauth_conf);
        $adapter = $hybridauth->authenticate($provider);
        $remote_user = $adapter->getUserProfile();
        
        // security, check remote identifier
        if ($remote_user->identifier != $user_identifier)
        {
          pwg_unset_session_var('oauth_new_user');
          throw new Exception('Hacking attempt!', 403);
        }
      
        $template->assign('OAUTH_USER', array(
          'provider' => $hybridauth_conf['providers'][$provider]['name'],
          'username' => $remote_user->displayName,
          'u_profile' => $remote_user->profileURL,
          'avatar' => $remote_user->photoURL,
          ));
      }
      
      $oauth_id = $provider.'---'.$user_identifier;
      
      $page['infos'][] = l10n('Your registration is almost done, please complete the registration form.');
      
      // form submited
      if (isset($_POST['submit']))
      {
        $user_id = register_user(
          $_POST['login'],
          hash('sha1', $oauth_id.$conf['secret_key']),
          $_POST['mail_address'],
          true,
          $page['errors'],
          false
          );

        if ($user_id !== false)
        {
          pwg_unset_session_var('oauth_new_user');
          
          // update oauth field
          $query = '
UPDATE ' . USERS_TABLE . '
  SET oauth_id = "' . $oauth_id . '"
  WHERE ' . $conf['user_fields']['id'] . ' = ' . $user_id . '
;';
          pwg_query($query);
          
          // log_user and redirect
          log_user($user_id, false);
          redirect('profile.php');
        }
      
        unset($_POST['submit']);
      }
      else
      {
        // overwrite fields with remote datas
        if ($provider == 'Persona')
        {
          $_POST['login'] = '';
          $_POST['mail_address'] = $user_identifier;
        }
        else
        {
          $_POST['login'] = $remote_user->displayName;
          $_POST['mail_address'] = $remote_user->email;
        }
      }
      
      // template
      $template->assign('OAUTH_PATH', OAUTH_PATH);
      $template->set_prefilter('register', 'oauth_add_profile_prefilter');
      $template->set_prefilter('register', 'oauth_remove_password_fields_prefilter');
    }
    catch (Exception $e)
    {
      $page['errors'][] = l10n('An error occured, please contact the gallery owner. <i>Error code : %s</i>', $e->getCode());
    }
  }
  // display login buttons
  else if ($conf['oauth']['display_register'])
  {
    oauth_assign_template_vars(get_gallery_home_url());
    
    $template->set_prefilter('register', 'oauth_add_buttons_prefilter');
  }
}


/**
 * profile page
 */
function oauth_begin_profile()
{
  global $template, $user, $hybridauth_conf, $page, $user;
  
  if (empty($user['oauth_id']))
  {
    return;
  }
  
  list($provider, $user_identifier) = explode('---', $user['oauth_id'], 2);
  
  try {
    if ($provider == 'Persona')
    {
      $template->assign('OAUTH_USER', array(
        'provider' => 'Persona',
        'username' => $user_identifier,
        'u_profile' => null,
        'avatar' => null,
        ));
    }
    else
    {
      require_once(OAUTH_PATH . 'include/hybridauth/Hybrid/Auth.php');
      
      $hybridauth = new Hybrid_Auth($hybridauth_conf);
      $adapter = $hybridauth->getAdapter($provider);
      $remote_user = $adapter->getUserProfile();
      
      $template->assign('OAUTH_USER', array(
        'provider' => $hybridauth_conf['providers'][$provider]['name'],
        'username' => $remote_user->displayName,
        'u_profile' => $remote_user->profileURL,
        'avatar' => $remote_user->photoURL,
        ));
    }
    
    $template->assign('OAUTH_PATH', OAUTH_PATH);
    $template->set_prefilter('profile_content', 'oauth_add_profile_prefilter');
    $template->set_prefilter('profile_content', 'oauth_remove_password_fields_prefilter');
  }
  catch (Exception $e)
  {
    $page['errors'][] = l10n('An error occured, please contact the gallery owner. <i>Error code : %s</i>', $e->getCode());
  }
}


/**
 * logout
 */
function oauth_logout($user_id)
{
  global $hybridauth_conf;
  
  $oauth_id = get_oauth_id($user_id);
  
  if (!isset($oauth_id))
  {
    return;
  }

  list($provider, $identifier) = explode('---', $oauth_id, 2);
  
  
  if ($provider != 'Persona')
  {
    require_once(OAUTH_PATH . 'include/hybridauth/Hybrid/Auth.php');
    
    try {
      $hybridauth = new Hybrid_Auth($hybridauth_conf);
      $adapter = $hybridauth->getAdapter($provider);
      $adapter->logout();
    }
    catch (Exception $e) {
      $_SESSION['page_errors'][] = l10n('An error occured, please contact the gallery owner. <i>Error code : %s</i>', $e->getCode());
    }
  }
}


/**
 * identification menu block
 */
function oauth_blockmanager($menu_ref_arr)
{
  global $template, $conf, $hybridauth_conf;
  
  $menu = &$menu_ref_arr[0];  
  
  if ($hybridauth_conf['enabled'] == 0 or
      !$conf['oauth']['display_menubar'] or
      $menu->get_block('mbIdentification') == null
    )
  {
    return;
  }
  
  $u_redirect = !empty($_GET['redirect']) ? urldecode($_GET['redirect']) : get_gallery_home_url();
  oauth_assign_template_vars($u_redirect);
  
  $template->set_prefilter('menubar', 'oauth_add_menubar_buttons_prefilter');
}


/**
 * load common javascript
 */
function oauth_page_header()
{
  global $conf, $template;

  if (isset($conf['oauth']['include_common_template']))
  {
    $template->set_filename('oauth', realpath(OAUTH_PATH . 'template/identification_common.tpl'));
    $template->parse('oauth');
  }
}


/**
 * prefilters
 */
function oauth_add_buttons_prefilter($content)
{
  $search = '</form>';
  $add = file_get_contents(OAUTH_PATH . 'template/identification_page.tpl');
  return str_replace($search, $search.$add, $content);
}

function oauth_remove_password_fields_prefilter($content)
{
  $search = 'type="password" ';
  $add = 'disabled="disabled" ';
  $script = '
{footer_script require="jquery"}
jQuery("input[type=password], input[name=send_password_by_mail]").parent().hide();
{/footer_script}';

  $content = str_replace($search, $search.$add, $content);
  return $content.$script;
}

function oauth_add_profile_prefilter($content)
{
  $search = '#(</legend>)#';
  $add = file_get_contents(OAUTH_PATH . 'template/profile.tpl');
  return preg_replace($search, '$1 '.$add, $content, 1);
}

function oauth_add_menubar_buttons_prefilter($content)
{
  $search = '#({include file=\$block->template\|@?get_extent:\$id ?})#';
  $add = file_get_contents(OAUTH_PATH . 'template/identification_menubar.tpl');
  return preg_replace($search, '$1 '.$add, $content);
}
