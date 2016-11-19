window.setSearchParam = function () {
    $('#country_iso').removeClass('form-control');
    $('#country_iso').selectize({
        create: true,
        sortField: {
            field: 'text',
            direction: 'asc'
        }
    });
    $('#specie').removeClass('form-control');
    $('#specie').selectize({
        create: true,
        sortField: {
            field: 'text',
            direction: 'asc'
        }
    });
    $('#type').removeClass('form-control');
    $('#type').selectize({
        create: true,
        sortField: {
            field: 'text',
            direction: 'asc'
        }
    });
    $('#mark').selectize({
        create: true,
        sortField: {
            field: 'text',
            direction: 'asc'
        }
    });
    $('#model').selectize({
        create: true,
        sortField: {
            field: 'text',
            direction: 'asc'
        }
    });
}
$Behavior.onLoadAdd = function(){
    function inputFocusBlur(sSelector, sFocusText){
        if(sSelector.val()==''){
            sSelector.val(sFocusText).css({'color':'#999'});
        }else if(sSelector.val() == sFocusText){
            sSelector.css({'color':'#999'});
        }else{
            sSelector.css({'color':'#000'});
        }
        sSelector.focus(function(){
            if(sSelector.val()==sFocusText){
                sSelector.val('').css({'color':'#000'});
            }
        });
        sSelector.blur(function(){
            if(sSelector.val()==''){
                sSelector.val(sFocusText).css({'color':'#999'});
            }

        });
    }

    function inputOnEnterNumbers(sSelector){
        sSelector.on ('input', function (event) {
            sSelector.val(this.value.replace(/[^0-9,\,]/g, ''));

        });
    }
    var inputPhoneNumber = $('#phone_number'), inputPrice = $('#price');

//    inputFocusBlur(inputPhoneNumber, oTranslations['cars.number']);
    inputOnEnterNumbers(inputPhoneNumber);
//    inputFocusBlur(inputPrice, oTranslations['cars.price']);
    inputOnEnterNumbers(inputPrice);
    window.setSearchParam();
//    inputFocusBlur(inputTitle, oTranslations['cars.example_title']);
    /*
    if(inputPhoneCode.val()==''){
        inputPhoneCode.val(oTranslations['cars.code']).css({'color':'#999'});
    }else if(inputPhoneCode.val() == oTranslations['cars.code']){
        inputPhoneCode.css({'color':'#999'});
    }else{
        inputPhoneCode.css({'color':'#000'});
    }

    if(inputPhoneNumber.val()==''){
        inputPhoneNumber.val(oTranslations['cars.number']).css({'color':'#999'});
    }else if(inputPhoneNumber.val() == oTranslations['cars.number']){
        inputPhoneNumber.css({'color':'#999'});
    }else{
        inputPhoneNumber.css({'color':'#000'});
    }*/
    /*inputPhoneCode.focus(function(){
        if(inputPhoneCode.val()==oTranslations['cars.code']){
            inputPhoneCode.val('').css({'color':'#000'});
        }
    });
    inputPhoneCode.blur(function(){
        if(inputPhoneCode.val()==''){
            inputPhoneCode.val(oTranslations['cars.code']).css({'color':'#999'});
        }

    });*/
//    inputFocusBlur($('#phone_code'), oTranslations['cars.code']);
//    inputFocusBlur($('#phone_number'), oTranslations['cars.number']);
    /*inputPhoneNumber.focus(function(){
        if(inputPhoneNumber.val()==oTranslations['cars.number'])inputPhoneNumber.val('').css({'color':'#000'});
    });
    inputPhoneNumber.blur(function(){
        if(inputPhoneNumber.val()=='')inputPhoneNumber.val(oTranslations['cars.number']).css({'color':'#999'});
    });*/

//    inputOnEnterNumbers($('#phone_code'), oTranslations['cars.code']);
//    inputOnEnterNumbers($('#phone_number'), oTranslations['cars.number']);
    /*inputPhoneCode.on ('input', function (event) {
       if (oTranslations['cars.code'] != inputPhoneCode.val()){
           inputPhoneCode.val(this.value.replace(/[^0-9]/g, ''));
       }

    });
    inputPhoneNumber.on('input', function (event) {
        if (oTranslations['cars.number'] != inputPhoneNumber.val()){
            inputPhoneNumber.val(this.value.replace(/[^0-9]/g, ''));
        }
    });*/
}
//
//function plugin_completeProgress()
//{
//    /* An error occured, lets let them know that none of their images were uploaded */
//    if ($('#js_photo_upload_failed').hasClass('js_photo_upload_failed'))
//    {
//        alert(oTranslations['photo.none_of_your_files_were_uploaded_please_make_sure_you_upload_either_a_jpg_gif_or_png_file']);
//
//        return false;
//    }
//
//    if ($('#js_photo_action').val() == 'upload')
//    {
//        $('#js_upload_form_outer').show();
//    }
//
//    iCnt = 0;
//    sHtml = '';
//    $('.js_uploaded_image').each(function()
//    {
//        iCnt++;
//        if (iCnt == 1)
//        {
//            $(this).addClass('row_first');
//        }
//        else
//        {
//            $(this).removeClass('row_first');
//        }
//
//        sHtml += '<div id="js_uploaded_photo_' + this.id.replace('js_photo_', '') + '"><input type="hidden" name="val[photo_id][]" value="' + this.id.replace('js_photo_', '') + '" /></div>';
//    });
//    $('#js_post_form_content').html(sHtml);
//
//    switch ($('#js_photo_action').val())
//    {
//        case 'process':
//            $('#js_post_form').submit();
//            break;
//        default:
//            iNewInputBars = 0;
//            $('.js_uploader_files').remove();
//            sInput = '';
//            if (typeof oProgressBar != "undefined")
//            {
//                for (i = 1; i <= oProgressBar['total']; i++)
//                {
//                    sInput += '<div class="js_uploader_files"><input type="file" name="' + oProgressBar['file_id'] + '" size="30" class="js_uploader_files_input" disabled="disabled" onchange="addMoreToProgressBar();" /></div>' + "\n";
//                }
//            }
//            $('#js_uploader_files_outer').append(sInput);
//            break;
//    }
//}
//
//function plugin_startProgress(sProgressKey)
//{
//    $('#js_upload_form_outer').hide();
//}
//
//function deleteNewPhoto(iId)
//{
//    if (confirm(getPhrase('core.are_you_sure')))
//    {
//        $('#js_photo_' + iId).remove();
//        $('#js_uploaded_photo_' + iId).remove();
//
//        iCnt = 0;
//        $('.js_uploaded_image').each(function()
//        {
//            iCnt++;
//        });
//
//        if (!iCnt)
//        {
//            $('#js_uploaded_images').hide();
//        }
//
//        $.ajaxCall('photo.deleteNewPhoto', 'id=' + iId);
//
//        return false;
//    }
//
//    return false;
//}
//
//function plugin_addFriendToSelectList()
//{
//    $('#js_allow_list_input').show();
//}
//
//function plugin_cancelFriendSelection()
//{
//    $('#js_allow_list_input').hide();
//}
//
//function uploadComplete()
//{
//    if (typeof swfu != 'undefined')
//    {
//        var oStats = swfu.getStats();
//        if (oStats.in_progress > 0 || oStats.files_queued > 0)
//        {
//
//        }
//        else
//        {
//            var sPhotos = "";
//            for (var i in window.aImagesUrl)
//            {
//                sPhotos += 'photos[]=' + window.aImagesUrl[i] + '&';
//            }
//            sPhotos = sPhotos.substr(0, sPhotos.length - 1);
//            window.parent.$.ajaxCall('photo.process', 'js_disable_ajax_restart=true&' + sPhotos + '&action='+$('#js_photo_action').val());
//        }
//    }
//}
//
//if (typeof $Core.Photo == 'undefined') $Core.Photo = {};