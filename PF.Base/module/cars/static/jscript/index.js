$Behavior.onLoadIndex = function(){
    $('#type_container select').change(function(){
        if ($('#type_container select option:selected').val() > 0){
            $('#js_type_loader').css({'display':'block'});
            $.ajaxCall('cars.displayFilters', 'type_id='+$('#type_container select option:selected').val());
        }
    });

    $('#release_container select:first option:first').text(oTranslations['cars.from']);
    $('#release_container select:last option:first').text(oTranslations['cars.to']);


    var inputPriceTo = $('#price_container input[type=text]:last'), inputPriceFrom = $('#price_container input[type=text]:first');
    if(inputPriceTo.val()==''){
        inputPriceTo.val(oTranslations['cars.to']).css({'color':'#999'});
    }else if(inputPriceTo.val() == oTranslations['cars.to']){
        inputPriceTo.css({'color':'#999'});
    }else{
        inputPriceTo.css({'color':'#000'});
    }

    if(inputPriceFrom.val()==''){
        inputPriceFrom.val(oTranslations['cars.from']).css({'color':'#999'});
    }else if(inputPriceFrom.val() == oTranslations['cars.from']){
        inputPriceFrom.css({'color':'#999'});
    }else{
        inputPriceFrom.css({'color':'#000'});
    }
    inputPriceTo.focus(function(){
        if(inputPriceTo.val()==oTranslations['cars.to']){
            inputPriceTo.val('').css({'color':'#000'});
        }
    });
    inputPriceTo.blur(function(){
        if(inputPriceTo.val()==''){
            inputPriceTo.val(oTranslations['cars.to']).css({'color':'#999'});
        }

    });
    inputPriceFrom.focus(function(){
        if(inputPriceFrom.val()==oTranslations['cars.from'])inputPriceFrom.val('').css({'color':'#000'});
    });
    inputPriceFrom.blur(function(){
        if(inputPriceFrom.val()=='')inputPriceFrom.val(oTranslations['cars.from']).css({'color':'#999'});
    });


    var inputTitle = $('#title input[type=text]');
    if(inputTitle.val()==''){
        inputTitle.val(oTranslations['cars.title_example']).css({'color':'#999'});
    }else if(inputTitle.val() == oTranslations['cars.title_example']){
        inputTitle.css({'color':'#999'});
    }else{
        inputTitle.css({'color':'#000'});
    }

    inputTitle.focus(function(){
        if(inputTitle.val()==oTranslations['cars.title_example']){
            inputTitle.val('').css({'color':'#000'});
        }
    });
    inputTitle.blur(function(){
        if(inputTitle.val()==''){
            inputTitle.val(oTranslations['cars.title_example']).css({'color':'#999'});
        }

    });
}
