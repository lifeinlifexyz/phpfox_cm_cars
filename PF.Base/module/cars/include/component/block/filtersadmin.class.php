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
 * @version 		$Id: addfilters.class.php 7021 2014-01-06 19:37:08Z Bolot_Kalil $
 */
class Cars_Component_Block_Filtersadmin extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
        $iSpecieId = $this->getParam('specie_id');
        $iTypeId = $this->getParam('type_id');
        $iMarkId = $this->getParam('mark_id');
        $iModelId = $this->getParam('model_id');

        $this->template()->assign(array(
                'sSelectedSpecieId' => (int) $iSpecieId,
                'aTypes' => !empty($iSpecieId)?Phpfox::getService('cars.cars')->getForEditType(null, $iSpecieId):'',
                'sSelectedTypeId' => (int) $iTypeId,
                'aMarks' => !empty($iTypeId)?Phpfox::getService('cars.cars')->getForEditMark(null, $iTypeId):'',
                'sSelectedMarkId' => (int) $iMarkId,
                'aModels' => !empty($iMarkId)?Phpfox::getService('cars.cars')->getForEditModel(null, $iMarkId):'',
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
		(($sPlugin = Phpfox_Plugin::get('cars.component_block_addfilters_clean')) ? eval($sPlugin) : false);
	}
}

?>
