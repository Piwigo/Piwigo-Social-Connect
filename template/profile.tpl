{html_head}{literal}
<style type="text/css">
  #oauth {
    width:400px;
    height:48px;
    overflow:hidden;
    margin:0 auto 15px auto;
    padding:5px;
    background:rgba(128,128,128,0.2);
    border:1px solid #7e7e7e;
    border-radius:5px;
  }
  #oauth .avatar {
    width:48px;
    border-radius:5px;
    margin-right:5px;
    float:left;
  }
</style>
{/literal}{/html_head}

<div id="oauth">
{if $OAUTH_AVATAR}
  <img src="{$OAUTH_AVATAR}" class="avatar">
{else}
  <img src="{$ROOT_URL}{$OAUTH_PATH}template/images/avatar-default.png" class="avatar">
{/if}

  {'Logged with'|@translate} : <b>{$OAUTH_PROVIDER}</b><br>
  <b>{'Username'|@translate}</b> : {$OAUTH_USERNAME}<br>
  {if $OAUTH_PROFILE_URL}<b>{'Profile URL'|@translate}</b> : <a href="{$OAUTH_PROFILE_URL}">{$OAUTH_PROFILE_URL|truncate:40:' ... ':true:true}</a>{/if}
</div>