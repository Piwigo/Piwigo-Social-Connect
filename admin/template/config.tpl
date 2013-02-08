{combine_css path=$OAUTH_PATH|cat:"admin/template/style.css"}
{combine_css path=$OAUTH_PATH|cat:'template/oauth_sprites.css'}

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
        <span class="oauth_16px facebook"></span>
        <span class="oauth_16px google"></span>
        <span class="oauth_16px twitter"></span>
      </label>
      <label>
        <input type="radio" name="identification_icon" value="26px" {if $identification_icon=='26px'}checked="checked"{/if}>
        26px
        <span class="oauth_26px facebook"></span>
        <span class="oauth_26px google"></span>
        <span class="oauth_26px twitter"></span>
      </label>
      <label>
        <input type="radio" name="identification_icon" value="38px" {if $identification_icon=='38px'}checked="checked"{/if}>
        38px
        <span class="oauth_38px facebook"></span>
        <span class="oauth_38px google"></span>
        <span class="oauth_38px twitter"></span>
      </label>
    </li>
    
    <li>
      <b>{'Icon size in the menubar'|@translate} :</b><br>
      <label>
        <input type="radio" name="menubar_icon" value="16px" {if $menubar_icon=='16px'}checked="checked"{/if}>
        16px
        <span class="oauth_16px facebook"></span>
        <span class="oauth_16px google"></span>
        <span class="oauth_16px twitter"></span>
      </label>
      <label>
        <input type="radio" name="menubar_icon" value="26px" {if $menubar_icon=='26px'}checked="checked"{/if}>
        26px
        <span class="oauth_26px facebook"></span>
        <span class="oauth_26px google"></span>
        <span class="oauth_26px twitter"></span>
      </label>
      <label>
        <input type="radio" name="menubar_icon" value="38px" {if $menubar_icon=='38px'}checked="checked"{/if}>
        38px
        <span class="oauth_38px facebook"></span>
        <span class="oauth_38px google"></span>
        <span class="oauth_38px twitter"></span>
      </label>
    </li>
  </ul>
</fieldset>

<p style="text-align:left;"><input type="submit" name="save_config" value="{'Save Settings'|@translate}"></p>
</form>

<div style="text-align:right;">
  Icons from : <a href="http://www.wpzoom.com" target="_blank">WPZOOM</a> |
  Library : <a href="http://hybridauth.sourceforge.net" target="_blank">HybridAuth</a>
</div>