{combine_css path=$OAUTH_PATH|@cat:"admin/template/style.css"}

{footer_script}{literal}
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
  jQuery("#help_container").animate({"margin-right": "-550px"}, 'fast');
  return false;
});

jQuery(".open-help").click(function() {
  var $top = $(this).closest("div.provider");
  var p = $top.data('p');
  
  $("#help_container h5").html($top.find("h4").html());
  $("#help_container div").html($top.find("div.help").html());
  $("#help_container").animate({"margin-right": "0px"}, 'fast');
  return false;
});
{/literal}{/footer_script}


<div class="titrePage">
	<h2>OAuth</h2>
</div>

<div id="help_container">
  <a href="#" id="close_help" title="{'Close'|@translate}">&times;</a>
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
        <img src="{$OAUTH_PATH}template/icons/38px/{$p|strtolower}.png">
      </td>
      
      <td>
        <select name="providers[{$p}][enabled]" class="enable">
          <option value="true" {if $CONFIG[$p].enabled}selected="selected"{/if}>{'Enabled'|@translate}</option>
          <option value="false" {if not $CONFIG[$p].enabled}selected="selected"{/if}>{'Disabled'|@translate}</option>
        </select>
        <br><a href="#" class="open-help">{'Help'|@translate}</a>
      </td>
      
      {if $provider.new_app_link}
      <td class="keys" {if not $CONFIG[$p].enabled}style="display:none;"{/if}>
        {if $provider.require_client_id}
          <label for="{$p}_app_id">Application ID</label>
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
        <li>{'Go to <a href="%s" target="_blank">%s</a> and <b>create a new application</b>'|@translate|sprintf:$provider.new_app_link:$provider.new_app_link}</li>
        
      {if $p=='Google'}
        <li>{'On the <b>API Access</b> tab, <b>create an OAuth 2.0 Client ID</b>'|@translate}</li>
        <li>{'Fill out any required fields such as the application name and description'|@translate}</li>
        <li>{'On the <b>Create Client ID</b> popup switch to advanced settings by clicking on <b>(more options)</b>'|@translate}</li>
      {else}
        <li>{'Fill out any required fields such as the application name and description'|@translate}</li>
      {/if}
        
      {if $provider.callback}
        <li>
          {assign var=callback value=$OAUTH_CALLBACK|cat:$p}
          {'Provide this URL as the Callback/Redirect URL for your application: <em>%s</em>'|@translate|sprintf:$callback}
        </li>
      {/if}

      {if $p=='Live'}
        <li>{'Put your website domain in the %s fields. It should match with the current hostname: <em>%s</em>'|@translate|sprintf:'<b>Redirect Domain</b>':$SERVERNAME}</li>
      {elseif $p=='Facebook'}
        <li>{'Select <em>Website with facebook authentication</em> as application type'|@translate}</li>
        <li>{'Put your website domain in the %s fields. It should match with the current hostname: <em>%s</em>'|@translate|sprintf:'<b>Site Url</b>, <b>App Domains</b>':$SERVERNAME}</li>
      {elseif $p=='LinkedIn'}
        <li>{'Put your website domain in the %s fields. It should match with the current hostname: <em>%s</em>'|@translate|sprintf:'<b>Website URL</b>':$SERVERNAME}</li>
        <li>{'Set the <b>Application Type</b> to <em>Web Application</em>'|@translate}</li> 
      {elseif $p=='Yahoo'}
        <li>{'Put your website domain in the %s fields. It should match with the current hostname: <em>%s</em>'|@translate|sprintf:'<b>Application URL</b>, <b>Application Domain</b>':$SERVERNAME}</li>
        <li>{'Set the <b>Kind of Application</b> to <em>Web-based</em>'|@translate}</li> 
        <li>{'Set the <b>Access Scopes</b> to <em>This app will only access public...</em>'|@translate}</li> 
      {elseif $p=='Twitter'}
        <li>{'Put your website domain in the %s fields. It should match with the current hostname: <em>%s</em>'|@translate|sprintf:'<b>Application Website</b>, <b>Application Callback URL</b>':$SERVERNAME}</li>
        <li>{'Set the <b>Default Access Type</b> to <em>Read only</em>'|@translate}</li> 
      {elseif $p=='Tumblr'}
        <li>{'Put your website domain in the %s fields. It should match with the current hostname: <em>%s</em>'|@translate|sprintf:'<b>Application Website</b>, <b>Default Callback URL</b>':$SERVERNAME}</li>
      {/if}
      
        <li>{'Once you have registered, copy and past the created application credentials into this setup page'|@translate}</li>  
      </ol>
    {else}
      <p>{'No registration required for OpenID based providers'|@translate}</p> 
    {/if}
    </div>
  </div>
{/foreach}

</fieldset>

<p style="text-align:left;"><input type="submit" name="save_config" value="{'Save Settings'|@translate}"></p>
  
</form>

<p>Icons from http://www.wpzoom.com - Library from http://hybridauth.sourceforge.net/</p>