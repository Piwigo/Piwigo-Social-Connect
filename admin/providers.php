<?php
defined('OAUTH_PATH') or die('Hacking attempt!');

load_language('help.lang', OAUTH_PATH);

$PROVIDERS_CONFIG = include(OAUTH_PATH . 'include/providers_stats.inc.php');

if (isset($_POST['save_config']))
{
  $providers = array(); $count_enabled = 0;
  foreach ($_POST['providers'] as $id => $data)
  {
    $data['enabled'] = $data['enabled']=='true';
    if ($data['enabled']) $count_enabled++;
    
    if ($PROVIDERS_CONFIG[$id]['new_app_link'] and $data['enabled'])
    {
      if (empty($data['keys']['secret']) or
        (@$PROVIDERS_CONFIG[$id]['require_client_id'] and empty($data['keys']['id'])) or
        (!@$PROVIDERS_CONFIG[$id]['require_client_id'] and empty($data['keys']['key']))
      ) {
        $page['errors'][] = l10n('%s: invalid keys', $PROVIDERS_CONFIG[$id]['name']);
      }
    }
    
    if (isset($PROVIDERS_CONFIG[$id]['scope']))
    {
      $data['scope'] = $PROVIDERS_CONFIG[$id]['scope'];
    }
    
    if (is_array(@$data['keys']))
    {
      $data['keys'] = array_map('trim', $data['keys']);
    }
    
    $data['name'] = $PROVIDERS_CONFIG[$id]['name'];
    $providers[$id] = $data;
  }
  
  $hybridauth_conf['providers'] = $providers;
  $hybridauth_conf['total'] = count($hybridauth_conf['providers']);
  $hybridauth_conf['enabled'] = $count_enabled;
  
  if (!count($page['errors']))
  {
    // generate config file
    $content = "<?php\ndefined('PHPWG_ROOT_PATH') or die('Hacking attempt!');\n\nreturn ";
    $content.= var_export(array_intersect_key($hybridauth_conf, array_flip(array('providers','total','enabled'))), true);
    $content.= ";\n?>";
    
    file_put_contents(OAUTH_CONFIG, $content);
    $page['infos'][] = l10n('Information data registered in database');
  }
}


$template->assign(array(
  'PROVIDERS' => $PROVIDERS_CONFIG,
  'CONFIG' => $hybridauth_conf['providers'],
  'SERVERNAME' => get_servername(),
  'WEBSITE' => get_absolute_root_url(),
  'OAUTH_CALLBACK' => OAUTH_PUBLIC . '?hauth.done=',
  ));

// define template file
$template->set_filename('oauth_content', realpath(OAUTH_PATH . 'admin/template/providers.tpl'));
