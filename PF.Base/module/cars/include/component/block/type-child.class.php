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
 * @package 		Phpfox_Component
 * @version 		$Id: country-child.class.php 2525 2011-04-13 18:03:20Z Bolot_Kalil $
 */
class Cars_Component_Block_Type_Child extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$iTypeId   = $this->getParam('type_id');
		$iMarkId   = $this->getParam('mark_id');
        $iModelId  = $this->getParam('model_id');

		$this->template()->assign(array(
                'aTypes' => Phpfox::getService('cars.cars')->getTypes(true),
                'sSelectedTypeId' => (int) $iTypeId,
				'aMarks' => !empty($iTypeId)?Phpfox::getService('cars.cars')->getMarks($iTypeId):'',
				'sSelectedMarkId' => (int) $iMarkId,
                'aModels' => !empty($iMarkId)?Phpfox::getService('cars.cars')->getModels($iMarkId):'',
                'sSelectedModelId' => (int) $iModelId
			)
		);

	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('cars.component_block_country_child_clean')) ? eval($sPlugin) : false);
	}
}

?>