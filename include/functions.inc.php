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
  
  $out = array();
  
  foreach ($hybridauth_conf['providers'] as $id => $data)
  {
    if ($data['enabled'])
    {
      $out[$id] = $data;
    }
  }
  
  return $out;
}


?>