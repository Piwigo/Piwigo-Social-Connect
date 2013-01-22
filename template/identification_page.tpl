{if not $OAUTH_JS_LOADED}
  {assign var=OAUTH_JS_LOADED value=true}
  {include file=$OAUTH_ABS_PATH|@cat:'template/identification_common.tpl'}
{/if}
{html_head}{literal}
<style type="text/css">
  #oauth_wrap .oauth {
    margin:0 2px;
  }
</style>
{/literal}{/html_head}
  
<fieldset style="text-align:center;" id="oauth_wrap">
  <legend>{'Or sign in with'|@translate}</legend>
  
{foreach from=$PROVIDERS item=provider key=p}{strip}
  <a href="#" class="oauth" title="{$p}"><img src="{$ROOT_URL}{$OAUTH_PATH}template/icons/{$oauth.identification_icon}/{$p|strtolower}.png"></a>
{/strip}{/foreach}
</fieldset>