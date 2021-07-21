{combine_script id='jquery.colorbox' load='footer' require='jquery' path='themes/default/js/plugins/jquery.colorbox.min.js'}
{combine_css id='colorbox' path="themes/default/js/plugins/colorbox/style2/colorbox.css"}

{html_style}
#popupWindow { padding:20px; }

#popupWindow span.property {
    width: 100%;
    text-align: left;
}

#popupWindow input.login {
    width: 90%;
}

#popupWindow, #popupWindow fieldset legend, #popupWindow fieldset label { color: black; }

#oauth {
  width:400px;
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

{footer_script require="jquery"}
jQuery("form[name=register_form] input[type=password], form[name=register_form] input[name=send_password_by_mail]").parent().hide();

$('#popupLink').click(function(e) {
    e.preventDefault();

    $('#popupWindow').show();

    $.colorbox({
        inline: true,
        href: '#popupWindow',
        initialWidth: 0,
        initialHeight: 0,
        onClosed: function() { $('#popupWindow').hide(); },
        onComplete: function() { $.colorbox.resize({ speed:0 }) }
    });
});
{/footer_script}

<p style="text-align:center"><a style="cursor:pointer" title="Associate with existing account" id="popupLink" class="pwg-state-default pwg-button">{'Already have an existing account? Connect your %s account to your existing account instead'|translate:$OAUTH_USER.provider}</a></p>
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

<div id="popupWindow" style="display:none">
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

<form action="{$F_ACTION}" method="post" name="login_form" class="properties">
  <fieldset>
    <legend>{'Associate with an existing account'|@translate}</legend>

    <ul>
      <li>
        <span class="property">
          <label for="username">* {'Username'|@translate}</label>
        </span>
        <input tabindex="1" class="login" type="text" name="username" id="username" size="25" maxlength="40">
      </li>

      <li>
        <span class="property">
          <label for="password">* {'Password'|@translate}</label>
        </span>
        <input tabindex="2" class="login" type="password" name="password" id="password" size="25" maxlength="25">
      </li>
    </ul>
  </fieldset>

  <p>
    <input tabindex="4" type="submit" name="login" value="{'Submit'|@translate}">
  </p>

{if isset($U_LOST_PASSWORD)}
	<p>
		<a href="{$U_LOST_PASSWORD}" title="{'Forgot your password?'|@translate}" class="pwg-state-default pwg-button">
            <span class="pwg-icon pwg-icon-lost-password">&nbsp;</span><span>{'Forgot your password?'|@translate}</span>
		</a>
	</p>
{/if}

</form>
</div>
