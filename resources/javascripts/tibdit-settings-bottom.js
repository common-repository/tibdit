// version 10
( 
function( $ )
  {
    function initColorPicker( widget ) 
      {
        widget.find( '.bd-colourp' ).wpColorPicker({
          mode: 'hsv',
          hide: true, // hide the color picker by default
          width: 200, // the width of the collection of UI elements
          palettes: false, // show a palette of basic colors beneath the square.
          change: function(event, ui) { 
            ui.color._alpha=0.2;
            $('.iris-picker').css('background-color',ui.color.toCSS('rgba'));
          } 
        });
      }

    function onFormUpdate( event, widget ) 
      {
        initColorPicker( widget );
      }

    $( document ).on( 'widget-added widget-updated', onFormUpdate );

    $( document ).ready( function() 
    {
      $( '#widgets-right .widget:has(.bd-colourp)' ).each( function () 
        {
          initColorPicker( $( this ) );
        } 
      );
    } );
  } ( jQuery ) 
);

jQuery(document).on( "ready", function() 
    { 
      if(window.location.hash == "#help") 
        { jQuery("a#contextual-help-link").trigger("click"); }
    }
  );     
