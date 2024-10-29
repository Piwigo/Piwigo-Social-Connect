<!DOCTYPE html>
<html lang="{$lang_info.code}" dir="{$lang_info.direction}">
<head>
  <meta charset="{$CONTENT_ENCODING}">
  <title>{'Sign in with %s'|translate:$PROVIDER} | {$GALLERY_TITLE}</title>
</head>

<body>

<div style="text-align:center;margin-top:20px;">
{if isset($ERROR)}
  <img id="loader" src="{$OAUTH_PATH}template/images/alert.png">
  <h3>{'Error...'|translate}</h3>
  {$ERROR}<br>
  <a href="#" id="close">{'Close'|translate}</a>
  
  <script type="text/javascript"> 
    document.getElementById('close').onclick = function() { window.self.close(); };
  </script>
  
{elseif isset($LOADING)}
  <img id="loader" src="{$OAUTH_PATH}template/images/ajax-loader-big.gif">
  <h3>{'Loading...'|translate}</h3>
  {'Contacting <b>%s</b>. Please wait.'|translate:$PROVIDER}
  
  <script type="text/javascript">
    setTimeout('window.location.href = "{$SELF_URL}{$LOADING}";', 500);
  </script>
  
{elseif isset($REDIRECT_TO)}
  <script type="text/javascript"> 
    if (window.opener) window.opener.parent.oauth_redirect('{$REDIRECT_TO}');
    window.self.close();
  </script>
{/if}
</div>

</body>
</html>