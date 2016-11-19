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
class Cars_Component_Controller_Admincp_App_Index extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */

	public function process()
	{
        $sExport = $this->request()->get('export');
        if (!empty($sExport) && $sExport == 'do'){
            $bIsExported = Phpfox::getService('cars.app.process')->getSetting();
            if($bIsExported){
                $this->url()->send('current', array(), Phpfox::getPhrase('cars.fields_successfully_exported'));
            }

        }
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean(){

		(($sPlugin = Phpfox_Plugin::get('cars.component_controller_admincp_app_index_clean')) ? eval($sPlugin) : false);

	}
}

?>