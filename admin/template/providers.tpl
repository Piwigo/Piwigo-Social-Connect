{combine_css path=$OAUTH_PATH|cat:'admin/template/style.css'}
{combine_css path=$OAUTH_PATH|cat:'template/oauth_sprites.css'}

{footer_script}
jQuery("select.enable").change(function() {
  var $top = $(this).closest("div.provider");
  var p = $top.data('p');
  
  if ($(this).val()=='true') {
    $top.find("td.keys").show();
    $top.removeClass('disabled');
    $top.addClass('enabled');
  }
  else {
    $top.find("td.keys").hide();
    $top.removeClass('enabled');
    $top.addClass('disabled');
  }
});

jQuery("#close_help").click(function() {
  jQuery("#help_container").animate({ "margin-right": "-550px" }, 'fast');
  return false;
});

jQuery(".open-help").click(function() {
  var $top = $(this).closest("div.provider");
  var p = $top.data('p');
  
  $("#help_container h5").html($top.find("h4").html());
  $("#help_container div").html($top.find("div.help").html());
  $("#help_container").animate({ "margin-right": "0px" }, 'fast');
  return false;
});
{/footer_script}


<div class="titrePage">
	<h2>Social Connect</h2>
</div>

<div id="help_container">
  <a href="#" id="close_help" title="{'Close'|translate}">&times;</a>
  <h5></h5>
  <div></div>
</div>

<form method="post" action="" class="properties">
<fieldset id="commentsConf">

