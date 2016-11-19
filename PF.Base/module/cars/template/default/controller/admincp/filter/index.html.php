<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Bolot_Kalil
 * @package 		Phpfox
 * @version 		$Id: index.html.php 3332 2011-10-20 12:50:29Z Bolot_Kalil $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<div class="table_header">
	{phrase var='cars.manage_filters'}
</div>
<table id="js_drag_drop" cellpadding="0" cellspacing="0">
	<tr>
		<th></th>
		<th style="width:20px;"></th>
		<th>{phrase var='cars.manage_filters'}</th>
	</tr>
    {if empty($bSub) && empty($bDoubleSub)}
        {foreach from=$aTypes key=iKey item=aType}
        <tr class="checkRow{if is_int($iKey/2)} tr{else}{/if}">
            <td class="drag_handle"><input type="hidden" name="val[ordering][{$aType.type_id}]" value="{$aType.ordering}" /></td>
            <td class="t_center">
                <a href="#" class="js_drop_down_link" title="Manage">{img theme='misc/bullet_arrow_down.png' alt=''}</a>
                <div class="link_menu">
                    <ul>
                        <li><a href="{url link='admincp.cars.filter.add' id=$aType.type_id}">{phrase var='cars.edit'}</a></li>
                        <li><a href="{url link='admincp.cars.filter' sub={$aType.type_id}">{phrase var='cars.manage_sub_filters'}</a></li>
                        <li><a href="{url link='admincp.cars.filter' id=$aType.type_id delete=$aType.type_id}" onclick="return confirm('{phrase var='core.are_you_sure'}');">{phrase var='cars.delete'}</a></li>
                    </ul>
                </div>
            </td>
            <td>{$aType.title|convert}</td>
        </tr>
        {/foreach}
    {elseif !empty($bSub)}
        {foreach from=$aMarks key=iKey item=aMark}
        <tr class="checkRow{if is_int($iKey/2)} tr{else}{/if}">
            <td class="drag_handle"><input type="hidden" name="val[ordering][{$aMark.mark_id}]" value="{$aMark.ordering}" /></td>
            <td class="t_center">
                <a href="#" class="js_drop_down_link" title="Manage">{img theme='misc/bullet_arrow_down.png' alt=''}</a>
                <div class="link_menu">
                    <ul>
                        <li><a href="{url link='admincp.cars.filter.add' sub=$aMark.mark_id from=$iEditId}">{phrase var='cars.edit'}</a></li>
                        <li><a href="{url link='admincp.cars.filter' doublesub=$aMark.mark_id from=$iEditId}">{phrase var='cars.manage_sub_filters'}</a></li>
                        <li><a href="{url link='admincp.cars.filter' sub=$aMark.mark_id delete=$aMark.mark_id from=$iEditId}" onclick="return confirm('{phrase var='core.are_you_sure'}');">{phrase var='cars.delete'}</a></li>
                    </ul>
                </div>
            </td>
            <td>{$aMark.title|convert}</td>
        </tr>
        {/foreach}
    {elseif !empty($bDoubleSub)}
        {foreach from=$aModels key=iKey item=aModel}
        <tr class="checkRow{if is_int($iKey/2)} tr{else}{/if}">
            <td class="drag_handle"><input type="hidden" name="val[ordering][{$aModel.model_id}]" value="{$aModel.ordering}" /></td>
            <td class="t_center">
                <a href="#" class="js_drop_down_link" title="Manage">{img theme='misc/bullet_arrow_down.png' alt=''}</a>
                <div class="link_menu">
                    <ul>
                        <li><a href="{url link='admincp.cars.filter.add' doublesub=$aModel.model_id from=$iEditId}">{phrase var='cars.edit'}</a></li>
                        <li><a href="{url link='admincp.cars.filter' doublesub=$aModel.model_id delete=$aModel.model_id from=$iEditId}" onclick="return confirm('{phrase var='core.are_you_sure'}');">{phrase var='cars.delete'}</a></li>
                    </ul>
                </div>
            </td>
            <td>{$aModel.title|convert}</td>
        </tr>
        {/foreach}
    {/if}
</table>