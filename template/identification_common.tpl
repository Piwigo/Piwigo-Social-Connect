{html_style}{literal}
  #openid_form {
    padding:20px;
  }
  #openid_form h3, #openid_form img {
    display:inline-block;
    vertical-align:middle;
    margin:0;
  }
  #openid_label.error {
    color:red;
    font-weight:bold;
  }
{/literal}{/html_style}

{combine_script id='jquery.colorbox' load='footer' require='jquery' path='themes/default/js/plugins/jquery.colorbox.min.js'}
{combine_css path="themes/default/js/plugins/colorbox/style2/colorbox.css"}

{footer_script}{literal}
// redirect, called from the popup
function redirect(type) {
{/literal}
  url = "{$REDIRECT_TO}";
  if (typeof type != 'undefined' && type != 'default') {ldelim}
    url = "{$ABS_ROOT_URL}"+ type +".php";
  }
{literal}
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
$("a.oauth").click(function() {
  var idp = $(this).attr('title');
  
  switch(idp) {
    case 'OpenID': case 'Wordpress': case 'Flickr':
      switch(idp) {
{/literal}
        case 'OpenID':
          $("#openid_label").html('{'Please enter your OpenID URL'|@translate|escape:javascript}'); break;
        case 'Wordpress': case 'Flickr':
          $("#openid_label").html('{'Please enter your username'|@translate|escape:javascript}'); break;
      }
      
      $("#openid_form").css('background-color', $("#the_page #content").css('background-color'));
      $("#openid_form img").attr('src', '{$ROOT_URL}{$OAUTH_PATH}template/icons/38px/'+ idp.toLowerCase() +'.png');
      $("#openid_form h3").html(idp);
      $("#openid_form").data('idp', idp);
{literal}  
      $.colorbox({
        inline:true,
        href:"#openid_form",
        initialWidth:0,
        initialHeight:0,
        onComplete:function(){ $.colorbox.resize({speed:0}) } // prevent misalignement when icon not loaded
      })
      break;
      
    default:
{/literal}
      open_auth("{$OAUTH_URL}"+ idp);
{literal}
  }
  
  return false;
});

$("#openid_form").submit(function() {
  var idp = $(this).data('idp');
  var oi = $("#openid_form input[name='openid_identifier']").val();
  $("#openid_form input[name='openid_identifier']").val('');
  
  $("#openid_label").removeClass('error');
  if (!oi) {
    $("#openid_label").addClass('error');
    return false;
  }
  
  switch(idp) {
    case 'Wordpress': oi = "http://" + oi + ".wordpress.com"; break;
    case 'Flickr': oi = "http://www.flickr.com/photos/" + oi + "/"; break;
  }
{/literal}  
  open_auth("{$OAUTH_URL}OpenID&openid_identifier="+ encodeURI(oi));
{literal}
  $.colorbox.close();
  return false;
});

$("#openid_cancel").click(function() {
  $("#openid_label").removeClass('error');
  $.colorbox.close();
  return false;
});
{/literal}{/footer_script}

<div style="display:none;">
  <form id="openid_form" action="">
    <div>
      <img src="{$ROOT_URL}{$OAUTH_PATH}template/icons/openid_big.png" style="width:38px:height:38px;">
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
      <input type="submit" name="{'Submit'|@translate}">
      <a href="#" id="openid_cancel">{'Cancel'|@translate}</a>
    </div>
  </form>
</div>