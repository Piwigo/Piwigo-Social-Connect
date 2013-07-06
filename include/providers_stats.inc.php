<?php

return array(
  'Facebook' => array( 
    'label'             => 'Facebook',
    'provider_name'     => 'Facebook',
    'require_client_id' => true,
    'new_app_link'      => 'https://developers.facebook.com/apps',
    'scope'             => 'email',
  ),
  
  'Google' => array( 
    'label'             => 'Google',
    'provider_name'     => 'Google',
    'callback'          => true,
    'require_client_id' => true,
    'new_app_link'      => 'https://code.google.com/apis/console',
    'scope'             => 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email',
  ),
  'Instagram' => array(
    'label'             => 'Instagram',
    'provider_name'     => 'Instagram',
    'callback'          => true,
    'require_client_id' => true,
    'new_app_link'      => 'http://instagram.com/developer/clients/manage',
  ),
  'LinkedIn' => array( 
    'label'             => 'LinkedIn',
    'provider_name'     => 'LinkedIn',
    'new_app_link'      => 'https://www.linkedin.com/secure/developer',
  ),
  'Tumblr' => array(
    'label'             => 'Tumblr',
    'provider_name'     => 'Tumblr',
    'new_app_link'      => 'http://www.tumblr.com/oauth/apps',
  ),
  'Twitter' => array( 
    'label'             => 'Twitter',
    'provider_name'     => 'Twitter',
    'new_app_link'      => 'https://dev.twitter.com/apps',
  ),
  'Live' => array( 
    'label'             => 'Live',
    'provider_name'     => 'Windows Live',
    'require_client_id' => true,
    'new_app_link'      => 'https://manage.dev.live.com/ApplicationOverview.aspx',
  ),
  'Yahoo' => array( 
    'label'             => 'Yahoo',
    'provider_name'     => 'Yahoo!',
    'new_app_link'      => 'https://developer.apps.yahoo.com/projects/',
  ),
  'OpenID' => array( 
    'label'             => 'OpenID',
    'provider_name'     => 'OpenID',
    'new_app_link'      => null,
  ),
  'Flickr' => array( 
    'label'             => 'Flickr',
    'provider_name'     => 'Flickr',
    'new_app_link'      => null,
  ),
  'Steam' => array( 
    'label'             => 'Steam',
    'provider_name'     => 'Steam',
    'new_app_link'      => null,
  ),
  'Wordpress' => array( 
    'label'             => 'Wordpress',
    'provider_name'     => 'Wordpress',
    'new_app_link'      => null,
  ),
);