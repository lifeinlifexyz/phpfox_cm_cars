<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Bolot_Kalil
 * @package 		Phpfox_Module
 * @version 		$Id: index.class.php 6113 2013-06-21 13:58:40Z Bolot_Kalil $
 */
class Cars_Component_Controller_Admincp_Location_Index extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
        $iEditId = null;
        if (($iDelete = $this->request()->get('delete')))
        {
            if (Phpfox::getService('cars.location.process')->delete($iDelete))
            {
                $this->url()->send('admincp.cars.location', null, Phpfox::getPhrase('cars.location_successfully_deleted'));
            }
        }

		$this->template()->setTitle(Phpfox::getPhrase('cars.manage_locations'))
			->setBreadcrumb(Phpfox::getPhrase('cars.manage_locations'))
			->setHeader(array(
					'drag.js' => 'static_script',
					'<script type="text/javascript">$Behavior.coreDragInit = function() { Core_drag.init({table: \'#js_drag_drop\', ajax: \'' . 'cars.locationOrdering' . '\'}); }</script>'
				)
			)			
			->assign(array(
                    'iEditId' => $iEditId,
					'aLocations' => Phpfox::getService('cars.location')->getForEdit()
				)
			);
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean(){

		(($sPlugin = Phpfox_Plugin::get('cars.component_controller_admincp_location_index_clean')) ? eval($sPlugin) : false);

	}
}

?>