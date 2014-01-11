<?php
defined('OAUTH_PATH') or die('Hacking attempt!');

function oauth_admin_plugin_menu_links($menu) 
{
  $menu[] = array(
    'NAME' => 'Social Connect',
    'URL' => OAUTH_ADMIN,
    );
  return $menu;
}

function oauth_user_list_columns($aColumns)
{
  $aColumns[] ='oauth_id';
  return $aColumns;
}

function oauth_user_list_render($output)
{
  global $aColumns, $hybridauth_conf;
  
  $oauth_col = array_search('oauth_id', $aColumns);
  $username_col = array_search('username', $aColumns);
  
  foreach ($output['aaData'] as &$user)
  {
    if (!empty($user[$oauth_col]))
    {
      list($provider) = explode('---', $user[$oauth_col], 2);
    }
    else
    {
      $provider = '';
    }
    
    $user[$username_col] = '<span class="oauth_16px '.strtolower($provider).'" title="'. @$hybridauth_conf['providers'][$provider]['name'] .'"></span> 
' . $user[$username_col];
    unset($user[$oauth_col]);
  }
  unset($user);
  
  return $output;
}

function oauth_user_list()
{
  global $template, $page;
  
  if ($page['page'] != 'user_list')
  {
    return;
  }

  $template->func_combine_css(array('path' => OAUTH_PATH . 'template/oauth_sprites.css'));
}
