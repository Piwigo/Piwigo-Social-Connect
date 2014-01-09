{combine_css path=$OAUTH_PATH|cat:'template/oauth_sprites.css'}

{combine_script id='jquery.colorbox' load='footer' require='jquery' path='themes/default/js/plugins/jquery.colorbox.min.js'}
{combine_css id='colorbox' path="themes/default/js/plugins/colorbox/style2/colorbox.css"}

{html_style}
#openid_form { padding:20px; }
#openid_form h3, #openid_form .oauth_38px { display:inline-block; vertical-align:middle; margin:0; }
#openid_label.error { color:red; font-weight:bold; }
{/html_style}

{footer_script}
// redirect, called from the popup
function redirect(type) {
  var url = "{$REDIRECT_TO}";
  if (typeof type != 'undefined' && type != 'default') {
    url = "{$ABS_ROOT_URL}"+ type +".php";
  }

  window.location.href = url;
}

// open authentication popup
function open_auth(url) {
  window.open(
    url+ "&t=" + (new Date()).getTime(), 
    "hybridauth_social_sing_on", 
    "location=0,status=0,scrollbars=0,width=800,height=500"
  );  
}

// click on a button
jQuery("a.oauth").click(function() {
  var idp = jQuery(this).data('idp');
  
  switch(idp) {
    case 'OpenID': case 'Wordpress': case 'Flickr': case 'Steam':
      switch(idp) {
        case 'OpenID':
          jQuery("#openid_label").html('{'Please enter your OpenID URL'|translate|escape:javascript}'); break;
        case 'Wordpress': case 'Flickr': case 'Steam':
          jQuery("#openid_label").html('{'Please enter your username'|translate|escape:javascript}'); break;
      }
      
      var bg_color = $('#the_page #content').css('background-color');
      if (!bg_color || bg_color=='transparent') {
        bg_color = $('body').css('background-color');
      }
      jQuery("#openid_form").css('background-color', bg_color);

      jQuery("#openid_form .oauth_38px").removeClass().addClass("oauth_38px " + idp.toLowerCase());
      jQuery("#openid_form h3").html(idp);
      jQuery("#openid_form").data('idp', idp);

      jQuery.colorbox({
        inline:true,
        href:"#openid_form",
        initialWidth:0,
        initialHeight:0,
        onComplete: function(){ jQuery.colorbox.resize({ speed:0 }) } // prevent misalignement when icon not loaded
      })
      break;
      
    default:
      open_auth("{$OAUTH_URL}"+ idp);
  }
  
  return false;
});

jQuery("#openid_form").submit(function() {
  var idp = jQuery(this).data('idp');
  var oi = jQuery("#openid_form input[name='openid_identifier']").val();
  jQuery("#openid_form input[name='openid_identifier']").val('');
  
  jQuery("#openid_label").removeClass('error');
  if (!oi) {
    jQuery("#openid_label").addClass('error');
    return false;
  }
  
  switch(idp) {
    case 'Wordpress': oi = "http://" + oi + ".wordpress.com"; break;
    case 'Flickr': oi = "http://www.flickr.com/photos/" + oi + "/"; break;
    case 'Steam': oi = "http://steamcommunity.com/openid/" + oi; break;
  }

  open_auth("{$OAUTH_URL}OpenID&openid_identifier="+ encodeURI(oi));

  jQuery.colorbox.close();
  return false;
});

jQuery("#openid_cancel").click(function() {
  jQuery("#openid_label").removeClass('error');
  jQuery.colorbox.close();
  return false;
});
{/footer_script}

<div style="display:none;">
  <form id="openid_form" action="">
    <div>
      <span class="oauth_38px"></span>
      <h3>OpendID</h3>
    </div>
    <div>
      <br>
      <label id="openid_label" for="openid_identifier">Open ID URL</label>
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