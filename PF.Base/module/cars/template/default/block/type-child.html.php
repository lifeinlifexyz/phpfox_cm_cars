<?php
/**
 * [PHPFOX_HEADER]
 *
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		CodeMake.Org
 * @package 		Phpfox
 * @version 		$Id: country-child.html.php 982 2009-09-16 08:11:36Z Bolot_Kalil $
 */

defined('PHPFOX') or exit('NO DICE!');
?>
<div id="mark_container">
    <div><?php echo(Phpfox::getPhrase('cars.mark'));?>:</div>
    <div class="table_right">
    <select onchange="$('#js_mark_loader').css('display', 'block');$.ajaxCall('cars.displayFilters', 'type_id='+$('#type_container select option:selected').val()+'&mark_id='+$('#mark_container select option:selected').val());" id="mark" name="search[mark]">
        <option value=""><?php echo(Phpfox::getPhrase('core.any'));?></option>
        {if !empty($aMarks)}
            {foreach from=$aMarks item=aMark}
            <option {if isset($aMark.mark_id) && !empty($sSelectedMarkId) && $sSelectedMarkId == $aMark.mark_id}selected="selected"{/if} value="{$aMark.mark_id}">{$aMark.title}</option>
            {/foreach}
        {/if}
    </select>
    <span id="js_mark_loader" style="display:none;">
        {img theme='ajax/small.gif'}
    </span>

    </div>
</div>
<div id="model_container">
    <div><?php echo(Phpfox::getPhrase('cars.model'));?></div>
    <div class="table_right">
        <select name="search[model]" id="model">
        <option value=""><?php echo(Phpfox::getPhrase('core.any'));?></option>
        {if !empty($aModels)}
            {foreach from=$aModels item=aModel}
            <option {if !empty($sSelectedModelId) && $sSelectedModelId == $aModel.model_id}selected="selected"{/if} value="{$aModel.model_id}">{$aModel.title}</option>
            {/foreach}
        {/if}
        </select>
    </div>
</div>