<?php
defined('OAUTH_PATH') or die('Hacking attempt!');

/**
 * admin plugins menu link
 */
function oauth_admin_plugin_menu_links($menu) 
{
  array_push($menu, array(
    'NAME' => l10n('oAuth'),
    'URL' => OAUTH_ADMIN,
  ));
  return $menu;
}


?>