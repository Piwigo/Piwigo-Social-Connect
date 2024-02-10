{html_style}
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
{/html_style}

<div id="oauth">
{if $OAUTH_USER.avatar}
  <img src="{$OAUTH_USER.avatar}" class="avatar">
{else}
  <img src="{$ROOT_URL}{$OAUTH_PATH}template/images/avatar-default.png" class="avatar">
{/if}

  {'Logged with'|translate} : <b>{$OAUTH_USER.provider}</b><br>
  {'Username'|translate} : <b>{$OAUTH_USER.username}</b><br>
  {if $OAUTH_USER.u_profile}<b>{'Profile URL'|translate}</b> : <a href="{$OAUTH_USER.u_profile}">{$OAUTH_USER.u_profile|truncate:40:' ... ':true:true}</a>{/if}
</div>
