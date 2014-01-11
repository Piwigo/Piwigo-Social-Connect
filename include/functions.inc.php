<?php
defined('OAUTH_PATH') or die('Hacking attempt!');

function load_hybridauth_conf()
{
  global $hybridauth_conf, $conf;
  
  if (file_exists(PHPWG_ROOT_PATH.OAUTH_CONFIG))
  {
    $hybridauth_conf = include(PHPWG_ROOT_PATH.OAUTH_CONFIG);
    $hybridauth_conf['base_url'] = OAUTH_PUBLIC;
    if (!empty($conf['oauth_debug_file']))
    {
      $hybridauth_conf['debug_mode'] = true;
      $hybridauth_conf['debug_file'] = $conf['oauth_debug_file'];
    }
    return true;
  }
  else
  {
    return false;
  }
}

function oauth_assign_template_vars($u_redirect=null)
{
  global $template, $conf, $hybridauth_conf, $user;
  
  $conf['oauth']['include_common_template'] = true;
  
  if ($template->get_template_vars('OAUTH') == null)
  {
    if (!empty($user['oauth_id']))
    {
      list($provider, $identifier) = explode('---', $user['oauth_id'], 2);
      if ($provider == 'Persona')
      {
        $persona_email = $identifier;
      }
    }
    
    $template->assign('OAUTH', array(
      'conf' => $conf['oauth'],
      'u_login' => get_root_url() . OAUTH_PATH . 'auth.php?provider=',
      'providers' => $hybridauth_conf['providers'],
      'persona_email' => @$persona_email,
      ));
    $template->assign(array(
      'OAUTH_PATH' => OAUTH_PATH,
      'OAUTH_ABS_PATH' => realpath(OAUTH_PATH) . '/',
      'ABS_ROOT_URL' => rtrim(get_gallery_home_url(), '/') . '/',
      ));
  }
  
  if (isset($u_redirect))
  {
    $template->append('OAUTH', compact('u_redirect'), true);
  }
}

function get_oauth_id($user_id)
{
  global $conf;
  
  $query = '
SELECT oauth_id FROM ' . USERS_TABLE . '
  WHERE ' . $conf['user_fields']['id'] . ' = ' . $user_id . '
  AND oauth_id != ""
;';
  $result = pwg_query($query);
  
  if (!pwg_db_num_rows($result))
  {
    return null;
  }
  else
  {
    list($oauth_id) = pwg_db_fetch_row($result);
    return $oauth_id;
  }
}

// http://www.sitepoint.com/authenticate-users-with-mozilla-persona/
function persona_verify()
{
  $url = 'https://verifier.login.persona.org/verify';

  $assert = filter_input(
    INPUT_POST,
    'assertion',
    FILTER_UNSAFE_RAW,
    FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH
    );

  $scheme = 'http';
  if ( (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 )
  {
    $scheme = 'https';
  }
  $audience = sprintf(
    '%s://%s:%s',
    $scheme,
    $_SERVER['HTTP_HOST'],
    $_SERVER['SERVER_PORT']
    );

  $params = 'assertion=' . urlencode($assert) . '&audience=' . urlencode($audience);

  $options = array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $params,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_SSL_VERIFYHOST => 2,
    );

  $ch = curl_init();
  curl_setopt_array($ch, $options);
  $result = curl_exec($ch);
  curl_close($ch);
  
  if ($result === false)
  {
    return false;
  }
  else
  {
    return json_decode($result, true);
  }
}