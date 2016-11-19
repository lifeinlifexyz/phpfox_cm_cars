<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Bolot_Kalil
 * @package 		Phpfox
 * @version 		$Id: add.html.php 5387 2013-02-19 12:19:37Z Bolot_Kalil $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<div class="table_header">
    {phrase var='cars.filter_details'}
</div>
<form method="post" action="{url link='admincp.cars.filter.add'}">
    <input type="hidden" name="from" value="{$iFrom}"/>
    {if !empty($bIsEdit)}
        {if !empty($iDoubleSubtEditId)}
            <input type="hidden" name="type" value="model"/>
            <input type="hidden" name="doublesub" value="{$iDoubleSubtEditId}"/>
        {elseif !empty($iSubtEditId)}
            <input type="hidden" name="type" value="mark"/>
            <input type="hidden" name="sub" value="{$iSubtEditId}"/>
        {elseif !empty($iEditId)}
            <input type="hidden" name="type" value="type"/>
            <input type="hidden" name="id" value="{$iEditId}"/>
        {/if}
    {else}

        {if isset($aTypes)}
        {literal}
        <script type="text/javascript">
            $Behavior.onLoadIndex = function(){
                $('#type_container select').change(function(){
                    $("#mark_container").remove();
                    $("#model_container").remove();
                    if ($('#type_container select option:selected').val() > 0){
                        $('#js_type_loader').css({'visibility':'visible'});
                        $.ajaxCall('cars.displayAddFiltersAdmin', 'type_id='+$('#type_container select option:selected').val());
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
                <select id="type" name="val[type]" style="width:150px;" id="type">
                    <option value="0">{phrase var='cars.none'}</option>

                    {foreach from=$aTypes item=aType}
                    {if $bIsEdit}
                    <option {if isset($aForms.type_id) && $aForms.type_id == $aType.type_id}selected="selected"{/if} value="{$aType.type_id}">{$aType.title}</option>
                    {else}
                    <option {if isset($aVals.type) && $aVals.type == $aType.type_id}selected="selected"{/if} value="{$aType.type_id}">{$aType.title}</option>
                    {/if}
                    {/foreach}
                </select>
                <?php
                printf('<span id="js_type_loader" style="visibility:hidden;"><img src="%s" class="v_middle" /></span>', Phpfox::getLib('template')->getStyle('image', 'ajax/small.gif'));
                ?>
            </div>
        </div>
        {/if}
        {if !empty($aMarks)}
            {template file='cars.block.filtersadmin'}
        {/if}
    {/if}
    <div class="table">
		<div class="table_left">
            {phrase var='cars.name'}:
		</div>
		<div class="table_right">
			<input type="text" name="val[name]" value="{if !empty($aForms)}{$aForms.title}{/if}" size="30" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="table_clear">
		<input type="submit" value="{phrase var='cars.submit'}" class="button" />
	</div>
</form>