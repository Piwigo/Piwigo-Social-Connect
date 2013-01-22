{html_head}{literal}
<style type="text/css">
  #openid_form {
    padding:20px;
  }
  #openid_form h3, #openid_form img {
    display:inline-block;
    vertical-align:middle;
    margin:0;
  }
  #openid_error {
    color:red;
    font-weight:bold;
  }
</style>
{/literal}{/html_head}

{combine_script id='jquery.colorbox' load='footer' require='jquery' path='themes/default/js/plugins/jquery.colorbox.min.js'}
{combine_css path="themes/default/js/plugins/colorbox/style2/colorbox.css"}

{footer_script}
// redirect, called from the popup
function redirect(type) {ldelim}
  url = "{$REDIRECT_TO}";
  if (typeof type != 'undefined' && type != 'default') {ldelim}
    url = "{$ABS_ROOT_URL}"+ type +".php";
  }
  window.location.href = url;
}

// open authentication popup
function open_auth(url) {ldelim}
  window.open(
    url+ "&t=" + (new Date()).getTime(), 
    "hybridauth_social_sing_on", 
    "location=0,status=0,scrollbars=0,width=800,height=500"
  );  
}

// click on a button
$("a.oauth").click(function() {ldelim}
  var idp = $(this).attr('title');
  
  switch(idp) {ldelim}
    case 'OpenID': case 'Wordpress': case 'Flickr':
      switch(idp) {ldelim}
        case 'OpenID':
          $("#openid_label").html('{'Please enter your OpenID URL'|@translate}'); break;
        case 'Wordpress': case 'Flickr':
          $("#openid_label").html('{'Please enter your username'|@translate}'); break;
      }
  
      $("#openid_form").css('background-color', $("#the_page #content").css('background-color'));
      $("#openid_form img").attr('src', '{$ROOT_URL}{$OAUTH_PATH}template/icons/38px/'+ idp.toLowerCase() +'.png');
      $("#openid_form h3").html(idp);
      $("#openid_form").data('idp', idp);
      
      $.colorbox({ldelim}
        inline:true,
        href:"#openid_form",
        initialWidth:0,
        initialHeight:0
      })
      break;
      
    default:
      open_auth("{$OAUTH_URL}"+ idp);
  }
  
  return false;
});

$("#openid_form").submit(function() {ldelim}
  var idp = $(this).data('idp');
  var oi = $("#openid_form input[name='openid_identifier']").val();
  
  $("#openid_error").hide();
  if (!oi) {ldelim}
    $("#openid_error").html("{'Put your username or OpenID on this field'|@translate}").show();
    $.colorbox.resize()
    return false;
  }
  
  switch(idp) {ldelim}
    case 'Wordpress': oi = "http://" + oi + ".wordpress.com"; break;
    case 'Flickr': oi = "http://www.flickr.com/photos/" + oi + "/"; break;
  }
  
  open_auth("{$OAUTH_URL}OpenID&openid_identifier="+ encode(oi));
  $.colorbox.close();
  return false;
});

$("#openid_cancel").click(function() {ldelim}
  $.colorbox.close();
  return false;
});
{/footer_script}

<div style="display:none;">
  <form id="openid_form" action="">
    <div>
      <img src="{$ROOT_URL}{$OAUTH_PATH}template/icons/openid_big.png">
      <h3>OpendID</h3>
    </div>
    <div>
      <br>
      <label id="openid_label" for="openid_identifier">Open ID URL</label>
      <br>
      <input type="text" name="openid_identifier" id="openid_identifier" size="50">
    </div>
    <div id="openid_error" style="display:none;"></div>
    <div>
      <br>
      <input type="submit" name="{'Submit'|@translate}">
      <a href="#" id="openid_cancel">{'Cancel'|@translate}</a>
    </div>
  </form>
</div>