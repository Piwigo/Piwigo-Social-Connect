<?php
use Hybridauth\Hybridauth;
use Hybridauth\HttpClient\Util;



define('PHPWG_ROOT_PATH', '../../');
include_once(PHPWG_ROOT_PATH.'include/common.inc.php');
require_once(OAUTH_PATH . 'include/hybridauth/Hybridauth.php');
require_once(OAUTH_PATH . 'include/hybridauth/autoload.php');
require_once(OAUTH_PATH . 'include/hybridauth/HttpClient/Util.php');

global $hybridauth_conf;
$provider = @$_GET['provider'];

// OpenID is always enabled
$hybridauth_conf['providers']['OpenID']['enabled'] = true;
$hybridauth_conf['providers']['Google']['callback'] = Util::getCurrentUrl()."?provider=".$provider;
//$hybridauth_conf['callback'] = Util::getCurrentUrl();

try {
  if (!array_key_exists($provider, $hybridauth_conf['providers'])
      or !$hybridauth_conf['providers'][$provider]['enabled']
    )
  {
    throw new Exception('Invalid provider!', 1002);
  }

  if ($provider == 'OpenID' and empty($_GET['openid_identifier']))
  {
    throw new Exception('Invalid OpenID!', 1003);
  }

  $hybridauth = new Hybridauth($hybridauth_conf);
  if(!$hybridauth->isConnectedWith($provider)){
    $adapter = $hybridauth->authenticate($provider);
  }


  if ($hybridauth->isConnectedWith($provider))
  {
    $adapter = $hybridauth->getAdapter($provider);
    $remote_user = $adapter->getUserProfile();

    $oauth_id = array($provider,$remote_user->email);
  }

  // connected
  if (!empty($oauth_id))
  {
    // check is already registered
    $query = '
SELECT user_id FROM ' . USER_INFOS_TABLE . '
  WHERE oauth_id = \'' . pwg_db_real_escape_string(implode('---', $oauth_id)) . '\'
;';
    $result = pwg_query($query);

    // registered : log_user and redirect
    if (pwg_db_num_rows($result))
    {
      list($user_id) = pwg_db_fetch_row($result);
      log_user($user_id, false);

      $redirect_to = 'default';
    }
    // not registered : redirect to register page
    else
    {
      if ($conf['allow_user_registration'])
      {
        pwg_set_session_var('oauth_new_user', $oauth_id);
        $redirect_to = 'register';
      }
      else
      {
        $_SESSION['page_errors'][] = l10n('Sorry, new registrations are blocked on this gallery.');
        if (isset($adapter)) $adapter->disconnect();
        $redirect_to = 'identification';
      }
    }

    $template->assign('REDIRECT_TO', $redirect_to);
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
    if (!verify_ephemeral_key(@$_GET['key']))
    {
      throw new Exception('Forbidden', 403);
    }

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
   404 : User not found
 other errors :
   403 : Invalid ephemeral key
  1002 : Invalid provider
  1003 : Missing openid_identifier
*/
catch (Exception $e)
{
  switch ($e->getCode())
  {
    case 5:
      $template->assign('ERROR', l10n('Authentication canceled')); break;
    case 404:
      $template->assign('ERROR', l10n('User not found')); break;
    default:
      $template->assign('ERROR', l10n('An error occured, please contact the gallery owner. <i>Error code : %s</i>', '<span title="'.$e->getMessage().'">'.$e->getCode().'</span>'));
  }
}


$template->assign(array(
  'GALLERY_TITLE' => $conf['gallery_title'],
  'CONTENT_ENCODING' => get_pwg_charset(),
  'U_HOME' => get_gallery_home_url(),

  'OAUTH_PATH' => OAUTH_PATH,
  'PROVIDER' => $hybridauth_conf['providers'][$provider]['name'],
  'SELF_URL' => OAUTH_PATH . 'auth.php?provider='.$provider,
  ));

$template->set_filename('index', realpath(OAUTH_PATH . 'template/auth.tpl'));
$template->pparse('index');
