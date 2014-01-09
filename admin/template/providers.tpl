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
    <h4>{$provider.provider_name}</h4>
    
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
          <br>
      </td>
      {/if}
    </tr></table>
    
    <div class="help">
    {if $provider.new_app_link}
      <ol>
        <li>{'Go to <a href="%s" target="_blank">%s</a> and create a new application'|translate|sprintf:$provider.new_app_link:$provider.new_app_link}</li>
        
      {if $p=='Google'}
        <li>{'On the <b>APIs & auth -> Credentials</b> tab, <b>Create new client ID</b>'|translate}</li>
      {else}
        <li>{'Fill out any required fields such as the application name and description'|translate}</li>
      {/if}
        
      {if $provider.callback}
        <li>
          {assign var=callback value=$OAUTH_CALLBACK|cat:$p}
          {'Provide this URL as the Callback/Redirect URL for your application: <em>%s</em>'|translate|sprintf:$callback}
        </li>
      {/if}
      
      {if $p=='Live'}
        <li>{'Put your website domain in the %s fields. It should match with the current hostname: <em>%s</em>'|translate|sprintf:'<b>Redirect Domain</b>':$SERVERNAME}</li>
      {elseif $p=='Facebook'}
        <li>{'Go to <b>Settings->Advanced</b> and activate <em>Client OAuth Login</em>.'|translate}</li>
        <li>{'Put your website domain in the %s fields. It should match with the current hostname: <em>%s</em>'|translate|sprintf:'<b>Valid OAuth redirect URIs</b>':$SERVERNAME}</li>
      {elseif $p=='LinkedIn'}
        <li>{'Put your website domain in the %s fields. It should match with the current hostname: <em>%s</em>'|translate|sprintf:'<b>Website URL</b>':$SERVERNAME}</li>
        <li>{'Set <b>%s</b> to <em>%s</em>'|translate|sprintf:'Application Type':'Web Application'}</li>
        <li>{'Set <b>%s</b> to <em>%s</em>'|translate|sprintf:'Default Scope':'r_basicprofile & r_emailaddress'}</li>
      {elseif $p=='Yahoo'}
        <li>{'Put your website domain in the %s fields. It should match with the current hostname: <em>%s</em>'|translate|sprintf:'<b>Application URL</b>, <b>Application Domain</b>':$SERVERNAME}</li>
        <li>{'Set <b>%s</b> to <em>%s</em>'|translate|sprintf:'Kind of Application':'Web-based'}</li>
        <li>{'Set <b>%s</b> to <em>%s</em>'|translate|sprintf:'Access Scopes':'This app will only access public...'}</li>
        <li>{'Once the application is registered update the permissions : set <b>Contacts</b> as <em>Read</em> and <b>Social Directory</b> as <em>Read Public</em>'|translate}</li>
      {elseif $p=='Twitter'}
        <li>{'Put your website domain in the %s fields. It should match with the current hostname: <em>%s</em>'|translate|sprintf:'<b>Website</b>, <b>Callback URL</b>':$SERVERNAME}</li>
      {elseif $p=='Tumblr'}
        <li>{'Put your website domain in the %s fields. It should match with the current hostname: <em>%s</em>'|translate|sprintf:'<b>Application Website</b>, <b>Default Callback URL</b>':$SERVERNAME}</li>
      {elseif $p=='Instagram'}
        <li>{'Put your website domain in the %s fields. It should match with the current hostname: <em>%s</em>'|translate|sprintf:'<b>Website</b>':$SERVERNAME}</li>
      {elseif $p=='Google'}
        <li>{'Set <b>%s</b> to <em>%s</em>'|translate|sprintf:'Application Type':'Web Application'}</li>
        <li>{'Put your website domain in the %s fields. It should match with the current hostname: <em>%s</em>'|translate|sprintf:'<b>Authorized Javascript origins </b>':$SERVERNAME}</li>
      {/if}
      
        <li>{'Once you have registered, copy and past the created application credentials into this setup page'|translate}</li>
      </ol>
    {else}
      <p>{'No registration required for OpenID based providers'|translate}</p> 
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