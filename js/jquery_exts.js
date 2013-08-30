
$.fn.extend({
  /** 
   * send_data Function is extending JQuery functions.
   *
   * By implementing this method user can send data to the sever by
   * ajax POST call, and the return data will be used to update session.
   */
  send_data: function( replace_,url_,data_,type_ ){
    return $(this).each(function(){ 
      $.ajax({
        type: "POST",
        url: url_,
        data: data_,
        dataType: type_,
        context: document,
        cache: false,
      }).done(function(code) {
          var inputs = $(code).filter(function(){ return $(this).is('input') });
          $("[name='stateless_session']").attr( 'value',$(inputs).filter("[name='stateless_session']").val() );
        });
    });
  }, 
  /**
   * append_as_form Function is extending JQuery functions.
   *
   * By implementing this method user can grab all the data contained
   * within an html element and send it to the address specified in
   * the attribute `data-href`
   *
   * This implementation will be useful for telling the server which
   * HTTP method POST GET PUT DELETE to use, through attribute `data-method`
   */
  append_as_form: function(){
    return $(this).each(function(){
      $(this).data("stateless_session",$("[name='stateless_session']").val());
      var form = $( document.createElement('form') );
      form.attr( { id: 'sender',method: 'post', action: $(this).data("href"), target: $(this).attr('target') });
      form.hide();

      $.each( $(this).data(), function( _name, _value ) {
        var _input = $( document.createElement('input'));
        _input.attr( { name: _name, value: _value } );
        form.append( _input );
      });     
    
      $('body').append( form );
      $('#sender').submit();
    });
  }

});  
