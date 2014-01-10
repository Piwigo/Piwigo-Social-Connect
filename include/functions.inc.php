<?php
defined('OAUTH_PATH') or die('Hacking attempt!');

function load_hybridauth_conf()
{
  global $hybridauth_conf, $conf;
  
  if (file_exists(PHPWG_ROOT_PATH.OAUTH_CONFIG))
  {
    $hybridauth_conf = include(PHPWG_ROOT_PATH.OAUTH_CONFIG);
    $hybridauth_conf['base_url'] = OAUTH_PUBLIC;
    if (!empty($conf['oauth_debug_file']))
    {
      $hybridauth_conf['debug_mode'] = true;
      $hybridauth_conf['debug_file'] = $conf['oauth_debug_file'];
    }
    return true;
  }
  else
  {
    return false;
  }
}

function oauth_assign_template_vars($u_redirect=null)
{
  global $template, $conf, $hybridauth_conf;
  
  $conf['oauth']['include_common_template'] = true;
  
  if ($template->get_template_vars('OAUTH') == null)
  {
    $template->assign('OAUTH', array(
      'conf' => $conf['oauth'],
      'u_login' => get_root_url() . OAUTH_PATH . 'auth.php?provider=',
      'providers' => $hybridauth_conf['providers'],
      ));
    $template->assign(array(
      'OAUTH_PATH' => OAUTH_PATH,
      'OAUTH_ABS_PATH' => realpath(OAUTH_PATH) . '/',
      'ABS_ROOT_URL' => rtrim(get_gallery_home_url(), '/') . '/',
      ));
  }
  
  if (isset($u_redirect))
  {
    $template->append('OAUTH', compact('u_redirect'), true);
  }
}

function get_oauth_id($user_id)
{
  global $conf;
  
  $query = '
SELECT oauth_id FROM ' . USERS_TABLE . '
  WHERE ' . $conf['user_fields']['id'] . ' = ' . $user_id . '
  AND oauth_id != ""
;';
  $result = pwg_query($query);
  
  if (!pwg_db_num_rows($result))
  {
    return null;
  }
  else
  {
    list($oauth_id) = pwg_db_fetch_row($result);
    return $oauth_id;
  }
}
