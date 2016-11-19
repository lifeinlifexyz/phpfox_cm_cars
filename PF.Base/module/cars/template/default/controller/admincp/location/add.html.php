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
    {phrase var='cars.location'}
</div>
<form method="post" action="{url link='admincp.cars.location.add'}">
    {if !empty($bIsEdit)}
        <input type="hidden" name="id" value="{$iEditId}"/>
    {/if}
    <div class="table">
		<div class="table_left">
            {phrase var='cars.name'}:
		</div>
		<div class="table_right">
			<input type="text" name="val[name]" value="{if !empty($aForms)}{$aForms.name}{/if}" size="30" />
		</div>
		<div class="clear"></div>
	</div>
    <div class="table">
        <div class="table_left">
            {phrase var='cars.iso_standart'}:
        </div>
        <div class="table_right">
            <input type="text" name="val[country_iso]" value="{if !empty($aForms)}{$aForms.country_iso}{/if}" size="30" />
        </div>
        <div class="clear"></div>
    </div>
	<div class="table_clear">
		<input type="submit" value="{phrase var='cars.submit'}" class="button" />
	</div>
</form>