<?php
defined('OAUTH_PATH') or die('Hacking attempt!');

load_language('help.lang', OAUTH_PATH);

$PROVIDERS_CONFIG = include(OAUTH_PATH . 'include/providers_stats.inc.php');

if (isset($_POST['save_config']))
{
  $providers = array();
  foreach ($_POST['providers'] as $id => $data)
  {
    $data['enabled'] = $data['enabled']=='true';
    
    if ($PROVIDERS_CONFIG[$id]['new_app_link'] and $data['enabled'])
    {
      if (empty($data['keys']['secret']) or
        (@$PROVIDERS_CONFIG[$id]['require_client_id'] and empty($data['keys']['id'])) or
        (!@$PROVIDERS_CONFIG[$id]['require_client_id'] and empty($data['keys']['key']))
      ) {
        array_push($page['errors'], sprintf(l10n('%s: invalid keys'), $PROVIDERS_CONFIG[$id]['provider_name']));
      }
    }
    
    if ( ($id=='Wordpress' or $id=='Flickr' or $id=='Steam') and $data['enabled'] and !@$providers['OpenID']['enabled'] ) // in the template, OpenID must be before other OpenID based providers
    {
      array_push($page['errors'], sprintf(l10n('OpenID must be enabled in order to use %s authentication'), $id));
    }
    
    if (isset($PROVIDERS_CONFIG[$id]['scope']))
    {
      $data['scope'] = $PROVIDERS_CONFIG[$id]['scope'];
    }
    
    if (is_array(@$data['keys']))
    {
      $data['keys'] = array_map('trim', $data['keys']);
    }
    
    $data['name'] = $PROVIDERS_CONFIG[$id]['provider_name'];
    $providers[$id] = $data;
  }
  
  $hybridauth_conf['providers'] = $providers;
  
  if (!count($page['errors']))
  {
    // generate config file
    $content = "<?php\ndefined('PHPWG_ROOT_PATH') or die('Hacking attempt!');\n\nreturn ";
    $content.= var_export(array('providers'=>$providers), true);
    $content.= ";\n?>";
    
    file_put_contents(OAUTH_CONFIG, $content);
    array_push($page['infos'], l10n('Information data registered in database'));
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