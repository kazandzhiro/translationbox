function get_translation (text_var,from,to) {
    jQuery.post(
        // see tip #1 for how we declare global javascript variables
        tr_box_ajax.ajaxurl,
        {
            // here we declare the parameters to send along with the request
            // this means the following action hooks will be fired:
            // wp_ajax_nopriv_myajax-submit and wp_ajax_myajax-submit
            action : 'tr-box-request',
     
            // other parameters can be added along with "action"
            text_to_translate : text_var,
            from_language: from,
            to_language: to
        },
        function(returned_json){
            var translation_text = returned_json.matches[0].translation;

            if (jQuery('#from_text').length > 0) {
                        jQuery('#from_text').text(translation_text);
                    } else { jQuery('#translate').after('<textarea id=\"from_text\" style=\"width:{$width}; height:{$height};\">'+translation_text+'</textarea>');}
        }
    );
}