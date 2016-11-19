<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		CodeMake.Org
 * @package  		Module_Photo
 * @version 		$Id: menu.html.php 7088 2014-02-04 15:37:30Z Fern $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{if (Phpfox::getUserParam('cars.can_edit_own_car') && $aForms.user_id == Phpfox::getUserId()) || Phpfox::isAdmin()}
<li><a href="{url link='cars.add' id=$aForms.car_id}">{phrase var='cars.edit_this_car'}</a></li>
{/if}
{if empty($aForms.is_main) && !empty($aForms.destination) && Phpfox::getUserId() == $aForms.user_id}
<li id="js_set_as_main_photo"><a href="#" onclick="$.ajaxCall('cars.setAsMainPhoto', 'photo_id={$aForms.photo_id}');">{phrase var='cars.set_as_main'}</a></li>
{/if}
{if Phpfox::isAdmin() && isset($aForms.car_id) && isset($aForms.is_featured)}
    <li id="js_unset_as_featured_{$aForms.car_id}" {if empty($aForms.is_featured)}style="display:none;"{/if}><a href="#" onclick="$.ajaxCall('cars.unsetAsFeatured', 'car_id={$aForms.car_id}');">{phrase var='cars.unset_featured'}</a></li>
    <li id="js_set_as_featured_{$aForms.car_id}" {if !empty($aForms.is_featured)}style="display:none;"{/if}><a href="#" onclick="$.ajaxCall('cars.setAsFeatured', 'car_id={$aForms.car_id}');">{phrase var='cars.set_as_featured'}</a></li>
{/if}
{if (Phpfox::getUserParam('cars.can_delete_own_car') && $aForms.user_id == Phpfox::getUserId()) || Phpfox::isAdmin()}
<li class="item_delete"><a href="{url link='cars.delete' id=$aForms.car_id}" class="sJsConfirm">{phrase var='cars.delete_this_car'}</a></li>
{/if}