<?php 
/*
Plugin Name: Social Connect (OAuth)
Version: auto
Description: Provides various ways to sign in your gallery (Twitter, Facebook, Google, etc.)
Plugin URI: auto
Author: Mistic
Author URI: http://www.strangeplanet.fr
*/

defined('PHPWG_ROOT_PATH') or die('Hacking attempt!');


define('OAUTH_ID',      basename(dirname(__FILE__)));
define('OAUTH_PATH' ,   PHPWG_PLUGINS_PATH . OAUTH_ID . '/');
define('OAUTH_ADMIN',   get_root_url() . 'admin.php?page=plugin-' . OAUTH_ID);
define('OAUTH_CONFIG',  PWG_LOCAL_DIR . 'config/hybridauth.inc.php');
define('OAUTH_PUBLIC',  get_absolute_root_url() . ltrim(OAUTH_PATH,'./') . 'include/hybridauth/');
define('OAUTH_VERSION', 'auto');


global $hybridauth_conf, $conf;

// try to load hybridauth config
include_once(OAUTH_PATH . 'include/functions.inc.php');

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
  
  include_once(OAUTH_PATH . 'maintain.inc.php');
  $maintain = new oAuth_maintain(OAUTH_ID);
  $maintain->autoUpdate(OAUTH_VERSION, 'install');
  
  load_language('plugin.lang', OAUTH_PATH);
  
  $conf['oauth'] = unserialize($conf['oauth']);
  
  // check config
  if (defined('IN_ADMIN'))
  {
    if (empty($hybridauth_conf) and strpos(@$_GET['page'],'plugin-'.OAUTH_ID)===false)
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
    
    if ($oauth_id[0] == 'Persona')
    {
      oauth_assign_template_vars(get_gallery_home_url());
      $template->block_footer_script(null, 'navigator.id.logout();');
    }
    else
    {
      require_once(OAUTH_PATH . 'include/hybridauth/Hybrid/Auth.php');
      
      try {
        $hybridauth = new Hybrid_Auth($hybridauth_conf);
        $adapter = $hybridauth->getAdapter($oauth_id[0]);
        $adapter->logout();
      }
      catch (Exception $e) {}
    }
  }
}
