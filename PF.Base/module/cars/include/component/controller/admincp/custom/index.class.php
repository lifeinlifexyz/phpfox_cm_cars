<?php

defined('PHPFOX') or exit('NO DICE!');
/**
 *
 *
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		CodeMake.Org
 * @package 		Phpfox_Module
 * @version 		$Id: process.class.php 6156 2013-06-26 09:09:14Z Bolot_Kalil $
 */

class Cars_Component_Controller_Admincp_Custom_Index extends Phpfox_Component
{
    public function process()
    {

        Phpfox::getUserParam('cars.can_manage_custom_fields', true);
        $bOrderUpdated = false;

        if (($iDeleteId = $this->request()->getInt('delete')) && Phpfox::getService('cars.custom.group.process')->delete($iDeleteId))
        {
            $this->url()->send('admincp.cars.custom', null, Phpfox::getPhrase('custom.custom_group_successfully_deleted'));
        }

        if (($aFieldOrders = $this->request()->getArray('field')) && Phpfox::getService('cars.custom.process')->updateOrder($aFieldOrders))
        {
            $bOrderUpdated = true;
        }

        if (($aGroupOrders = $this->request()->getArray('group')) && Phpfox::getService('cars.custom.group.process')->updateOrder($aGroupOrders))
        {
            $bOrderUpdated = true;
        }

        if ($bOrderUpdated === true)
        {
            $this->url()->send('admincp.cars.custom', null, Phpfox::getPhrase('custom.custom_fields_successfully_updated'));
        }

        $this->template()->setTitle(Phpfox::getPhrase('custom.manage_custom_fields'))
            ->setBreadcrumb(Phpfox::getPhrase('custom.manage_custom_fields'))
            ->setPhrase(array(
                    'custom.are_you_sure_you_want_to_delete_this_custom_option'
                )
            )
            ->setHeader(array(
                    'admin.js' => 'module_cars',
                    '<script type="text/javascript">$Behavior.custom_set_url = function() { $Core.custom.url(\'' . $this->url()->makeUrl('admincp.cars.custom') . '\'); };</script>',
                    'jquery/ui.js' => 'static_script',
                    '<script type="text/javascript">$Behavior.custom_admin_addSort = function(){$Core.custom.addSort();};</script>'
                )
            )
            ->assign(array(
                    'aGroups' => Phpfox::getService('cars.custom.custom')->getForListing()
                )
            );
    }
}