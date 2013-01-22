<?php
defined('OAUTH_PATH') or die('Hacking attempt!');
 
global $template, $page, $conf;

if (!$conf['allow_user_registration'])
{
  array_push($page['errors'], l10n('Users are not allowed to register on your gallery. oAuth will not work correctly.'));
}


// get current tab
$page['tab'] = (isset($_GET['tab'])) ? $_GET['tab'] : $page['tab'] = 'providers';

// tabsheet
include_once(PHPWG_ROOT_PATH.'admin/include/tabsheet.class.php');
$tabsheet = new tabsheet();
$tabsheet->set_id('oauth');

$tabsheet->add('providers', l10n('Providers'), OAUTH_ADMIN . '-providers');
$tabsheet->add('config', l10n('Configuration'), OAUTH_ADMIN . '-config');
$tabsheet->select($page['tab']);
$tabsheet->assign();
  
// include page
include(OAUTH_PATH . 'admin/' . $page['tab'] . '.php');

// template vars
$template->assign('OAUTH_PATH', get_root_url() . OAUTH_PATH);
  
// send page content
$template->assign_var_from_handle('ADMIN_CONTENT', 'oauth_content');

?>