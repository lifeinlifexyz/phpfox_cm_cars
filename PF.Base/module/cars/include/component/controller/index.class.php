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
 * @package  		Module_Cars
 * @version 		$Id: index.class.php 7264 2014-04-09 21:00:49Z Fern $
 */
class Cars_Component_Controller_Index extends Phpfox_Component
{
    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
        if ($sPlugin = Phpfox_Plugin::get('cars.controller_index_process_begin')) {return eval($sPlugin);}
        Phpfox::getLib('setting')->setParam('cars.dir_photo', Phpfox::getParam('core.dir_pic') . 'cars'.PHPFOX_DS);
        Phpfox::getLib('setting')->setParam('cars.url_photo', Phpfox::getParam('core.url_pic') . 'cars/');
        if ($this->request()->getInt('req2') > 0){
            return Phpfox::getLib('module')->setController('cars.view');
        }

        $aPages = array(21, 31, 41, 51);
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

        $aRelease = Phpfox::getService('cars.cars')->getReleaseYears();
        $aCurrencies = Phpfox::getService('core.currency')->get();
        foreach ($aCurrencies as $iKey => $aCurrency)
        {
            $aCurrencies[$iKey] = $iKey;
        }

        $aFilters = array(
            'display' => array(
                'type' => 'select',
                'options' => $aDisplays,
                'default' => 21
            ),
            'sort' => array(
                'type' => 'select',
                'options' => $aSorts,
                'default' => $sDefaultOrderName
            ),
            'sort_by' => array(
                'type' => 'select',
                'options' => array(
                    'DESC' => Phpfox::getPhrase('core.descending'),
                    'ASC' => Phpfox::getPhrase('core.ascending')
                ),
                'default' => $sDefaultSort,
            ),
            'title' => array(
                'type' => 'input:text',
                'size' => 17,
                'class' => 'txt_input',
//                'search' => 'AND c.title LIKE \'%[VALUE]%\''
            ),
            'type' => array(
                'type' => 'select',
                'options' => Phpfox::getService('cars.cars')->getTypes(true),
                'search' => 'AND c.type_id = \'[VALUE]\'',
                'add_any' => true,
                'id' => 'type'
            ),
            'from' => array(
                'type' => 'select',
                'options' => $aRelease,
                'add_any' => true,
                'search'  => 'AND c.release_year >= [VALUE]',
                'style'   => 'width:42%;float:left;'
            ),
            'to' => array(
                'type' => 'select',
                'options' => $aRelease,
                'add_any' => true,
                'search'  => 'AND c.release_year <= [VALUE]',
                'style'   => 'width:42%;float:right;'
            ),
            'priceFrom' => array(
                'type' => 'input:text',
            ),
            'priceTo' => array(
                'type' => 'input:text',
               // 'search'  => 'AND c.price <= [VALUE]'
            ),
            'location' => array(
                'type' => 'select',
                'options' => Phpfox::getService('cars.location')->get(),
                'search' => 'AND c.location_iso = \'[VALUE]\'',
                'add_any' => true,
                'id' => 'cm_country_iso'
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
        if ($sPlugin = Phpfox_Plugin::get('cars.controller_index_process_after_filters_variable')) {return eval($sPlugin);}

        $aSearchParams = array(
            'type' => 'browse',
            'filters' => $aFilters,
            'search' => 'title',
            'custom_search' => true
        );

        if ($sReset = $this->request()->getInt('reset')){
            $aSearchParams = array(
                'type' => 'browse',
                'filters' => array(),
                'custom_search' => true
            );
        }

        $oFilter = Phpfox::getLib('search')->set($aSearchParams);
        $sView = $this->request()->get('view');
        $aCustomSearch = $oFilter->getCustom();

        $this->template()->setTitle(Phpfox::getPhrase('cars.cars'))
            ->setBreadcrumb(Phpfox::getPhrase('cars.browse_cars'), $this->url()->makeUrl('cars'));
        if ($sPlugin = Phpfox_Plugin::get('cars.controller_index_process_filters_condition_start')) {return eval($sPlugin);}
        if ((int)($iPriceFrom = $oFilter->get('priceFrom')) || ($iPriceFrom = $this->request()->getInt('priceFrom')))
        {
            $oFilter->setCondition('AND c.price >= ' . $iPriceFrom. '' );
        }
        if ((int)($iPriceTo = $oFilter->get('priceTo')) || ($iPriceTo = $this->request()->getInt('priceTo')))
        {
            $oFilter->setCondition('AND c.price <= ' . $iPriceTo .'' );
        }

        if (($sTitle = $oFilter->get('title')) || ($sTitle = $this->request()->getInt('title')))
        {
            if (Phpfox::getLib('parse.input')->convert($sTitle) != Phpfox::getLib('parse.input')->convert(Phpfox::getPhrase('cars.title_example'))){
//                $oFilter->setCondition('AND c.title LIKE \'%' . $sTitle .'%\'' );
//                $oFilter->setCondition('OR c.description LIKE \'%' . $sTitle .'%\'' );
                $oFilter->setCondition('AND (c.title LIKE \'%' . $sTitle .'%\' OR c.description LIKE \'%' . $sTitle .'%\')');
            }
        }

        if (($sType = $oFilter->get('type')) || ($sType = $this->request()->getInt('type')))
        {
            $oFilter->setCondition('AND c.type_id = ' . $sType .'' );
            $this->setParam('type_id', $sType);
        }

        if (($sMark = $oFilter->get('mark')) || ($sMark = $this->request()->getInt('mark')))
        {
            $oFilter->setCondition('AND c.mark_id = ' . $sMark .'' );
            $this->setParam('mark_id', $sMark);
        }

        if (($sModel = $oFilter->get('model')) || ($sModel = $this->request()->getInt('model')))
        {
            $oFilter->setCondition('AND c.model_id = ' . $sModel .'' );
            $this->setParam('model_id', $sModel);
        }

        if ($sMy = $this->request()->get('req3'))
        {
            if ($sMy == 'my'){
                $oFilter->setCondition('AND c.user_id = '.Phpfox::getUserId());
                $oFilter->setCondition('AND c.is_sold IN(0,1)');
            }

        }elseif ($this->getParam('bIsProfile')){
            $sUser = $this->request()->get('req1');
            $aUser = Phpfox::getService('user')->getByUserName($sUser);
            $oFilter->setCondition('AND c.user_id = '.$aUser['user_id']);
        }

        if ($sMy == 'my' && ($sSold = $this->request()->get('req4')) && $sSold == 'sold')
        {
            $oFilter->setCondition('AND c.is_sold = 1');
        } elseif (($sSubmit = $oFilter->get('submit')) && !empty($sSubmit)){
            $oFilter->setCondition('AND c.is_sold = 0');
        }

        if (!Phpfox::isAdmin() && Phpfox::getUserParam('cars.cars_must_be_approved') && empty($sMy)){

            $oFilter->setCondition('AND c.view_id = 0');
        }
        if (!Phpfox::isAdmin() && empty($sMy)){
            $oFilter->setCondition('AND c.is_sold = 0');
        }
        if (Phpfox::getUserParam('cars.can_approve_cars') && ($sModerate = $this->request()->get('req3')) && $sModerate == 'moderate'){
            $oFilter->setCondition('AND c.view_id = 1');
        }

        $iPage = $this->request()->getInt('page');
        $iPageSize = $oFilter->getDisplay();
//var_dump($oFilter);die;
        list($iCnt, $aCars) = Phpfox::getService('cars.browse')->conditions($oFilter->getConditions())
            ->sort($oFilter->getSort())
            ->page($oFilter->getPage())
            ->limit($iPageSize)
            ->custom($aCustomSearch)
            ->photo(true)
            ->get();
        $iCnt = $oFilter->getSearchTotal($iCnt);
        $aNewCustomValues = array();
        if ($aCustomValues = $this->request()->get('custom'))
        {
            foreach ($aCustomValues as $iKey => $sCustomValue)
            {
                $aNewCustomValues['custom[' . $iKey . ']'] = $sCustomValue;
            }
        }
        else
        {
            $aCustomValues = array();
        }
        if ($sPlugin = Phpfox_Plugin::get('cars.controller_index_process_before_assert')) {return eval($sPlugin);}
        Phpfox::getLib('pager')->set(array('page' => $iPage, 'size' => $iPageSize, 'count' => $iCnt));
        Phpfox::getLib('url')->setParam('page', $iPage);

        $this->template()->setHeader('cache', array(
                'browse.css' => 'style_css'
            )
        )->setPhrase(array('cars.from', 'cars.to', 'cars.title_example'));

        $this->template()->setHeader('cache', array(
                'browse.js' => 'module_cars'
            )
        );

        if($iPage > Phpfox::getLib('pager')->getTotalPages())
        {
            Phpfox::getLib('url')->send('error.404');
        }
        $aCustomFields = Phpfox::getService('cars.custom')->getForPublic('cars_main_browse');
        $this->template()
            ->setHeader('cache', array(
                    'selectize.min.js' => 'module_cars',
                    'selectize.default.css' => 'module_cars',
                    'pager.css' => 'style_css',
                    'index.js' => 'module_cars',
                    'index.css' => 'module_cars'
                )
            )
            ->assign(array(
                    'aCars' => $aCars,
                    'bIsSearch' => $oFilter->isSearch(),
                    'aForms' => $aCustomSearch,
                    'aCustomFields' => $aCustomFields,
                    'sView' => $sView
                )
            );
        Phpfox::getService('cars')->getSectionMenu();
        if ($sPlugin = Phpfox_Plugin::get('cars.controller_index_process_end')) {return eval($sPlugin);}
    }
}

?>