{foreach from=$PROVIDERS item=provider key=p}
  <div data-p="{$p}" class="provider {$p} {if $CONFIG[$p].enabled}enabled{else}disabled{/if}">
    <h4>{$provider.name}</h4>
    
    <table><tr>
      <td>
        <span class="oauth_38px {$p|strtolower}"></span>
      </td>
      
      <td>
        <select name="providers[{$p}][enabled]" class="enable">
          <option value="true" {if $CONFIG[$p].enabled}selected="selected"{/if}>{'Enabled'|translate}</option>
          <option value="false" {if not $CONFIG[$p].enabled}selected="selected"{/if}>{'Disabled'|translate}</option>
        </select>
        <br><a href="#" class="open-help">{'Help'|translate}</a>
      </td>
      
      {if $provider.new_app_link}
      <td class="keys" {if not $CONFIG[$p].enabled}style="display:none;"{/if}>
        {if $provider.require_client_id}
          <label for="{$p}_app_id">Application/Client ID</label>
          <input type="text" id="{$p}_app_id" name="providers[{$p}][keys][id]" value="{$CONFIG[$p].keys.id}">
        {else}
          <label for="{$p}_key">Application Key</label>
          <input type="text" id="{$p}_key" name="providers[{$p}][keys][key]" value="{$CONFIG[$p].keys.key}">
        {/if}
          <label for="{$p}_secret">Application Secret</label>
          <input type="text" id="{$p}_secret" name="providers[{$p}][keys][secret]" value="{$CONFIG[$p].keys.secret}">
        {if $p=='Google'}
          <label for="Google_hd">Domain name (optional)</label>
          <input type="text" id="Google_hd" name="providers[Google][hd]" value="{$CONFIG.Google.hd}">
        {/if}
      </td>
      {/if}
    </tr></table>
    
    <div class="help">
    {if $provider.new_app_link}
      {assign var=callback_url value=$OAUTH_CALLBACK|cat:$p}
      
      <ol>
        <li>{'Go to <a href="%s" target="_blank">%s</a> and create a new application'|translate:$provider.new_app_link:$provider.new_app_link}</li>
        <li>{'Fill out any required fields such as the application name and description'|translate}</li>
        
      {if $p=='Facebook'}
        <li>{'Go to <b>Settings -> Advanced</b> and set <b>Valid OAuth redirect URIs</b> to <em>%s</em>'|translate:$callback_url}</li>
        <li>{'Go to <b>Settings -> Basic</b> and fill the contact email'|translate}</li>
        <li>{'Click on <b>Add Platform</b>, choose <b>Website</b> and set the <b>Site URL</b> to <em>%s</em>'|translate:$WEBSITE}</li>
        <li>{'Go to <b>Status & Review</b> and set the app public by clicking the big button on top-right'|translate}</li>
        
      {elseif $p=='Google'}
        <li>{'On the <b>Credentials</b> tab, click <b>Create credentials -> OAuth client ID</b>'|translate}</li>
        <li>{'Click on <b>Configure consent screen</b>, fill the <b>Product name</b> and save'|translate}</li>
        <li>{'Set <b>%1s</b> to <em>%2s</em>'|translate:'Application Type':'Web Application'}</li>
        <li>{'Put your website domain in the <b>%1s</b> field. It must match with the current hostname: <em>%2s</em>'|translate:'<b>Authorized Javascript origins</b>':$SERVERNAME}</li>
        <li>{'Enter <em>%1s</em> for <b>%2s</b>'|translate:$callback_url:'Authorized redirect URIs'}</li>
      
      {elseif $p=='Instagram'}
        <li>{'Put your website domain in the <b>%1s</b> field. It must match with the current hostname: <em>%2s</em>'|translate:'<b>Website</b>':$WEBSITE}</li>
        <li>{'Enter <em>%1s</em> for <b>%2s</b>'|translate:$callback_url:'OAuth redirect_uri'}</li>
        
      {elseif $p=='LinkedIn'}
        <li>{'Put your website domain in the <b>%1s</b> field. It must match with the current hostname: <em>%2s</em>'|translate:'<b>Website URL</b>':$WEBSITE}</li>
        <li>{'Set <b>%1s</b> to <em>%2s</em>'|translate:'Default Scope':'r_basicprofile & r_emailaddress'}</li>
        <li>{'Enter <em>%1s</em> for <b>%2s</b>'|translate:$callback_url:'OAuth 2.0 Redirect URLs'}</li>
      
      {elseif $p=='Tumblr'}
        <li>{'Put your website domain in the <b>%1s</b> field. It must match with the current hostname: <em>%2s</em>'|translate:'<b>Application website</b>':$WEBSITE}</li>
        <li>{'Enter <em>%1s</em> for <b>%2s</b>'|translate:$callback_url:'Default callback URL'}</li>
      
      {elseif $p=='Twitter'}
        <li>{'Put your website domain in the <b>%1s</b> field. It must match with the current hostname: <em>%2s</em>'|translate:'<b>Website</b>':$WEBSITE}</li>
        <li>{'Enter <em>%1s</em> for <b>%2s</b>'|translate:$callback_url:'Callback URL'}</li>
      
      {elseif $p=='Live'}
        <li>{'Go to <b>API Parameters</b> and set <em>%s</em> for <b>Redirect URL</b>'|translate:$WEBSITE}
        
      {elseif $p=='Vkontakte'}
        <li>{'Set <b>%1s</b> to <em>%2s</em>'|translate:'Category':'Website'}</li>
        <li>{'Enter <em>%1s</em> for <b>%2s</b>'|translate:$WEBSITE:'Site address'}</li>
        <li>{'Put your website domain in the <b>%1s</b> field. It must match with the current hostname: <em>%2s</em>'|translate:'<b>Base domain</b>':$SERVERNAME}</li>
        <li>{'Go to the <b>Settings</b> tab after creating the app'|translate}</li>
      
      {elseif $p=='Yahoo'}
        <li>{'Set <b>%1s</b> to <em>%2s</em>'|translate:'Kind of Application':'Web-based'}</li>
        <li>{'Put your website domain in the <b>%1s</b> field. It must match with the current hostname: <em>%2s</em>'|translate:'<b>Home Page URL</b>':$WEBSITE}</li>
        <li>{'Set <b>%1s</b> to <em>%2s</em>'|translate:'Access Scopes':'This app requires access to private user data'}</li>
        <li>{'Enter <em>%1s</em> for <b>%2s</b>'|translate:$callback_url:'Callback Domain'}</li>
        <li>{'Select these APIs: <b>Contacts</b> as <em>Read</em> and <b>Social Directory</b> as <em>Read Public</em>'|translate}</li>
      
      {elseif $p=='px500'}
        <li>{'Put your website domain in the <b>%1s</b> field. It must match with the current hostname: <em>%2s</em>'|translate:'<b>Application URL</b>':$WEBSITE}</li>
        <li>{'Enter <em>%1s</em> for <b>%2s</b>'|translate:$callback_url:'Callback URL'}</li>
        <li>{'Once the application is created, click <b>See application details</b>'|translate}</li>
      
      {/if}
      
        <li>{'Once you have registered, copy and past the created application credentials into this setup page'|translate}</li>
        
      {if $p=='Google'}
        <li>{'Go to the <b>Library</b> tab, open <b>Social APIs -> Google+ API</b> and click <b>ENABLE</b>'|translate}</li>
      {/if}
      </ol>
    {else}
      <p style="text-align:left;">
      {if $p=='Flickr' or $p=='Steam' or $p=='Wordpress'}
        {'Based on OpenID'|translate}</br>
      {/if}
      {'No registration required'|translate}<br>
      <a href="{$provider.about_link}" target="_blank">{'About'|translate}</a>
      </p>
    {/if}
    </div>
  </div>
{/foreach}

</fieldset>

<p style="text-align:left;"><input type="submit" name="save_config" value="{'Save Settings'|translate}"></p>
  
</form>

<div style="text-align:right;">
  Icons from : <a href="http://www.wpzoom.com" target="_blank">WPZOOM</a> |
  Library : <a href="http://hybridauth.sourceforge.net" target="_blank">HybridAuth</a>
</div>