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
    {phrase var='cars.manage_locations'}
</div>
<table id="js_drag_drop" cellpadding="0" cellspacing="0">
	<tr>
		<th></th>
		<th style="width:20px;"></th>
		<th>{phrase var='cars.manage_locations'}</th>
	</tr>
    {if !empty($aLocations)}
        {foreach from=$aLocations key=iKey item=aLocation}
        <tr class="checkRow{if is_int($iKey/2)} tr{else}{/if}">
            <td class="drag_handle"><input type="hidden" name="val[ordering][{$aLocation.country_iso}]" value="{$aLocation.ordering}" /></td>
            <td class="t_center">
                <a href="#" class="js_drop_down_link" title="Manage">{img theme='misc/bullet_arrow_down.png' alt=''}</a>
                <div class="link_menu">
                    <ul>
                        <li><a href="{url link='admincp.cars.location.add' id=$aLocation.country_iso}">{phrase var='cars.edit'}</a></li>
                        <li><a href="{url link='admincp.cars.location' delete=$aLocation.country_iso}" onclick="return confirm('{phrase var='core.are_you_sure'}');">{phrase var='cars.delete'}</a></li>
                    </ul>
                </div>
            </td>
            <td>{$aLocation.name|convert}({$aLocation.country_iso})</td>
        </tr>
        {/foreach}
    {/if}
</table>