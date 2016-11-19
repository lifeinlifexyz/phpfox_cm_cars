<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		CodeMake.Org
 * @package  		Module_Blog
 * @version 		$Id: add.html.php 6216 2013-07-08 08:20:46Z Bolot_Kalil $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{if isset($aForms.car_id)}
<div class="view_item_link">
	<a href="{permalink module='cars' id=$aForms.car_id title=$aForms.title}">{phrase var='cars.view_car'}</a>
</div>
{/if}

<script type="text/javascript">
{literal}
	function plugin_addFriendToSelectList()
	{
		$('#js_allow_list_input').show();
	}
{/literal}
</script>
<div class="main_break" id="js_photo_form_holder">
	{$sCreateJs}
	<form method="post" name="core_js_cars_form" action="{if $bIsEdit && isset($aForms.car_id)}{url link='cars.add' id=$aForms.car_id}{else}{url link='cars.add'}{/if}" id="core_js_cars_form" onsubmit="{$sGetJsForm}" enctype="multipart/form-data">
		
		<div class="table">
			<div class="table_left">
				<label for="title">{required}{phrase var='cars.title'}:</label>
			</div>
			<div class="table_right">
				<input type="text" class="form-control" name="val[title]" value="{value type='input' id='title'}" id="title" size="40" />
                <div class="extra_info">{phrase var='cars.fill_title_for_car'}</div>
			</div>			
		</div>

        <div class="table">
            <div class="table_left">
                <label for="text">{phrase var='cars.description'}:</label>
            </div>
            <div class="table_right">
                {editor id='text'}
            </div>
        </div>
        {if !empty($aLocations)}
            <div class="table">
                <div class="table_left">
                    <label for="text">{required}{phrase var='cars.location'}:</label>
                </div>
                <div class="table_right">
                    <select name="val[location_iso]" id="cm_iso_location">
                        <option value="">{phrase var='core.any'}</option>
                        {foreach from=$aLocations key=sIso item=sName}
                        {if $bIsEdit}
                            <option {if isset($aForms.location_iso) && $aForms.location_iso == $sIso}selected="selected"{/if} value="{$sIso}">{$sName}</option>
                        {else}
                            <option {if isset($aVals.location_iso) && $aVals.location_iso == $sIso}selected="selected"{/if} value="{$sIso}">{$sName}</option>
                        {/if}

                        {/foreach}
                    </select>
                    <div class="extra_info">{phrase var='cars.select_location_of_the_car'}</div>
                </div>
            </div>
        {/if}

        {literal}
            <script type="text/javascript">
                $Behavior.onLoadIndex = function(){
                    $('#type_container select').change(function(){
                        if ($('#type_container select option:selected').val() > 0){
                            $('#js_type_loader').css({'visibility':'visible'});
                            $.ajaxCall('cars.displayAddFilters', 'type_id='+$('#type_container select option:selected').val());
                        }
                    });
                }
            </script>
        {/literal}

        <div class="table" id="type_container">
            <div class="table_left">
                <label for="text">{required}{phrase var='cars.type'}:</label>
            </div>
            <div class="table_right">
                <select id="type" name="val[type]">
                    <option value="">{phrase var='core.any'}</option>
                    {if !empty($aTypes)}
                    {foreach from=$aTypes item=aType}
                        {if $bIsEdit}
                            <option {if isset($aForms.type_id) && $aForms.type_id == $aType.type_id}selected="selected"{/if} value="{$aType.type_id}">{$aType.title}</option>
                        {else}
                            <option {if isset($aVals.type) && $aVals.type == $aType.type_id}selected="selected"{/if} value="{$aType.type_id}">{$aType.title}</option>
                        {/if}
                    {/foreach}
                    {/if}
                </select>
                <?php
                    printf('<span id="js_type_loader" style="visibility:hidden;"><img src="%s" class="v_middle" /></span>', Phpfox::getLib('template')->getStyle('image', 'ajax/small.gif'));
                ?>
                <div class="extra_info">{phrase var='cars.select_types_of_the_car'}</div>
            </div>
        </div>
        {template file='cars.block.addfilters'}
        {if !empty($aReleaseYears)}
        <div class="table">
            <div class="table_left">
                <label for="text">{required}{phrase var='cars.release_year'}:</label>
            </div>
            <div class="table_right">
                <select name="val[release]" id="release">
                    <option value="">{phrase var='cars.release_year'}</option>
                    {foreach from=$aReleaseYears name=release key=iIndex item=iReleaseYear}
                        {if $bIsEdit}
                            <option {if isset($aForms.release_year) && $aForms.release_year == $iIndex}selected="selected"{/if} value="{$iIndex}">{$iReleaseYear}</option>
                        {else}
                            <option {if isset($aVals.release) && $aVals.release == $iIndex}selected="selected"{/if} value="{$iIndex}">{$iReleaseYear}</option>
                        {/if}
                    {/foreach}
                </select>
                <div class="extra_info">{phrase var='cars.select_release_year_of_the_car'}</div>
            </div>
        </div>
        {/if}
        <div class="table">
            <div class="table_left">
                <label for="text">{required}{phrase var='cars.phone_number'}:</label>
            </div>
            <div class="table_right">
                <input value="{value type='input' id='phone_number'}" class="form-control" type="text" id="phone_number" name="val[phone_number]"/>
                <div class="extra_info">{phrase var='cars.provide_phone_number'}</div>
            </div>
        </div>
        <div class="table">
            <div class="table_left">
                <label for="text">{required}{phrase var='cars.price'}:</label>
            </div>
            <div class="table_right">
                <input value="{value type='input' id='price'}" class="form-control" type="text" id="price" name="val[price]"/>
                <div class="extra_info">{phrase var='cars.enter_the_price_of_the_car'}</div>
            </div>
        </div>
        <div class="table">
            <div class="table_left">
                <label for="text">{phrase var='cars.currency'}:</label>
            </div>
            <div class="table_right">
                <select class="form-control" name="val[currency]" id="currency">
                    {foreach from=$aCurrencies name=currency key=iCurrencyIndex item=sCurrency}
                    {if $bIsEdit}
                    <option {if isset($aForms.currency) && $aForms.currency == $iCurrencyIndex}selected="selected"{/if} value="{$iCurrencyIndex}">{$sCurrency}</option>
                    {else}
                    <option {if isset($aVals.currency) && $aVals.currency == $iCurrencyIndex}selected="selected"{/if} value="{$iCurrencyIndex}">{$sCurrency}</option>
                    {/if}
                    {/foreach}
                </select>
            </div>
        </div>

        <div class="table">
            <div class="table_left">
                <label for="text">{phrase var='cars.is_sold'}:</label>
            </div>
            <div class="table_right">
                {if $bIsEdit}
                    <div class="radio">
                        <label><input type="radio" {if isset($aForms.is_sold) && $aForms.is_sold == 0}checked="checked"{/if} name="val[is_sold]" value="0" id="is_sold" /> {phrase var='core.no'}</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" {if isset($aForms.is_sold) && $aForms.is_sold == 1}checked="checked"{/if} name="val[is_sold]" value="1" id="is_sold" /> {phrase var='core.yes'}</label>
                    </div>
                {else}
                    <div class="radio">
                        <label><input type="radio" {if isset($aVals.is_sold) && $aVals.is_sold == 0}checked="checked"{/if} name="val[is_sold]" value="0" id="is_sold" /> {phrase var='core.no'}</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" {if isset($aVals.is_sold) && $aVals.is_sold == 1}checked="checked"{/if} name="val[is_sold]" value="1" id="is_sold" /> {phrase var='core.yes'}</label>
                    </div>
                {/if}
            </div>
        </div>

        <div class="table">
            <div class="table_left">
                <label for="text">{phrase var='cars.of_zip'}:</label>
            </div>
            <div class="table_right">
                <input value="{value type='input' id='zip'}" class="form-control" type="text" id="zip" name="val[zip]"/>
                <div class="extra_info">{phrase var='cars.zip_code_of_car'}</div>
            </div>
        </div>

		{if Phpfox::isModule('comment') && Phpfox::isModule('privacy') && Phpfox::getUserParam('cars.can_control_comments_on_cars')}
		<div class="table">
			<div class="table_left">
                {phrase var='cars.comment_privacy'}:
			</div>
			<div class="table_right">	
				{module name='privacy.form' privacy_name='privacy_comment' privacy_info='cars.control_who_can_comment_on_this_car' privacy_no_custom=true}
			</div>			
		</div>
		{/if}
        <div class="user_browse_content">
            <div id="browse_custom_fields_popup_holder" style="height: auto !important;">
            {foreach from=$aCustomFields name=customfield item=aCustomField}
            <div class="go_left">
                {if isset($aCustomField.fields)}
                <br />
                <div class="title">
                    {phrase var=$aCustomField.phrase_var_name}
                </div>
                <br />
                {template file='cars.block.custom.foreachcustom'}
                {/if}
            </div>
            {if is_int($phpfox.iteration.customfield / 3)}
            <div class="clear"> </div>
            {/if}
            {/foreach}
            <div class="clear"></div>
            </div>
        </div>
        {if $bIsEdit && !empty($aPhotos)}
        {literal}
            <script type="text/javascript">
                $Behavior.setCarouselSetting = function(){
                    $('#carousel').elastislide({
                        imageW 	: 180,
                        minItems: 5
                    });
                }
            </script>
        {/literal}
        <div class="table">
            <div class="table_left">

                <div id="carousel" class="es-carousel-wrapper">
                    <div class="es-carousel">
                        <ul class="js_drag_drop">
                            {foreach from=$aPhotos item=aPhoto}
                            <li id="js_photo_{$aPhoto.photo_id}">
                                <div style="overflow: hidden;position: absolute;">
                                    <a style="float:left;" onmouseover="$(this).css('opacity', '0.6');" onmouseout="$(this).css('opacity', '1');" href="#" {* class="action_delete js_hover_title" *} onclick = "if (confirm('{phrase var='core.are_you_sure'}')) $.ajaxCall('cars.inLineDeletePhoto', 'photo_id={$aPhoto.photo_id}'); else return false">
                                        {img theme='misc/delete.png' class='v_middle'}
                                    </a>
                                </div>
                                <a {if !empty($aPhoto.is_main)}id="is_main_photo"{/if} href="{img path='cars.url_photo' file=$aPhoto.destination suffix='_1024' return_url=true}"
                                   style="background:url('{img path='cars.url_photo' file=$aPhoto.destination suffix='_1024' return_url=true}') no-repeat;"
                                   class="thickbox" rel="{$aPhoto.photo_id}">
                                    {img path='cars.url_photo' file=$aPhoto.destination suffix='_150' max_width='150'}
                                </a>

                            </li>

                            {/foreach}
                        </ul>

                    </div>
                </div>
            </div>
        </div>
        {/if}
        <div class="table">
            <div class="table_left">
                {phrase var='cars.select_photo_s'}:
            </div>
            <div class="table_right">
                <div id="js_photo_upload_input"></div>

                <div class="extra_info">
                    {if $iMaxFileSize !== null}
                    <br />
                    {phrase var='cars.the_file_size_limit_is_file_size_if_your_upload_does_not_work_try_uploading_a_smaller_picture' file_size=$iMaxFileSize|filesize}
                    {/if}
                </div>
            </div>
            <div id="js_progress_bar"></div>
        </div>
        {if Phpfox::isModule('captcha') && Phpfox::getUserParam('cars.captcha_on_car_add')}
            {module name='captcha.form' captcha_type=cars}
        {/if}
        <div class="table_clear">
			<input onclick="{literal}var isValid = {/literal}{if isset($sNameJsValidation)}{$sNameJsValidation}{else}''{/if};{literal} if(isValid){$('#js_form_loader').css('display', 'block');}else{$('#js_form_loader').css('display', 'none');}{/literal}" type="submit" name="val[{if $bIsEdit}update{else}add{/if}]" value="{if $bIsEdit}{phrase var='cars.update'}{else}{phrase var='cars.add'}{/if}" class="button btn btn-primary" />
            <?php
            printf('<span id="js_form_loader" style="display:none;"><img src="%s" class="v_middle" /></span>', Phpfox::getLib('template')->getStyle('image', 'ajax/small.gif'));
            ?>
			<div class="clear"></div>
		</div>		
	
	</form>

	{if Phpfox::getParam('core.display_required')}
	<div class="table_clear">
		{required} {phrase var='core.required_fields'}
	</div>
	{/if}
</div>

{literal}
<script type="text/javascript">
    $Ready(function(){
        $('#cm_iso_location').removeClass('form-control');
        $('#cm_iso_location').selectize({
            sortField: {
                field: 'text',
                direction: 'asc'
            }
        });
        $('#specie').removeClass('form-control');
        $('#specie').selectize({
            sortField: {
                field: 'text',
                direction: 'asc'
            }
        });

        $('#type').removeClass('form-control');
        $('#type').selectize({
            sortField: {
                field: 'text',
                direction: 'asc'
            }
        });

        $('#mark').selectize({
            sortField: {
                field: 'text',
                direction: 'asc'
            }
        });
        $('#model').selectize({
            sortField: {
                field: 'text',
                direction: 'asc'
            }
        });

        $('#release').removeClass('form-control');
        $('#release').selectize({});
    });
</script>
{/literal}