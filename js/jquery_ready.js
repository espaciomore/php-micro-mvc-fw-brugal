var JS_TOOLS = (function(){
  var instance;
  function init(){
    return {
      /**
       * bind_click Function for binding `click` event to .post_link and sending as form.
       * @return void
       */
      bind_postable: function(){
        $('.post_link').on('click',function(){
          $(this).append_as_form(); 
          $('#sender').remove();
          return false;
        });  
      },
      /**
       * bind_submit Function for binding `submit` event to include session info to forms.
       * @return void
       */
      bind_submit: function(){  
        $('form.js_postable').on('submit',function() {
          $(this).data("stateless_session",$("[name='stateless_session']").val());

          $.each( $(this).data(), function( _name, _value ) {
            var _input = $( document.createElement('input'));
            _input.attr( { name: _name, value: _value, hidden: true } );
            $('form.js_postable').append( _input );
          }); 
        });       
      },
      /**
       * show_notes Function for show notes / messages on user actions.
       * @return void
       */
      show_notes: function(){
        var note = $('.action-note-container').each(function(){
          if ( $(this).find('p').text()!='' ){
            $(this).show({duration: 0, queue: true})
              .delay(4000)
                .hide({duration: 0, queue: true});      
          }
        });    
      }
    };
  }
  return (function(){
      if ( !instance ){ 
        instance = init();
      }
      return instance; 
    })();  
})();

$(document).ready(function(){    
  JS_TOOLS.bind_postable();
  JS_TOOLS.bind_submit(); 
  JS_TOOLS.show_notes();   
});  
