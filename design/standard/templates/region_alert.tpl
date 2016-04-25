<div id="dialog-confirm" style="display: none;">
    <p>{"Select your region"|i18n('region')}</p>
</div>
{def
    $current_SA = siteaccess('name')
    $current_region = ezini( 'RegionalSettings', 'TranslationSA' )[$current_SA]
    $detected_region = detected_region()
}

<script type="text/javascript">
    var geo_go = "{"Go to our %region site"|i18n('region','',hash('%region', $detected_region ))}";
    var geo_continue = "{"Continue to our %region site"|i18n('region','',hash('%region', $current_region ))}";
</script>

