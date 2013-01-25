{if $id == "mbIdentification" and isset($U_LOGIN)}
  {if not $OAUTH_JS_LOADED}
    {assign var=OAUTH_JS_LOADED value=true}
    {include file=$OAUTH_ABS_PATH|@cat:'template/identification_common.tpl'}
  {/if}
  {html_style}{literal}
    dl#mbIdentification dd:first-of-type {
      padding-bottom:0 !important;
    }
    #mbIdentification .oauth {
      margin:0 1px;
    }
  {/literal}{/html_style}
  
  <dd>
    <form id="quickconnect">
    <fieldset style="text-align:center;">
      <legend>{'Or sign in with'|@translate}</legend>
      
    {foreach from=$PROVIDERS item=provider key=p}{strip}
      <a href="#" class="oauth" title="{$p}"><img src="{$ROOT_URL}{$OAUTH_PATH}template/icons/{$oauth.menubar_icon}/{$p|strtolower}.png"></a>
    {/strip}{/foreach}
    </fieldset>
    </form>
  </dd>
{/if}