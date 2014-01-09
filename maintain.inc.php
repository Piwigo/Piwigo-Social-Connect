<?php
defined('PHPWG_ROOT_PATH') or die('Hacking attempt!');

class oAuth_maintain extends PluginMaintain
{
  private $installed = false;
  
  private $default_conf = array(
    'display_menubar' => true,
    'display_register' => true,
    'identification_icon' => '38px',
    'menubar_icon' => '26px',
    );

  function install($plugin_version, &$errors=array())
  {
    global $conf;

    if (empty($conf['oauth']))
    {
      $conf['oauth'] = serialize($this->default_conf);
      conf_update_param('oauth', $conf['oauth']);
    }
    
    $result = pwg_query('SHOW COLUMNS FROM `' . USERS_TABLE . '` LIKE "oauth_id";');
    if (!pwg_db_num_rows($result))
    {      
      pwg_query('ALTER TABLE `' . USERS_TABLE . '` ADD `oauth_id` VARCHAR(255) DEFAULT NULL;');
    }

    $this->installed = true;
  }

  function activate($plugin_version, &$errors=array())
  {
    if (!$this->installed)
    {
      $this->install($plugin_version, $errors);
    }
  }

  function deactivate()
  {
  }

  function uninstall()
  {
    conf_delete_param('oauth');

    pwg_query('ALTER TABLE `'. USERS_TABLE .'` DROP `oauth_id`;');
    
    @unlink(PHPWG_PLUGINS_PATH . PWG_LOCAL_DIR . 'config/hybridauth.inc.php');
  }
}
