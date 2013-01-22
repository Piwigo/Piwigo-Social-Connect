{combine_css path=$OAUTH_PATH|@cat:"admin/template/style.css"}

<div class="titrePage">
	<h2>OAuth</h2>
</div>

<form method="post" action="" class="properties">
<fieldset id="commentsConf">
  <ul>
    <li>
      <label>
        <input type="checkbox" name="display_menubar" {if $display_menubar}checked="checked"{/if}>
        <b>{'Display sign in buttons in the menubar'|@translate}</b>
      </label>
    </li>

    <li>
      <label>
        <input type="checkbox" name="display_register" {if $display_register}checked="checked"{/if}>
        <b>{'Display sign in buttons on the register page'|@translate}</b>
      </label>
    </li>
  </ul>
</fieldset>

<fieldset id="commentsConf">
  <ul>
    <li>
      <b>{'Icon size on the identification page'|@translate} :</b><br>
      <label>
        <input type="radio" name="identification_icon" value="16px" {if $identification_icon=='16px'}checked="checked"{/if}>
        16px
        <img src="{$OAUTH_PATH}template/icons/16px/facebook.png">
        <img src="{$OAUTH_PATH}template/icons/16px/google.png">
        <img src="{$OAUTH_PATH}template/icons/16px/twitter.png">
      </label>
      <label>
        <input type="radio" name="identification_icon" value="26px" {if $identification_icon=='26px'}checked="checked"{/if}>
        26px
        <img src="{$OAUTH_PATH}template/icons/26px/facebook.png">
        <img src="{$OAUTH_PATH}template/icons/26px/google.png">
        <img src="{$OAUTH_PATH}template/icons/26px/twitter.png">
      </label>
      <label>
        <input type="radio" name="identification_icon" value="38px" {if $identification_icon=='38px'}checked="checked"{/if}>
        38px
        <img src="{$OAUTH_PATH}template/icons/38px/facebook.png">
        <img src="{$OAUTH_PATH}template/icons/38px/google.png">
        <img src="{$OAUTH_PATH}template/icons/38px/twitter.png">
      </label>
    </li>
    
    <li>
      <b>{'Icon size in the menubar'|@translate} :</b><br>
      <label>
        <input type="radio" name="menubar_icon" value="16px" {if $menubar_icon=='16px'}checked="checked"{/if}>
        16px
        <img src="{$OAUTH_PATH}template/icons/16px/facebook.png">
        <img src="{$OAUTH_PATH}template/icons/16px/google.png">
        <img src="{$OAUTH_PATH}template/icons/16px/twitter.png">
      </label>
      <label>
        <input type="radio" name="menubar_icon" value="26px" {if $menubar_icon=='26px'}checked="checked"{/if}>
        26px
        <img src="{$OAUTH_PATH}template/icons/26px/facebook.png">
        <img src="{$OAUTH_PATH}template/icons/26px/google.png">
        <img src="{$OAUTH_PATH}template/icons/26px/twitter.png">
      </label>
      <label>
        <input type="radio" name="menubar_icon" value="38px" {if $menubar_icon=='38px'}checked="checked"{/if}>
        38px
        <img src="{$OAUTH_PATH}template/icons/38px/facebook.png">
        <img src="{$OAUTH_PATH}template/icons/38px/google.png">
        <img src="{$OAUTH_PATH}template/icons/38px/twitter.png">
      </label>
    </li>
  </ul>
</fieldset>

<p style="text-align:left;"><input type="submit" name="save_config" value="{'Save Settings'|@translate}"></p>
</form>

<p>Icons from http://www.wpzoom.com - Library from http://hybridauth.sourceforge.net/</p>