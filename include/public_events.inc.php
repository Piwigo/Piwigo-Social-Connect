<?php
defined('OAUTH_PATH') or die('Hacking attempt!');

function oauth_anti_lightbox($tpl_thumbnails_var)
{
  global $template, $page;
  
  if ($page['section'] == 'collections' && !empty($template->css_by_priority[0]))
  {
    foreach ($template->css_by_priority[0] as $file)
    {
      if (strpos($file[0], 'colorbox.css') !== false)
      {
        $template->assign('OAUTH_NO_LIGHTBOX', true);
        break;
      }
    }
  }
  
  return $tpl_thumbnails_var;
}

/**
 * identification page
 */
function oauth_begin_identification()
{
  global $template, $conf;
  
  oauth_assign_template_vars();
  $template->assign('REDIRECT_TO', !empty($_GET['redirect']) ? urldecode($_GET['redirect']) : get_gallery_home_url());
  
  $template->set_prefilter('identification', 'oauth_add_buttons_prefilter');
}

/**
 * interrupt normal login if corresponding to an oauth user
 */
function oauth_try_log_user($success, $username)
{
  global $conf, $redirect_to;
  
  $query = '
SELECT oauth_id FROM '.USERS_TABLE.'
  WHERE '.$conf['user_fields']['username'].' = "'.pwg_db_real_escape_string($username).'"
  AND oauth_id != ""
;';
  $result = pwg_query($query);
  
  if (pwg_db_num_rows($result))
  {
    list($oauth_id) = pwg_db_fetch_row($result);
    list($provider) = explode('---', $oauth_id);
    $_SESSION['page_errors'][] = sprintf(l10n('You registered with a %s account, please sign in with the same account.'), $provider);
    
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
  
  // coming from identification page
  if (pwg_get_session_var('oauth_new_user') != null)
  {
    list($provider, $user_identifier) = pwg_get_session_var('oauth_new_user');
    
    require_once(OAUTH_PATH . 'include/hybridauth/Hybrid/Auth.php');
    
    try {
      $hybridauth = new Hybrid_Auth($hybridauth_conf);
      $adapter = $hybridauth->authenticate($provider);
      $remote_user = $adapter->getUserProfile();
      
      // security, check remote identifier
      if ($remote_user->identifier != $user_identifier)
      {
        pwg_unset_session_var('oauth_new_user');
        throw new Exception('Hacking attempt!', 403);
      }
    
      $template->assign(array(
        'OAUTH_PROVIDER' => $provider,
        'OAUTH_USERNAME' => $remote_user->displayName,
        'OAUTH_PROFILE_URL' => $remote_user->profileURL,
        'OAUTH_AVATAR' => $remote_user->photoURL,
        'OAUTH_PATH' => OAUTH_PATH,
        ));
        
      array_push($page['infos'], l10n('Your registration is almost done, please complete the registration form.'));
      
      $oauth_id = $provider.'---'.$remote_user->identifier;
      
      // form submited
      if (isset($_POST['submit']))
      {
        $page['errors'] =
          register_user($_POST['login'],
                        hash('sha1', $oauth_id.$conf['secret_key']),
                        $_POST['mail_address']
                        );
                        
        if (count($page['errors']) == 0)
        {
          pwg_unset_session_var('oauth_new_user');
          $user_id = get_userid($_POST['login']);
          
          // update oauth field
          $query = '
UPDATE '.USERS_TABLE.'
  SET oauth_id = "'.$oauth_id.'"
  WHERE '.$conf['user_fields']['id'].' = '.$user_id.'
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
        $_POST['login'] = $remote_user->displayName;
        $_POST['mail_address'] = $remote_user->email;
      }
      
      // template
      $template->set_prefilter('register', 'oauth_add_profile_prefilter');
      $template->set_prefilter('register', 'oauth_remove_password_fields_prefilter');
    }
    catch (Exception $e) {
      array_push($page['errors'], sprintf(l10n('An error occured, please contact the gallery owner. <i>Error code : %s</i>'), $e->getCode()));
    }
  }
  // display login buttons
  else if ($conf['oauth']['display_register'])
  {
    oauth_assign_template_vars();
    $template->assign('REDIRECT_TO', get_gallery_home_url());
    
    $template->set_prefilter('register', 'oauth_add_buttons_prefilter');
  }
}


/**
 * profile page
 */
function oauth_begin_profile()
{
  global $template, $user, $conf, $hybridauth_conf, $page;
  
  $query = '
SELECT oauth_id FROM '.USERS_TABLE.'
  WHERE '.$conf['user_fields']['id'].' = '.$user['id'].'
  AND oauth_id != ""
;';
  $result = pwg_query($query);
  
  if (!pwg_db_num_rows($result))
  {
    return;
  }
  
  list($oauth_id) = pwg_db_fetch_row($result);
  list($provider) = explode('---', $oauth_id);
  
  require_once(OAUTH_PATH . 'include/hybridauth/Hybrid/Auth.php');
  
  try {
    $hybridauth = new Hybrid_Auth($hybridauth_conf);
    $adapter = $hybridauth->getAdapter($provider);
    $remote_user = $adapter->getUserProfile();
    
    $template->assign(array(
      'OAUTH_PROVIDER' => $provider,
      'OAUTH_USERNAME' => $remote_user->displayName,
      'OAUTH_PROFILE_URL' => $remote_user->profileURL,
      'OAUTH_AVATAR' => $remote_user->photoURL,
      'OAUTH_PATH' => OAUTH_PATH,
      ));
    
    $template->set_prefilter('profile_content', 'oauth_add_profile_prefilter');
    $template->set_prefilter('profile_content', 'oauth_remove_password_fields_prefilter');
  }
  catch (Exception $e) {
    array_push($page['errors'], sprintf(l10n('An error occured, please contact the gallery owner. <i>Error code : %s</i>'), $e->getCode()));
  }
}


/**
 * logout
 */
function oauth_logout($user_id)
{
  global $conf, $hybridauth_conf;
  
  $query = '
SELECT oauth_id FROM '.USERS_TABLE.'
  WHERE '.$conf['user_fields']['id'].' = '.$user_id.'
  AND oauth_id != ""
;';
  $result = pwg_query($query);
  
  if (!pwg_db_num_rows($result))
  {
    return;
  }
  
  list($oauth_id) = pwg_db_fetch_row($result);
  list($provider) = explode('---', $oauth_id);
  
  require_once(OAUTH_PATH . 'include/hybridauth/Hybrid/Auth.php');
  
  try {
    $hybridauth = new Hybrid_Auth($hybridauth_conf);
    $adapter = $hybridauth->getAdapter($provider);
    $adapter->logout();
  }
  catch (Exception $e) {
    $_SESSION['page_errors'][] = sprintf(l10n('An error occured, please contact the gallery owner. <i>Error code : %s</i>'), $e->getCode());
  }
}


/**
 * identification menu block
 */
function oauth_blockmanager($menu_ref_arr)
{
  global $template, $conf;
  
  $menu = &$menu_ref_arr[0];  
  
  if ( !$conf['oauth']['display_menubar'] or $menu->get_block('mbIdentification') == null )
  {
    return;
  }
  
  oauth_assign_template_vars();
  $template->assign('REDIRECT_TO', get_gallery_home_url());
  
  $template->set_prefilter('menubar', 'oauth_add_menubar_buttons_prefilter');
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
jQuery("input[type=\'password\'], input[name=\'send_password_by_mail\']").parent().css("display", "none");
{/footer_script}';

  $content = str_replace($search, $search.$add, $content);
  return $content.$script;
}

function oauth_add_profile_prefilter($content)
{
  $search = '#</legend>#';
  $add = file_get_contents(OAUTH_PATH . 'template/profile.tpl');
  return preg_replace($search, '</legend> '.$add, $content, 1);
}

function oauth_add_menubar_buttons_prefilter($content)
{
  $search = '{include file=$block->template|@get_extent:$id }';
  $add = file_get_contents(OAUTH_PATH . 'template/identification_menubar.tpl');
  return str_replace($search, $search.$add, $content);
}

?>