{if not $OAUTH_JS_LOADED}
  {assign var=OAUTH_JS_LOADED value=true}
  {include file=$OAUTH_ABS_PATH|@cat:'template/identification_common.tpl'}
{/if}
  
<fieldset style="text-align:center;">
  <legend>{'Or sign in with'|@translate}</legend>
  
{foreach from=$PROVIDERS item=provider key=p}
  <a href="#" class="oauth_{$p}" title="{$p}"><img src="{$ROOT_URL}{$OAUTH_PATH}template/icons/{$p|strtolower}_big.png"></a>
{/foreach}
</fieldset>