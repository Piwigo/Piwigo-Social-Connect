<?php
defined('PHPWG_ROOT_PATH') or die('Hacking attempt!');

function oauth_install() 
{
  global $conf;
  
  if (empty($conf['oauth']))
  {
    $oauth_default_config = serialize(array(
      'display_menubar' => true,
      'display_register' => true,
      'identification_icon' => '38px',
      'menubar_icon' => '26px',
      ));
  
    conf_update_param('oauth', $oauth_default_config);
    $conf['oauth'] = $oauth_default_config;
  }
  
  $result = pwg_query('SHOW COLUMNS FROM `'.USERS_TABLE.'` LIKE "oauth_id";');
  if (!pwg_db_num_rows($result))
  {      
    pwg_query('ALTER TABLE `' . USERS_TABLE . '` ADD `oauth_id` VARCHAR(255) DEFAULT NULL;');
  }
}

?>