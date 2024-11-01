jQuery(document).ready(function($){

    timeoutId = -1;

    $('.new_spectrum_1 input').wpColorPicker({
        "width": 300,
        "palettes": ['#3CDD72', '#2c82c9', '#e2c038', '#615F79', '#777', '#000' ],
        "border": false,
        "change": function(event, ui){
            $(".preview-container .bd-btn-backdrop").css("fill", ui.color.toString());
            $('#base_colour').val(ui.color.toString());
        }
    });

    $('.new_spectrum_1 a').attr("title", "Button Face");
    $('.new_spectrum_1 .wp-picker-default').val('Reset');


});

