// Query current region via AJAX
$(document).ready(function() {


    if (isRegionCheckPending()) {
        checkRegion();
    }


    function isRegionCheckPending() {

        var regionChecked = localStorage.getItem("regionchecked");

        if (regionChecked == null || regionChecked == 0) {
            return true;
        } else {
            return false;
        }
    }

    function setRegionCheckedFlag(regionChecked) {
        localStorage.setItem('regionchecked', regionChecked);
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

                        var isRegionChecked = response.regionChecked; // either a 1 or 0 comes back from the back end. A zero means no region check was performed (try again later), a 1 means we have done one at the back end.
                        var redirectTo = response.redirectTo;

                        setRegionCheckedFlag(isRegionChecked);
                        if (isRegionChecked && redirectTo != null) {

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
                                buttons: [{text: geo_go}, {text: geo_continue}]
                            });
                        }
                    }
                }
            });
    }

});



