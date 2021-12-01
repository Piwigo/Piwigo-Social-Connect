<?php 
/*
Plugin Name: Social Connect
Version: auto
Description: Provides various ways to sign in your gallery (Twitter, Facebook, Google, etc.)
Plugin URI: auto
Author: Mistic
Author URI: http://www.strangeplanet.
Has Settings: true
*/

defined('PHPWG_ROOT_PATH') or die('Hacking attempt!');

if (basename(dirname(__FILE__)) != 'oAuth')
{
  add_event_handler('init', 'ouath_error');
  function ouath_error()
  {
    global $page;
    $page['errors'][] = 'Social Connect folder name is incorrect, uninstall the plugin and rename it to "oAuth"';
  }
  return;
}

define('OAUTH_PATH' ,  PHPWG_PLUGINS_PATH . 'oAuth/');
define('OAUTH_ADMIN',  get_root_url() . 'admin.php?page=plugin-oAuth');
define('OAUTH_CONFIG', PWG_LOCAL_DIR . 'config/hybridauth.inc.php');
define('OAUTH_PUBLIC', get_absolute_root_url() . ltrim(OAUTH_PATH,'./') . 'include/hybridauth/');

include_once(OAUTH_PATH . 'include/functions.inc.php');


// try to load hybridauth config
global $hybridauth_conf;
load_hybridauth_conf();


add_event_handler('init', 'oauth_init');

if (defined('IN_ADMIN'))
{
  add_event_handler('get_admin_plugin_menu_links', 'oauth_admin_plugin_menu_links');
  
  add_event_handler('user_list_columns', 'oauth_user_list_columns');
  add_event_handler('after_render_user_list', 'oauth_user_list_render');
  
  add_event_handler('loc_begin_admin_page', 'oauth_user_list');
  
  include_once(OAUTH_PATH . 'include/admin_events.inc.php');
}
else if (!empty($hybridauth_conf) and function_exists('curl_init'))
{
  add_event_handler('loc_begin_identification', 'oauth_begin_identification');
  add_event_handler('loc_begin_register', 'oauth_begin_register');
  add_event_handler('loc_begin_profile', 'oauth_begin_profile');
  
  add_event_handler('loc_after_page_header', 'oauth_page_header');
  
  add_event_handler('try_log_user', 'oauth_try_log_user', EVENT_HANDLER_PRIORITY_NEUTRAL-30, 2);
  add_event_handler('user_logout', 'oauth_logout');
  
  add_event_handler('blockmanager_apply', 'oauth_blockmanager');
  
  include_once(OAUTH_PATH . 'include/public_events.inc.php');
}


/**
 * plugin initialization
 */
function oauth_init()
{
  global $conf, $page, $hybridauth_conf, $template;
  
  load_language('plugin.lang', OAUTH_PATH);
  
  $conf['oauth'] = safe_unserialize($conf['oauth']);
  
  // check config
  if (defined('IN_ADMIN'))
  {
    if (empty($hybridauth_conf) and strpos(@$_GET['page'],'plugin-oAuth')===false)
    {
      $page['warnings'][] = '<a href="'.OAUTH_ADMIN.'">'.l10n('Social Connect: You need to configure the credentials').'</a>';
    }
    if (!function_exists('curl_init'))
    {
      $page['warnings'][] = l10n('Social Connect: PHP Curl extension is needed');
    }
  }
  
  // in case of registration aborded
  if ( script_basename() == 'index' and ($oauth_id=pwg_get_session_var('oauth_new_user')) !== null )
  {
    pwg_unset_session_var('oauth_new_user');
    
    require_once(OAUTH_PATH . 'include/hybridauth/Hybrid/Auth.php');
    
    try {
      $hybridauth = new Hybrid_Auth($hybridauth_conf);
      $adapter = $hybridauth->getAdapter($oauth_id[0]);
      $adapter->logout();
    }
    catch (Exception $e) {}
  }
}
