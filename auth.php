<?php
define('PHPWG_ROOT_PATH', '../../');
include_once(PHPWG_ROOT_PATH.'include/common.inc.php');

global $hybridauth_conf;
require_once(OAUTH_PATH . 'include/hybridauth/Hybrid/Auth.php');

$provider = @$_GET['provider'];

try {
  // inputs
  if ( $provider == 'OpenID' and !isset($_GET['openid_identifier']) )
  {
    throw new Exception('Invalid OpenID!', 1003);
  }
  
  if (
    !array_key_exists($provider, $hybridauth_conf['providers'])
    or !$hybridauth_conf['providers'][$provider]['enabled']
  ) {
    throw new Exception('Invalid provider!', 1002);
  }
  
  $hybridauth = new Hybrid_Auth($hybridauth_conf);
  
  // connected
  if ($hybridauth->isConnectedWith($provider))
  {
    $adapter = $hybridauth->getAdapter($provider);
    $remote_user = $adapter->getUserProfile();
    
    $oauth_id = $provider.'---'.$remote_user->identifier;
    
    // check is already registered
    $query = '
SELECT id FROM '.USERS_TABLE.'
  WHERE oauth_id = "'.$oauth_id.'"
;';
    $result = pwg_query($query);
    // registered : log_user and redirect
    if (pwg_db_num_rows($result))
    {
      list($user_id) = pwg_db_fetch_row($result);
      log_user($user_id, false);
      
      $template->assign('REDIRECT_TO', 'default');
    }
    // not registered : redirect to register page
    else
    {
      if ($conf['allow_user_registration'])
      {
        pwg_set_session_var('oauth_new_user', array($provider,$remote_user->identifier));
        $template->assign('REDIRECT_TO', 'register');
      }
      else
      {
        $_SESSION['page_errors'][] = l10n('Sorry, new registrations are blocked on this gallery.');
        $adapter->logout();
        $template->assign('REDIRECT_TO', 'identification');
      }
    }
  }
  // init connect
  else if (isset($_GET['init_auth']))
  {
    $params = array();
    if ($provider == 'OpenID')
    {
      $params['openid_identifier'] = $_GET['openid_identifier'];
    }
      
    // try to authenticate
    $adapter = $hybridauth->authenticate($provider, $params);
  }
  // display loader
  else
  {
    $template->assign('LOADING', '&openid_identifier='.@$_GET['openid_identifier'].'&init_auth=1');
  }
}
/*
 library errors :
     0 : Unspecified error
     1 : Hybriauth configuration error
     2 : Provider not properly configured
     3 : Unknown or disabled provider
     4 : Missing provider application credentials
     5 : Authentication aborded
     6 : User profile request failed
 other errors :
  1002 : Invalid provider
  1003 : Missing openid_identifier
*/
catch (Exception $e) {
  switch ($e->getCode()) {
    case 5:
      $template->assign('ERROR', l10n('Authentication canceled')); break;
    case 404:
      $template->assign('ERROR', l10n('User not found')); break;
    default:
      $template->assign('ERROR', sprintf(l10n('An error occured, please contact the gallery owner. <i>Error code : %s</i>'), '<span title="'.$e->getMessage().'">'.$e->getCode().'</span>'));
  }
}


$template->assign(array(
  'GALLERY_TITLE' => $conf['gallery_title'],
  'CONTENT_ENCODING' => get_pwg_charset(),
  'U_HOME' => get_gallery_home_url(),
  
  'OAUTH_PATH' => OAUTH_PATH,
  'PROVIDER' => $provider,
  'SELF_URL' => OAUTH_PATH . 'auth.php?provider='.$provider,
  ));

$template->set_filename('index', realpath(OAUTH_PATH . 'template/auth.tpl'));
$template->pparse('index');
?>