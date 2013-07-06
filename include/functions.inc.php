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

function get_activated_providers()
{
  global $hybridauth_conf;
  
  return array_filter($hybridauth_conf['providers'], create_function('$p', 'return $p["enabled"];'));
}

function oauth_assign_template_vars()
{
  global $template, $conf;
  
  if ($template->get_template_vars('OAUTH_URL') == null)
  {
    $template->assign(array(
      'oauth' => $conf['oauth'],
      'OAUTH_URL' => get_root_url() . OAUTH_PATH . 'auth.php?provider=',
      'OAUTH_PATH' => OAUTH_PATH,
      'OAUTH_ABS_PATH' => realpath(OAUTH_PATH) . '/',
      'PROVIDERS' => get_activated_providers(),
      'ABS_ROOT_URL' => rtrim(get_gallery_home_url(), '/') . '/',
      ));
  }
}

?>