<?php
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | https://github.com/hybridauth/hybridauth
*  (c) 2009-2012 HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
*/

/**
* Hybrid_Providers_Nextcloud (By rachung)
*/
class Hybrid_Providers_Nextcloud extends Hybrid_Provider_Model_OAuth2
{
	/**
	* IDp wrappers initializer 
	*/
	function initialize()
	{
		parent::initialize();

		// Provider api end-points
        if (!ctype_alpha(substr($this->config['hd'], -1))) // remove slash at end if user included it in
            $this->config['hd'] = substr($this->config['hd'], 0, -1);

		$this->api->api_base_url  = $this->config['hd'];
		$this->api->authorize_url = $this->api->api_base_url.(($this->config['hd_opt'])?'':'/index.php').'/apps/oauth2/authorize'; // insert 'index.php' if not pretty URL
		$this->api->token_url = $this->api->api_base_url.(($this->config['hd_opt'])?'':'/index.php').'/apps/oauth2/api/v1/token'; // insert 'index.php' if not pretty URL

        $this->api->curl_header = array("Authorization: Bearer ".$this->api->access_token, "OCS-APIRequest: true");
	}

	/**
	* load the user profile from the IDp api client
	*/
	function getUserProfile(){
        $this->refreshToken();
        $data = $this->api->api($this->api->api_base_url."/ocs/v2.php/cloud/user?format=json");

        // Not getting data
        if (!property_exists($data,'ocs')) {
            echo "Data retrieval failed. Try a curl to see if header \"Authorization: Bearer\" is being stripped.<br>";
            echo "If so, and using Apache, check permissions in /etc/apache2/apache2.conf<br>";

            $this->user->profile->identifier  = '';
            $this->user->profile->displayName = '';
            $this->user->profile->email = '';
            $this->user->profile->photoURL    = '';
            return $this->user->profile;
        }

        $data = $data->ocs;

		if ( $data->meta->statuscode != 200 ){
			throw new Exception( "User profile request failed! {$this->providerId} returned an invalid response.", 6 );
		}

		$this->user->profile->identifier  = $data->data->id;
		$this->user->profile->displayName = $data->data->{'display-name'};
		$this->user->profile->email       = $data->data->email;

        // Nextcloud has no user data on avatar image URL, so uses Nextcloud's library of avatar images based on initial
        $ch = strtolower($data->data->{'display-name'}[0]); // initial
		$this->user->profile->photoURL    = OAUTH_PATH.'template/images/nextcloud_avatars/'.$ch.'.png';

		return $this->user->profile;
	}
}

