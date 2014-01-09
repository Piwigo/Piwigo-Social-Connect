<?php
defined('OAUTH_PATH') or die('Hacking attempt!');

if (isset($_POST['save_config']))
{
  // plugin config
  $conf['oauth'] = array(
    'display_register' => isset($_POST['display_register']),
    'display_menubar' => isset($_POST['display_menubar']),
    'identification_icon' => $_POST['identification_icon'],
    'menubar_icon' => $_POST['menubar_icon'],
    );
    
  conf_update_param('oauth', serialize($conf['oauth']));
  $page['infos'][] = l10n('Information data registered in database');
}

$template->assign($conf['oauth']);

// define template file
$template->set_filename('oauth_content', realpath(OAUTH_PATH . 'admin/template/config.tpl'));
