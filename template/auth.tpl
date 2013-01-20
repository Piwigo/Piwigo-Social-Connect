<!DOCTYPE html>
<html lang="{$lang_info.code}" dir="{$lang_info.direction}">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset={$CONTENT_ENCODING}">
  <title>{'Sign in with %s'|@translate|sprintf:$PROVIDER} | {$GALLERY_TITLE}</title>
  
  {combine_script id="jquery" load="footer"}
  
  {get_combined_css}
  {get_combined_scripts load='header'}
  {if not empty($head_elements)}
    {foreach from=$head_elements item=elt}{$elt}
    {/foreach}
  {/if}
</head>

<body>
  
{if $LOADING}
  <img src="{$OAUTH_PATH}template/ajax-loader-big.gif">
  <script type="text/javascript">
    setTimeout('window.location.href = "{$SELF_URL}{$LOADING}";', 500);
  </script>
  
{elseif $AUTH_DONE}
  <script language="javascript"> 
    if (window.opener) {ldelim}
      window.opener.parent.redirect('{$REDIRECT_TO}');
    }
    window.self.close();
  </script>
{elseif $ERROR}
  {$ERROR}<br>
  <a href="#" id="close">{'Close'|@translate}</a>
{/if}


{footer_script}{literal}
$("a#close").click(function() {
  window.self.close();
});
{/literal}{/footer_script}



{get_combined_scripts load='footer'}
{if isset($footer_elements)}
  {foreach from=$footer_elements item=v}{$v}
  {/foreach}
{/if}
</body>
</html>