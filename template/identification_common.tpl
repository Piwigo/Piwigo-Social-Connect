{combine_css path=$OAUTH_PATH|cat:'template/oauth_sprites.css'}

{combine_script id='jquery.colorbox' load='footer' require='jquery' path='themes/default/js/plugins/jquery.colorbox.min.js'}
{combine_css id='colorbox' path="themes/default/js/plugins/colorbox/style2/colorbox.css"}

{if $OAUTH.providers.Persona.enabled}
  {combine_script id='persona' path='https://login.persona.org/include.js' load='footer'}
{/if}

{combine_script id='oauth' load='footer' require='jquery.colorbox' template=true path=$OAUTH_PATH|cat:'template/script.js'}

{html_style}
#openid_form { padding:20px; }
#openid_form h3, #openid_form .oauth_38px { display:inline-block; vertical-align:middle; margin:0; }
#openid_label.error { color:red; font-weight:bold; }
{/html_style}


<div style="display:none;">
  <form id="openid_form" action="">
    <div>
      <span class="oauth_38px"></span>
      <h3>OpendID</h3>
    </div>
    <div>
      <br>
      <label id="openid_label" for="openid_identifier"></label>
      <br>
      <input type="text" name="openid_identifier" id="openid_identifier" size="50">
    </div>
    <div>
      <br>
      <input type="submit" name="{'Submit'|translate}">
      <a href="#" id="openid_cancel">{'Cancel'|translate}</a>
    </div>
  </form>
</div>