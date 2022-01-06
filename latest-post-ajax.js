jQuery(document).ready(function($) {

  /*------------------------------------------------
                        Latest blog  
    ------------------------------------------------*/
    var LBcontainer = $('#call-to-action .wrapper');

    var LBpageNumber = 1;

    function top_blogger_load_latest_posts(){
        LBpageNumber++;

        $.ajax({
            type: "POST",
            dataType: "html",
            url: top_blogger.ajaxurl,
            data: {action: 'top_blogger_posts_ajax_handler',
                LBpageNumber: LBpageNumber,
            },
            success: function(data){               
                LBcontainer.append(data);             
            },
            error : function(jqXHR, textStatus, errorThrown) {
                $loader.html(jqXHR + " :: " + textStatus + " :: " + errorThrown);
            }

        });

        return false;
    }

    $("#LBloadmore").click(function(e){ // When btn is pressed.
        e.preventDefault();
        top_blogger_load_latest_posts();
    });

/*------------------------------------------------
                END JQUERY
------------------------------------------------*/

});