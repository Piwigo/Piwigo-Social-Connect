{html_style}
#oauth_wrap .oauth { margin:0 2px; }
{/html_style}
  
<fieldset style="text-align:center;" id="oauth_wrap">
  <legend>{'Or sign in with'|translate}</legend>
  
{foreach from=$OAUTH.providers item=provider key=p}{strip}
  {if $provider.enabled}
    <a href="#" class="oauth oauth_{$OAUTH.conf.identification_icon} {$p|strtolower}" data-idp="{$p}" title="{$provider.name}"></a>
  {/if}
{/strip}{/foreach}
</fieldset>