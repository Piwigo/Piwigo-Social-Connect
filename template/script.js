// redirect, called from the popup
function oauth_redirect(type) {
  var url = '{$OAUTH.u_redirect}';
  if (type && type != 'default') {
    url = '{$ABS_ROOT_URL}'+ type +'.php';
  }

  window.location.href = url;
}

// open authentication popup
function open_auth(url) {
  window.open(
    '{$OAUTH.u_login}' + url + '&key={$OAUTH.key}', 
    'hybridauth_social_sign_on', 
    'location=0,status=0,scrollbars=0,width=800,height=500'
  );  
}

// click on a button
jQuery('a.oauth:not(.persona)').click(function(e) {
  e.preventDefault();
  
  var idp = jQuery(this).data('idp');
  
  switch(idp) {
    case 'OpenID': case 'Wordpress': case 'Flickr': case 'Steam':
      switch(idp) {
        case 'OpenID':
          jQuery('#openid_label').html('{'Please enter your OpenID URL'|translate|escape:javascript}'); break;
        case 'Wordpress': case 'Steam':
          jQuery('#openid_label').html('{'Please enter your username'|translate|escape:javascript}'); break;
        case 'Flickr': 
          jQuery('#openid_label').html('{'Please enter your user ID'|translate|escape:javascript}'); break;
      }
      
      var bg_color = $('#the_page #content').css('background-color');
      if (!bg_color || bg_color=='transparent') {
        bg_color = $('body').css('background-color');
      }
      jQuery('#openid_form').css('background-color', bg_color);

      jQuery('#openid_form .oauth_38px').removeClass().addClass('oauth_38px ' + idp.toLowerCase());
      jQuery('#openid_form h3').html(idp);
      jQuery('#openid_form').data('idp', idp);

      jQuery.colorbox({
        inline: true,
        href: '#openid_form',
        initialWidth: 0,
        initialHeight: 0,
        mawWidth: '100%',
        onComplete: function(){ jQuery.colorbox.resize({ speed:0 }) } // prevent misalignement when icon not loaded
      });
      break;
      
    default:
      open_auth(idp);
  }
});

jQuery('#openid_form').submit(function(e) {
  e.preventDefault();
  
  var idp = jQuery(this).data('idp');
  var oi = jQuery('#openid_form input[name=openid_identifier]').val();
  jQuery('#openid_form input[name=openid_identifier]').val('');
  
  jQuery('#openid_label').removeClass('error');
  if (!oi) {
    jQuery('#openid_label').addClass('error');
    return;
  }
  
  switch(idp) {
    case 'Wordpress': oi = 'http://' + oi + '.wordpress.com'; break;
    case 'Flickr': oi = 'http://www.flickr.com/photos/' + oi + '/'; break;
    case 'Steam': oi = 'http://steamcommunity.com/openid/' + oi; break;
  }

  open_auth('OpenID&openid_identifier=' + encodeURI(oi));

  jQuery.colorbox.close();
});

jQuery('#openid_cancel').click(function(e) {
  e.preventDefault();
  
  jQuery('#openid_label').removeClass('error');
  jQuery.colorbox.close();
});

{if $OAUTH.providers.Persona.enabled}
  jQuery('a.oauth.persona').click(function(e) {
    e.preventDefault();
    navigator.id.request();
  });

  {if not empty($OAUTH.persona_email)}
  jQuery('a[href$="act=logout"]').click(function(e) {
    e.preventDefault();
    navigator.id.logout();
  });
  {/if}

  navigator.id.watch({
    loggedInUser: {if not empty($OAUTH.persona_email)}'{$OAUTH.persona_email}'{else}null{/if},
    
    onlogin: function(assertion) {
      jQuery.ajax({
        type: 'POST',
        url: '{$OAUTH.u_login}Persona',
        dataType: 'json',
        data: {
          assertion: assertion,
          key: '{$OAUTH.key}'
        },
        success: function(data) {
          oauth_redirect(data.redirect_to);
        },
        error: function() {
          alert('Unknown error');
        }
      });
    },
    
    onlogout: function() {
      window.location.href = '{$U_LOGOUT}';
    }
  });
{/if}