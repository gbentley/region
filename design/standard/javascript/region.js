// MAIN MENU TOGGLES
$(document).ready(function() {

    $.ajax({
        url: "/region/check",
        cache: false
    })
    .done( function(result) {
        if(result != '') {
            //console.log("Site needs redirection");

            var response = result;
            if (typeof response == 'object') {
                //console.log("Redirect URL = " + response.redirectto);                
                $(document).on('click touchstart','.ui-dialog-buttonset button:first',function(e){
                    e.preventDefault();
                    location.href = response.redirectto;
                    $("#dialog-confirm").dialog("close");
                });
                $(document).on('click touchstart','.ui-dialog-buttonset button:eq(1)',function(e){
                    e.preventDefault();
                    $("#dialog-confirm").dialog("close");
                });

                $("#dialog-confirm").dialog({
                    resizable: false,
                    height: 340,
                    width: 400,
                    modal: true,
                    buttons: [ { text: geo_go }, { text: geo_continue } ]
                });
            }
        }
    });

});
