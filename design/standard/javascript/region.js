// Query current region via AJAX
$(document).ready(function() {

    $(".languages-nav-holder a").click(function() {
        setRegionWarningShownFlag();
    });

    if (!hasRegionWarningBeenShown()) {
        checkRegion();
    }


    function hasRegionWarningBeenShown() {
        var regionWarningShown = sessionStorage.getItem("regionWarningShown");

        if (regionWarningShown == null || regionWarningShown == 'false') {
            return false;
        } else {
            return true;
        }
    }

    function setRegionWarningShownFlag() {
        sessionStorage.setItem('regionWarningShown', 'true');
    }

    function checkRegion() {

        console.log("Initiating /region/check");

        $.ajax({
            url: "/region/check",
            cache: false,
            method: 'POST',
            dataType: 'json'
        })
            .done( function(result) {
                console.log("/region/check AJAX returned. Result: " + JSON.stringify(result));

                if(result != null) {
                    var response = result;
                    if (typeof response == 'object') {

                        var redirectTo = response.redirectTo;
                        if (redirectTo != null) {

                            // replace text copy in dialog with content from target (detected) language

                            setRegionWarningShownFlag(); // region warning has been shown, do not show again.

                            $("#select-your-region-prompt").text(response.selectYourRegionPrompt);

                            $(document).on('click touchstart', '.ui-dialog-buttonset button:first', function (e) {
                                e.preventDefault();
                                location.href = response.redirectTo;
                                $("#dialog-confirm").dialog("close");
                            });
                            $(document).on('click touchstart', '.ui-dialog-buttonset button:eq(1)', function (e) {
                                e.preventDefault();
                                $("#dialog-confirm").dialog("close");
                            });

                            $("#dialog-confirm").dialog({
                                resizable: false,
                                height: 340,
                                width: 400,
                                modal: true,
                                buttons: [{text: response.goToDetectedRegionPrompt}, {text: response.continueToRegionPrompt}]
                            });
                        }
                    }
                }
            });
    }

});



