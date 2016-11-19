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
class Cars_Component_Block_Cars extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
        Phpfox::getLib('setting')->setParam('cars.dir_photo', Phpfox::getParam('core.dir_pic') . 'cars'.PHPFOX_DS);
        Phpfox::getLib('setting')->setParam('cars.url_photo', Phpfox::getParam('core.url_pic') . 'cars/');
        $bIsAjax = $this->getParam('isAjax');
        if (empty($bIsAjax)){
            $iCarId = Phpfox::getLib('request')->get('req2');
            if ($iCarId>0){
                $aCars = Phpfox::getService('cars.cars')->getRecomendedCars($iCarId);
            }else{
                $aCars = Phpfox::getService('cars.process')->getLatestCars();
            }
        }else{
            $aCars = $this->getParam('aCars');
            $iCarId = $this->getParam('carid');
        }

        foreach($aCars as $iKey=>&$aCar){

            $aMark = Phpfox::getService('cars.cars')->getMark($aCar['type_id'], $aCar['mark_id']);
            $aModel = Phpfox::getService('cars.cars')->getModel($aCar['mark_id'], $aCar['model_id']);

            $aCar['mark_id'] = !empty($aMark['title'])?$aMark['title']:'';
            $aCar['model_id'] = !empty($aModel['title'])?$aModel['title']:'';

        }
        $this->template()
            ->assign(
            array(
                'sHeader' => $iCarId>0?Phpfox::getPhrase('cars.recommended'):Phpfox::getPhrase('cars.last_added_cars'),
                'aCars'   => $aCars,
                'iCarId'  => $iCarId,
                'isAjax'  => $bIsAjax
            )
        );
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
