<?php
defined('PHPWG_ROOT_PATH') or die('Hacking attempt!');

class oAuth_maintain extends PluginMaintain
{
  private $default_conf = array(
    'display_menubar' => true,
    'display_register' => true,
    'identification_icon' => '38px',
    'menubar_icon' => '26px',
    'allow_merge_accounts' => true,
    );
    
  private $file;
  
  function __construct($plugin_id)
  {
    parent::__construct($plugin_id);
    $this->file = PWG_LOCAL_DIR . 'config/hybridauth.inc.php';
  }

  function install($plugin_version, &$errors=array())
  {
    global $conf;

    if (empty($conf['oauth']))
    {
      conf_update_param('oauth', $this->default_conf, true);
    }
    else
    {
      $conf['oauth'] = safe_unserialize($conf['oauth']);
      
      if (!isset($conf['oauth']['allow_merge_accounts']))
      {
        $conf['oauth']['allow_merge_accounts'] = true;
        
        conf_update_param('oauth', $conf['oauth']);
      }
    }
    
    $result = pwg_query('SHOW COLUMNS FROM `' . USER_INFOS_TABLE . '` LIKE "oauth_id";');
    if (!pwg_db_num_rows($result))
    {
      pwg_query('ALTER TABLE `' . USER_INFOS_TABLE . '` ADD `oauth_id` VARCHAR(255) DEFAULT NULL;');
    }
    else
    {
      // delete Persona auth
      pwg_query('UPDATE `' . USER_INFOS_TABLE . '` SET `oauth_id` = NULL WHERE oauth_id LIKE \'Persona---%\';');
    }
    
    // move field from users table to user_infos
    $result = pwg_query('SHOW COLUMNS FROM `' . USERS_TABLE . '` LIKE "oauth_id";');
    if (pwg_db_num_rows($result))
    {
      $query = '
UPDATE `' . USER_INFOS_TABLE . '` AS i
  SET oauth_id = (
    SELECT oauth_id
      FROM `' . USERS_TABLE . '` AS u
      WHERE u.'.$conf['user_fields']['id'].' = i.user_id
    )
;';
      pwg_query($query);
      
      pwg_query('ALTER TABLE `' . USERS_TABLE . '` DROP `oauth_id`;');
    }
    
    // add 'total' and 'enabled' fields in hybridauth conf file
    if (file_exists($this->file))
    {
      $hybridauth_conf = include($this->file);
      if (!isset($hybridauth_conf['total']))
      {
        $enabled = array_filter($hybridauth_conf['providers'], create_function('$p', 'return $p["enabled"];'));
        
        $hybridauth_conf['total'] = count($hybridauth_conf['providers']);
        $hybridauth_conf['enabled'] = count($enabled);
        
        $content = "<?php\ndefined('PHPWG_ROOT_PATH') or die('Hacking attempt!');\n\nreturn ";
        $content.= var_export($hybridauth_conf, true);
        $content.= ";\n?>";
        
        file_put_contents($this->file, $content);
      }
    }
  }

  function update($old_version, $new_version, &$errors=array())
  {
    $this->install($new_version, $errors);
  }

  function uninstall()
  {
    conf_delete_param('oauth');

    pwg_query('ALTER TABLE `'. USER_INFOS_TABLE .'` DROP `oauth_id`;');
    
    @unlink($this->file);
  }
}
