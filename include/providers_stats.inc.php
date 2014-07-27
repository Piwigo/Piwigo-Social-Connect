<?php

return array(
  'Facebook' => array(
    'name'              => 'Facebook',
    'require_client_id' => true,
    'new_app_link'      => 'https://developers.facebook.com/apps',
    'scope'             => 'email',
  ),
  'Google' => array(
    'name'              => 'Google',
    'require_client_id' => true,
    'new_app_link'      => 'https://cloud.google.com/console/project',
    'scope'             => 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email',
  ),
  'Instagram' => array(
    'name'              => 'Instagram',
    'require_client_id' => true,
    'new_app_link'      => 'http://instagram.com/developer/clients/manage',
  ),
  'LinkedIn' => array( 
    'name'              => 'LinkedIn',
    'new_app_link'      => 'https://www.linkedin.com/secure/developer',
  ),
  'Tumblr' => array(
    'name'              => 'Tumblr',
    'new_app_link'      => 'http://www.tumblr.com/oauth/apps',
  ),
  'Twitter' => array(
    'name'              => 'Twitter',
    'new_app_link'      => 'https://dev.twitter.com/apps',
  ),
  'Live' => array(
    'name'              => 'Windows Live',
    'require_client_id' => true,
    'new_app_link'      => 'https://account.live.com/developers/applications/index',
  ),
  'Vkontakte' => array(
    'name'              => 'Vkontakte',
    'require_client_id' => true,
    'new_app_link'      => 'http://vk.com/dev',
  ),
  'Yahoo' => array(
    'name'              => 'Yahoo!',
    'new_app_link'      => 'https://developer.apps.yahoo.com/projects/',
  ),
  'px500' => array(
    'name'              => '500px',
    'new_app_link'      => 'http://500px.com/settings/applications',
  ),
  'OpenID' => array(
    'name'              => 'OpenID',
    'new_app_link'      => null,
    'about_link'        => 'http://openid.net/get-an-openid/what-is-openid/',
  ),
  'Flickr' => array(
    'name'              => 'Flickr',
    'new_app_link'      => null,
    'about_link'        => 'http://openid.net/get-an-openid/what-is-openid/',
  ),
  'Steam' => array(
    'name'              => 'Steam',
    'new_app_link'      => null,
    'about_link'        => 'http://openid.net/get-an-openid/what-is-openid/',
  ),
  'Wordpress' => array(
    'name'              => 'Wordpress',
    'new_app_link'      => null,
    'about_link'        => 'http://openid.net/get-an-openid/what-is-openid/',
  ),
  'Persona' => array(
    'name'              => 'Persona',
    'new_app_link'      => null,
    'about_link'        => 'https://login.persona.org/about',
    ),
);