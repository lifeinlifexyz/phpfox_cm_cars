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
<div class="table" id="mark_container">
    <span class="table_left">{required}<?php echo(Phpfox::getPhrase('cars.mark'));?>:</span>
    <div class="table_right">

        <select onchange="$('#js_mark_loader').css('visibility', 'visible');
        $.ajaxCall('cars.displayAddFilters', 'type_id='+$('#type_container select option:selected').val()+'&mark_id='+$('#mark_container select option:selected').val());" id="mark" name="val[mark]">
            <option value=""><?php echo(Phpfox::getPhrase('core.any'));?></option>

                {if isset($bIsEdit) && $bIsEdit && isset($aForms)}
                    {foreach from=$aMarksEdit item=aMark}
                    <option {if !empty($aForms.mark_id) && $aForms.mark_id == $aMark.mark_id}selected="selected"{/if} value="{$aMark.mark_id}">{$aMark.title}</option>
                    {/foreach}
                {elseif !empty($aMarks)}
                    {foreach from=$aMarks item=aMark}
                    <option {if !empty($sSelectedMarkId) && $sSelectedMarkId == $aMark.mark_id}selected="selected"{/if} value="{$aMark.mark_id}">{$aMark.title}</option>
                    {/foreach}
                {/if}

        </select>
        <span id="js_mark_loader" style="visibility:hidden;">
            {img theme='ajax/small.gif'}
        </span>
        <div class="extra_info">{phrase var='cars.provide_car_mark'}</div>
    </div>
</div>

<div class="table" id="model_container">
    <span class="table_left"><?php echo(Phpfox::getPhrase('cars.model'));?></span>
    <div class="table_right">
        <select name="val[model]" id="model">
            <option value=""><?php echo(Phpfox::getPhrase('core.any'));?></option>
            {if isset($bIsEdit) && $bIsEdit && isset($aForms)}
            {foreach from=$aModelsEdit item=aModel}
                <option {if !empty($aForms.model_id) && $aForms.model_id == $aModel.model_id}selected="selected"{/if} value="{$aModel.model_id}">{$aModel.title}</option>
            {/foreach}
            {elseif !empty($aModels)}
            {foreach from=$aModels item=aModel}
                <option {if !empty($sSelectedModelId) && $sSelectedModelId == $aModel.model_id}selected="selected"{/if} value="{$aModel.model_id}">{$aModel.title}</option>
            {/foreach}
            {/if}
        </select>
        <div class="extra_info">{phrase var='cars.provide_car_model'}</div>
    </div>
</div>