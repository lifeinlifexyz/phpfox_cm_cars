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
class Cars_Component_Controller_Admincp_Index extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
        if ($aDeleteIds = $this->request()->getArray('id'))
        {
            if (Phpfox::getService('cars.cars')->deleteMultiple($aDeleteIds))
            {
                $this->url()->send('admincp.cars', null, Phpfox::getPhrase('cars.car_successfully_deleted'));
            }
        }

        $iPage = $this->request()->getInt('page');

        $aPages = array(10, 20, 40, 60);
        $aDisplays = array();
        foreach ($aPages as $iPageCnt)
        {
            $aDisplays[$iPageCnt] = Phpfox::getPhrase('core.per_page', array('total' => $iPageCnt));
        }

        $aSorts = array(
            'c.title' => Phpfox::getPhrase('cars.title'),
            'c.time_stamp' => Phpfox::getPhrase('cars.latest_added'),
            'c.total_comment' => Phpfox::getPhrase('cars.most_liked'),
            'c.total_like' => Phpfox::getPhrase('cars.most_discussed')
        );

        $sDefaultOrderName = 'c.car_id';
        $sDefaultSort = 'DESC';
        $aCurrencies = Phpfox::getService('core.currency')->get();
        foreach ($aCurrencies as $iKey => $aCurrency)
        {
            $aCurrencies[$iKey] = $iKey;
        }
        $aFilters = array(
//            'approved' => array(
//                'type' => 'select',
//                'options' => array('1'=>Phpfox::getPhrase('core.yes'), '0'=>Phpfox::getPhrase('core.no')),
//                'default' => 10,
//                'style' => 'width:150px;',
//            ),
            'display' => array(
                'type' => 'select',
                'options' => $aDisplays,
                'default' => 10,
                'style' => 'width:150px;',
            ),
            'sort' => array(
                'type' => 'select',
                'options' => $aSorts,
                'default' => $sDefaultOrderName,
                'style' => 'width:150px;'
            ),
            'sort_by' => array(
                'type' => 'select',
                'options' => array(
                    'DESC' => Phpfox::getPhrase('core.descending'),
                    'ASC' => Phpfox::getPhrase('core.ascending')
                ),
                'default' => $sDefaultSort,
                'style' => 'width:150px;'
            ),
            'title' => array(
                'type' => 'input:text',
                'size' => 20,
                'class' => 'txt_input',
//                'search' => 'AND c.title LIKE \'%[VALUE]%\''
            ),
            'type' => array(
                'type' => 'select',
                'options' => Phpfox::getService('cars.cars')->getTypes(true),
                'search' => 'AND c.type_id = \'[VALUE]\'',
                'add_any' => true,
                'style' => 'width:150px;',
                'id' => 'type'
            ),
            'from' => array(
                'type' => 'select',
                'options' => Phpfox::getService('cars.cars')->getReleaseYears(),
                'add_any' => true,
                'style'   => 'width: 68px;margin-right: 2px;',
                'search'  => 'AND c.release_year >= [VALUE]'
            ),
            'to' => array(
                'type' => 'select',
                'options' => Phpfox::getService('cars.cars')->getReleaseYears(),
                'add_any' => true,
                'style' => 'width: 67px;margin-left: 1px;',
                'search'  => 'AND c.release_year <= [VALUE]'
            ),
            'location' => array(
                'type' => 'select',
                'options' => Phpfox::getService('cars.location')->get(),
                'search' => 'AND c.location_iso = \'[VALUE]\'',
                'add_any' => true,
                'style' => 'width:150px;',
                'id' => 'country_iso'
            ),
            'priceFrom' => array(
                'type' => 'input:text',
                'class' => 'txt_input',
                'size' => 5,
                //  'search'  => 'AND c.price >= [VALUE]'
            ),
            'priceTo' => array(
                'type' => 'input:text',
                'class' => 'txt_input',
                'size' => 5,
                // 'search'  => 'AND c.price <= [VALUE]'
            )/*,
            'toPrint' => array(
                'type' => 'select',
                'options' => array('0'=>Phpfox::getPhrase('cars.no'), '1'=>Phpfox::getPhrase('cars.yes')),
                'add_any' => true,
                'style' => 'width: 67px;margin-left: 1px;',
                //'search'  => 'AND c.to_print = \'[VALUE]\''
            )*/,
            'phone_number' => array(
                'type' => 'input:text',
                'class' => 'txt_input',
                'size' => 20,
                'search'  => 'AND c.phone_number LIKE \'%[VALUE]%\''
            ),
            'currencies' => array(
                'type' => 'select',
                'options' => $aCurrencies,
                'search' => 'AND c.currency = \'[VALUE]\'',
                'id' => 'cm_currencies'
            ),
            'zip' => array(
                'type' => 'input:text',
            )
        );

        $oFilter = Phpfox::getLib('search')->set(array(
                'type' => 'browse',
                'filters' => $aFilters,
                'search' => 'search'
            )
        );
        if ((int)($iPriceFrom = $oFilter->get('priceFrom')) || ($iPriceFrom = $this->request()->getInt('priceFrom')))
        {
            $oFilter->setCondition('AND c.price >= ' . $iPriceFrom. '' );
        }
        if ((int)($iPriceTo = $oFilter->get('priceTo')) || ($iPriceTo = $this->request()->getInt('priceTo')))
        {
            $oFilter->setCondition('AND c.price <= ' . $iPriceTo .'' );
        }
        $sToPrint = $oFilter->get('toPrint');
        if ($sToPrint != '')
        {
            //var_dump($sToPrint);
            $oFilter->setCondition('AND c.to_print = ' . (int)$sToPrint .'' );
        }

        $sApproved = $oFilter->get('approved');
        if ($sApproved != '')
        {
            $oFilter->setCondition('AND c.view_id = ' . (int)$sApproved .'' );
        }

        $sIsFeatured = $oFilter->get('is_featured');
        if ($sIsFeatured != '')
        {
            $oFilter->setCondition('AND c.is_featured = ' . (int)$sIsFeatured .'' );
        }
        if ($sTitle = $oFilter->get('title'))
        {
            if (preg_match('/^\#\d{1,}$/', $sTitle)){
                $oFilter->setCondition('AND c.car_id = ' . (int)str_replace('#', '',$sTitle) .'' );
            }elseif (Phpfox::getLib('parse.input')->convert($sTitle) != Phpfox::getLib('parse.input')->convert(Phpfox::getPhrase('cars.title_example'))){
                $oFilter->setCondition('AND c.title LIKE \'%'.$sTitle.'%\'');
            }

        }
        $iPageSize = $oFilter->getDisplay();
        list($iCnt, $aCars) = Phpfox::getService('cars.browse')->conditions($oFilter->getConditions())
            ->sort($oFilter->getSort())
            ->page($oFilter->getPage())
            ->limit($iPageSize)
            ->get();

        if(!empty($aCars)){
            foreach($aCars as $iKey=>$aCar){
                $aType = Phpfox::getService('cars.cars')->getForEditType($aCar['type_id']);
                $sType = !empty($aType['title'])?$aType['title']:'';
                $aMark = Phpfox::getService('cars.cars')->getForEditMark($aCar['mark_id']);
                $sMark = !empty($aMark['title'])?$aMark['title']:'';
                $aModel = Phpfox::getService('cars.cars')->getForEditModel($aCar['model_id']);
                $sModel = !empty($aModel['title'])?$aModel['title']:'';
                $aCars[$iKey]['type_id'] = !empty($sType)?$sType:$aCar['type_id'];
                $aCars[$iKey]['mark_id'] = !empty($sMark)?$sMark:$aCar['mark_id'];
                $aCars[$iKey]['model_id']= !empty($sModel)?$sModel:$aCar['model_id'];
                $aCars[$iKey]['location_name'] = Phpfox::getService('cars.location')->getLocation($aCar['location_iso']);
                $aCars[$iKey]['date'] = date(Phpfox::getParam('cars.car_details_time_stamp'), $aCar['time_stamp']);
            }
        }

        Phpfox::getLib('pager')->set(array('page' => $iPage, 'size' => $iPageSize, 'count' => $iCnt));

        $this->template()->setTitle(Phpfox::getPhrase('cars.export'))
            ->setBreadcrumb(Phpfox::getPhrase('cars.export'), $this->url()->makeUrl('admincp.cars'))
            ->assign(array(
                    'aCars'       => $aCars,
                    'sToPrint'    => $sToPrint,
                    'sApproved'   => $sApproved,
                    'sIsFeatured' => $sIsFeatured
                )
            )
            ->setHeader('cache', array(
                    'quick_edit.js' => 'static_script',
                    'index.js' => 'module_cars',
                    'index.css' => 'module_cars'
                )
            )->setPhrase(array('cars.from', 'cars.to', 'cars.title_example'));
    }
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean(){

		(($sPlugin = Phpfox_Plugin::get('cars.component_controller_admincp_index_clean')) ? eval($sPlugin) : false);

	}
}

?>