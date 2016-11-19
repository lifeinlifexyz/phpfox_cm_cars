<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		CodeMake.Org
 * @package 		Phpfox_Module
 * @version 		$Id: filter.class.php 7021 2014-01-06 19:37:08Z Bolot_Kalil $
 */
class Cars_Component_Block_Frontslider extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		Phpfox::getLib('setting')->setParam('cars.dir_photo', Phpfox::getParam('core.dir_pic') . 'cars'.PHPFOX_DS);
		Phpfox::getLib('setting')->setParam('cars.url_photo', Phpfox::getParam('core.url_pic') . 'cars/');
        $aCars = Phpfox::getService('cars.process')->getMostLikedCars(Phpfox::getParam('cars.limit_to_show_on_front_page'));
        
        $this->template()->assign(array('aCars'=>$aCars));
        return 'block';
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('cars.component_block_cars_clean')) ? eval($sPlugin) : false);
	}
}

?>
