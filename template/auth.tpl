<!DOCTYPE html>
<html lang="{$lang_info.code}" dir="{$lang_info.direction}">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset={$CONTENT_ENCODING}">
  <title>{'Sign in with %s'|@translate|sprintf:$PROVIDER} | {$GALLERY_TITLE}</title>
</head>

<body>

<div style="text-align:center;margin-top:20px;">
{if $ERROR}
  <img id="loader" src="{$OAUTH_PATH}template/images/alert.png">
  <h3>{'Error...'|@translate}</h3>
  {$ERROR}<br>
  <a href="#" id="close">{'Close'|@translate}</a>
  
  <script type="text/javascript"> 
    document.getElementById('close').onclick = function() {ldelim} window.self.close(); };
  </script>
  
{elseif $LOADING}
  <img id="loader" src="{$OAUTH_PATH}template/images/ajax-loader-big.gif">
  <h3>{'Loading...'|@translate}</h3>
  {'Contacting <b>%s</b>. Please wait.'|@translate|sprintf:$PROVIDER}
  
  <script type="text/javascript">
    setTimeout('window.location.href = "{$SELF_URL}{$LOADING}";', 200);
  </script>
  
{elseif $REDIRECT_TO}
  <script type="text/javascript"> 
    if (window.opener) window.opener.parent.redirect('{$REDIRECT_TO}');
    window.self.close();
  </script>
{/if}
</div>

</body>
</html>