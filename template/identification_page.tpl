{if count($PROVIDERS)}
  {if not $OAUTH_JS_LOADED}
    {assign var=OAUTH_JS_LOADED value=true}
    {include file=$OAUTH_ABS_PATH|cat:'template/identification_common.tpl'}
  {/if}
  {html_style}
    #oauth_wrap .oauth { margin:0 2px; }
  {/html_style}
    
  <fieldset style="text-align:center;" id="oauth_wrap">
    <legend>{'Or sign in with'|translate}</legend>
    
  {foreach from=$PROVIDERS item=provider key=p}{strip}
    <a href="#" class="oauth oauth_{$oauth.identification_icon} {$p|strtolower}" data-idp="{$p}" title="{$provider.name}"></a>
  {/strip}{/foreach}
  </fieldset>
{/if}