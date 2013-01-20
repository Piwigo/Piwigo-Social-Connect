<?php
defined('PHPWG_ROOT_PATH') or die('Hacking attempt!');

defined('OAUTH_ID') or define('OAUTH_ID', basename(dirname(__FILE__)));
include_once(PHPWG_PLUGINS_PATH . OAUTH_ID . '/include/install.inc.php');


function plugin_install() 
{
  oauth_install();
  define('oauth_installed', true);
}


function plugin_activate()
{
  if (!defined('oauth_installed'))
  {
    oauth_install();
  }
}

function plugin_uninstall() 
{
  pwg_query('DELETE FROM `'. CONFIG_TABLE .'` WHERE param = "oauth" LIMIT 1;');
  pwg_query('ALTER TABLE `'. USERS_TABLE .'` DROP `oauth_id`;');
  @unlink(PHPWG_PLUGINS_PATH . PWG_LOCAL_DIR . 'config/hybridauth.inc.php');
}

?>