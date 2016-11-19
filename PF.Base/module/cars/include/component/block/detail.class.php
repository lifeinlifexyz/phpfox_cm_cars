<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * Display the image details when viewing an image.
 *
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		CodeMake.Org
 * @package  		Module_Cars
 * @version 		$Id: detail.class.php 5857 2013-05-10 08:05:37Z Bolot_Kalil $
 */
class Cars_Component_Block_Detail extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
        Phpfox::getLib('setting')->setParam('cars.dir_photo', Phpfox::getParam('core.dir_pic') . 'cars'.PHPFOX_DS);
        Phpfox::getLib('setting')->setParam('cars.url_photo', Phpfox::getParam('core.url_pic') . 'cars/');
        $aCar = $this->getParam('aCar');

		if ($aCar === null)
		{
			return false;
		}

		$aInfo = array(
            Phpfox::getPhrase('cars.title') => $aCar['title'],
            Phpfox::getPhrase('cars.location') => Phpfox::getService('cars.location')->getLocation($aCar['location_iso']),
		);
		if ($aType = Phpfox::getService('cars.cars')->getType($aCar['type_id'])){
            $aInfo[Phpfox::getPhrase('cars.type')] = isset($aType['title'])?$aType['title']:'';
        }
        if ($aMark = Phpfox::getService('cars.cars')->getMark($aCar['type_id'], $aCar['mark_id'])){
            $aInfo[Phpfox::getPhrase('cars.mark')] = isset($aMark['title'])?$aMark['title']:'';
        }

        if ($aModel = Phpfox::getService('cars.cars')->getModel($aCar['mark_id'], $aCar['model_id'])){
            $aInfo[Phpfox::getPhrase('cars.model')] = isset($aModel['title'])?$aModel['title']:'';
        }
        $aInfo[Phpfox::getPhrase('cars.phone_number')] = $aCar['phone_number'];
        $aInfo[Phpfox::getPhrase('cars.release_year')] = $aCar['release_year'];
        $aInfo[Phpfox::getPhrase('cars.price')] = $aCar['price'].' '.$aCar['currency'];
        $aInfo[Phpfox::getPhrase('cars.added')] = '<span itemprop="dateCreated">' . Phpfox::getTime(Phpfox::getParam('cars.car_details_time_stamp'), $aCar['time_stamp']) . '</span>';
        $aInfo[Phpfox::getPhrase('cars.comments')] = $aCar['total_comment'];
        $aInfo[Phpfox::getPhrase('cars.total_like')] = $aCar['total_like'];
        $aInfo[Phpfox::getPhrase('cars.views')] = '<span itemprop="interactionCount">' . $aCar['total_view'] . '</span>';

        $aCustomFields = Phpfox::getService('cars.custom')->getForEdit(array('cars_advanced_filter'), $aCar['car_id'], null, false, $aCar['car_id']);
        foreach($aCustomFields as $iIndex=>$aCustom){

            if ($aCustom['var_type'] == 'select'){

                foreach ($aCustom['options'] as $aOption){
                    if (!isset($aCustomFields[$iIndex]['isHas']) && isset($aOption['value']) && isset($aOption['selected']) && $aOption['selected'] == true){
                        $aCustomFields[$iIndex]['isHas'] = true;

                    }
                }
            }elseif ($aCustom['var_type'] == 'multiselect'){
                foreach ($aCustom['options'] as $aOption){
                    if (!isset($aCustomFields[$iIndex]['isHas']) && isset($aOption['value']) && isset($aOption['selected']) && $aOption['selected'] == true){
                        $aCustomFields[$iIndex]['isHas'] = true;
                    }
                }
            }elseif ($aCustom['var_type'] == 'radio'){

                foreach ($aCustom['options']  as $aOption){
                    if (!isset($aCustomFields[$iIndex]['isHas']) && isset($aOption['selected']) && $aOption['selected'] == true){
                        $aCustomFields[$iIndex]['isHas'] = true;

                    }
                }

            }elseif ($aCustom['var_type'] == 'checkbox'){

                foreach ($aCustom['options']  as $aOption){
                    if (!isset($aCustomFields[$iIndex]['isHas']) && isset($aOption['selected']) && $aOption['selected'] == true){
                        $aCustomFields[$iIndex]['isHas'] = true;
                    }
                }
            }
        }

		foreach ($aInfo as $sKey => $mValue)
		{
			if (empty($mValue))
			{
				unset($aInfo[$sKey]);
			}
		}

		$this->template()
            ->setHeader(array(
                'detail.css' => 'module_cars'
            ))
            ->assign(array(
				'sHeader' => Phpfox::getPhrase('cars.car_details'),
				'aCarDetails' => $aInfo,
                'aSettings' => $aCustomFields
//				'sUrlPath' => (preg_match("/\{file\/pic\/(.*)\/(.*)\.jpg\}/i", $aCar['destination'], $aMatches) ? Phpfox::getParam('core.path') . str_replace(array('{', '}'), '', $aMatches[0]) : (($aCar['server_id'] && Phpfox::getParam('core.allow_cdn')) ? Phpfox::getLib('cdn')->getUrl(Phpfox::getParam('cars.dir_photo') . sprintf($aCar['destination'], '_500'), $aCar['server_id']) : Phpfox::getParam('cars.dir_photo') . sprintf($aCar['destination'], '_500')))
			)
		);

		// return 'block';
	}

	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('cars.component_block_detail_clean')) ? eval($sPlugin) : false);
	}
}

?>