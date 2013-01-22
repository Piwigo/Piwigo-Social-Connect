<?php
defined('OAUTH_PATH') or die('Hacking attempt!');

$PROVIDERS_CONFIG = include(OAUTH_PATH . 'include/providers_stats.inc.php');

if (isset($_POST['save_config']))
{
  array_walk_recursive($_POST, 'trim');
  
  $providers = array();
  foreach ($_POST['providers'] as $id => $data)
  {
    $error = false;
    $data['enabled'] = $data['enabled']=='true';
    
    if ($PROVIDERS_CONFIG[$id]['new_app_link'] and $data['enabled'])
    {
      if (empty($data['keys']['secret']) or
        (@$PROVIDERS_CONFIG[$id]['require_client_id'] and empty($data['keys']['id'])) or
        (!@$PROVIDERS_CONFIG[$id]['require_client_id'] and empty($data['keys']['key']))
      ) {
        array_push($page['errors'], sprintf(l10n('%s: invalid keys'), $PROVIDERS_CONFIG[$id]['provider_name']));
        $error = true;
      }
    }
    else
    {
      unset($data['keys']);
    }
    
    if ( ($id=='Wordpress' or $id=='Flickr') and $data['enabled'] and !@$providers['OpenID']['enabled'] )
    {
      array_push($page['errors'], sprintf(l10n('OpenID must be enabled in order to use %s authentication'), $id));
      $error = true;
    }
    
    if (isset($PROVIDERS_CONFIG[$id]['scope']))
    {
      $data['scope'] = $PROVIDERS_CONFIG[$id]['scope'];
    }
    
    if (!$error)
    {
      $providers[$id] = $data;
    }
  }
  
  if (!count($page['errors']))
  {
    // generate config file
    $hybridauth_conf['providers'] = $providers;
    $content = "<?php\ndefined('PHPWG_ROOT_PATH') or die('Hacking attempt!');\n\nreturn ";
    $content.= var_export(array('providers'=>$providers), true);
    $content.= ";\n?>";
    file_put_contents(OAUTH_CONFIG, $content);
  }
}


$template->assign(array(
  'PROVIDERS' => $PROVIDERS_CONFIG,
  'CONFIG' => $hybridauth_conf['providers'],
  'SERVERNAME' => get_absolute_root_url(),
  'OAUTH_CALLBACK' => OAUTH_PUBLIC . '?hauth.done=',
  ));

// define template file
$template->set_filename('oauth_content', realpath(OAUTH_PATH . 'admin/template/providers.tpl'));

?>