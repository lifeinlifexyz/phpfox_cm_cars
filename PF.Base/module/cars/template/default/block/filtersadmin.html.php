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

{if !empty($aTypes)}
<div class="table" id="type_container">
    <div class="table_left">{required}<?php echo(Phpfox::getPhrase('cars.type'));?>:</div>
    <div class="table_right">
        <select onchange="$('#js_type_loader').css('visibility', 'visible');$('#mark_container').remove();
        $.ajaxCall('cars.displayAddFiltersAdmin', 'specie_id='+$('#specie_container select option:selected').val()+'&type_id='+$('#type_container select option:selected').val());" id="type" name="val[type]" style="width:150px;">
            {if isset($bIsEdit) && !$bIsEdit}
            <option value="0">{phrase var='cars.none'}</option>
            {/if}
            {foreach from=$aTypes item=aType}
            {if isset($bIsEdit) && $bIsEdit && isset($aForms)}
            <option {if !empty($aForms.type_id) && $aForms.type_id == $aMark.type_id}selected="selected"{/if} value="{$aType.type_id}">{$aType.title}</option>
            {else}
            <option {if !empty($sSelectedTypeId) && $sSelectedTypeId == $aType.type_id}selected="selected"{/if} value="{$aType.type_id}">{$aType.title}</option>
            {/if}

            {/foreach}
        </select>
        <span id="js_type_loader" style="visibility:hidden;">
            {img theme='ajax/small.gif'}
        </span>
    </div>
</div>
{/if}

{if !empty($aMarks)}
<div class="table" id="mark_container">
    <div class="table_left">{required}<?php echo(Phpfox::getPhrase('cars.mark'));?>:</div>
    <div class="table_right">
        <select onchange="$('#js_mark_loader').css('visibility', 'visible');
        $.ajaxCall('cars.displayAddFiltersAdmin', 'specie_id='+$('#specie_container select option:selected').val()+'&type_id='+$('#type_container select option:selected').val()+'&mark_id='+$('#mark_container select option:selected').val());" id="mark" name="val[mark]" style="width:150px;">
            {if isset($bIsEdit) && !$bIsEdit}
            <option value="0">{phrase var='cars.none'}</option>
            {/if}
            {foreach from=$aMarks item=aMark}
                {if isset($bIsEdit) && $bIsEdit && isset($aForms)}
                    <option {if !empty($aForms.mark_id) && $aForms.mark_id == $aMark.mark_id}selected="selected"{/if} value="{$aMark.mark_id}">{$aMark.title}</option>
                {else}
                    <option {if !empty($sSelectedMarkId) && $sSelectedMarkId == $aMark.mark_id}selected="selected"{/if} value="{$aMark.mark_id}">{$aMark.title}</option>
                {/if}

            {/foreach}
        </select>
        <span id="js_mark_loader" style="visibility:hidden;">
            {img theme='ajax/small.gif'}
        </span>
    </div>
</div>
{/if}
{*
{if !empty($aModels)}
<div class="table" id="model_container">
    <div class="table_left">{required}<?php echo(Phpfox::getPhrase('cars.model'));?></div>
    <div class="table_right">
        <select name="val[model]" style="width:150px;">
            {if isset($bIsEdit) && !$bIsEdit}
            <option value="0">{phrase var='cars.none'}</option>
            {/if}
            {foreach from=$aModels item=aModel}
                {if isset($bIsEdit) && $bIsEdit && isset($aForms)}
                    <option {if !empty($aForms.model_id) && $aForms.model_id == $aModel.model_id}selected="selected"{/if} value="{$aModel.model_id}">{$aModel.title}</option>
                {else}
                    <option {if !empty($sSelectedModelId) && $sSelectedModelId == $aModel.model_id}selected="selected"{/if} value="{$aModel.model_id}">{$aModel.title}</option>
                {/if}
            {/foreach}
        </select>
    </div>
</div>
{/if}
*}