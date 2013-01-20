{if $id == "mbIdentification" and isset($U_LOGIN)}
  {if not $OAUTH_JS_LOADED}
    {assign var=OAUTH_JS_LOADED value=true}
    {include file=$OAUTH_ABS_PATH|@cat:'template/identification_common.tpl'}
  {/if}

  {html_head}{literal}
  <style type="text/css">
    #menubar dl#mbIdentification dd:first-of-type {
      padding-bottom:0 !important;
    }
  </style>
  {/literal}{/html_head}
  <dd>
    <form id="quickconnect">
    <fieldset style="text-align:center;">
      <legend>{'Or sign in with'|@translate}</legend>
      
    {foreach from=$PROVIDERS item=provider key=p}
      <a href="#" class="oauth_{$p}" title="{$p}"><img src="{$ROOT_URL}{$OAUTH_PATH}template/icons/{$p|strtolower}_small.png"></a>
    {/foreach}
    </fieldset>
    </form>
  </dd>
{/if}