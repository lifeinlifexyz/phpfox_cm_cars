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
 * @version 		$Id: cars.class.php 7059 2014-01-22 14:20:10Z Fern $
 */
class Cars_Service_Cars extends Phpfox_Service
{
    private $_sTableType, $_sTableModel, $_sTableMark;
    private $_aTypes, $_aMarks, $_aModels;
	public function __construct()
	{
        $this->_sTable = Phpfox::getT('cars');
        Phpfox::getLib('setting')->setParam('cars.dir_photo', Phpfox::getParam('core.dir_pic') . 'cars'.PHPFOX_DS);
        Phpfox::getLib('setting')->setParam('cars.url_photo', Phpfox::getParam('core.url_pic') . 'cars/');
        $this->_sTableType = Phpfox::getT('cars_type');
        $this->_sTableMark = Phpfox::getT('cars_mark');
        $this->_sTableModel = Phpfox::getT('cars_model');

        $sCachedTypeId = $this->cache()->set('cars_type');
        $sCachedMarkId = $this->cache()->set('cars_mark');
        $sCachedModelId = $this->cache()->set('cars_model');

        if (!($this->_aTypes = $this->cache()->get($sCachedTypeId)))
        {
            $this->_aTypes = $this->database()->select('ct.*')
                ->from($this->_sTableType, 'ct')
                ->order('ct.title ASC')
                ->execute('getRows');
            $aTypesCache = array();
            foreach($this->_aTypes as $iTypeKey=>$aType){
                $aTypesCache[$aType['type_id']]=$aType;
            }
            $this->cache()->save($sCachedTypeId, $aTypesCache);
            $this->_aTypes = $this->cache()->get($sCachedTypeId);
        }

        if(!($this->_aMarks = $this->cache()->get($sCachedMarkId))){
            $this->_aMarks = $this->database()->select('cm.*')
                ->from($this->_sTableMark, 'cm')
                ->order('cm.title ASC')
                ->execute('getSlaveRows');
            $aMarksCache = array();
            foreach($this->_aMarks as $iMarkKey=>$aMark){
                $aMarksCache[$aMark['type_id']][$aMark['mark_id']]=$aMark;
            }

            $this->cache()->save($sCachedMarkId, $aMarksCache);
            $this->_aMarks = $this->cache()->get($sCachedMarkId);
        }

        if(!($this->_aModels = $this->cache()->get($sCachedModelId))){
            $this->_aModels = $this->database()->select('cmd.*')
                ->from($this->_sTableModel, 'cmd')
                ->order('cmd.title ASC')
                ->execute('getSlaveRows');

            $aModelsCache = array();
            foreach($this->_aModels as $iModelKey=>$aModel){
                $aModelsCache[$aModel['mark_id']][$aModel['model_id']]=$aModel;
            }
            $this->cache()->save($sCachedModelId, $aModelsCache);
            $this->_aModels = $this->cache()->get($sCachedModelId);
        }
    }

    public function addFilter($aVals){

        if (empty($aVals['name'])){
            Phpfox_Error::set(Phpfox::getPhrase('cars.the_name_must_not_be_empty'));
            return false;
        }

        $bIsInserted = false;
        if (!empty($aVals['type']) && !empty($aVals['mark']) && empty($aVals['model'])){
            $bIsInserted = $this->database()->insert($this->_sTableModel, array('type_id'=>$aVals['type'], 'mark_id'=>$aVals['mark'], 'title'=>$aVals['name']));
        }elseif(!empty($aVals['type']) && empty($aVals['mark'])){
            $bIsInserted = $this->database()->insert($this->_sTableMark, array('type_id'=>$aVals['type'], 'title'=>$aVals['name']));
        }elseif(empty($aVals['type'])){
            $bIsInserted = $this->database()->insert($this->_sTableType, array('title'=>$aVals['name']));
        }
        return $bIsInserted;
    }

    public function updateFilter($iEditId, $aVals, $sType){
        $bIsUpdated = false;

        if (empty($aVals['name'])){

            Phpfox_Error::set(Phpfox::getPhrase('cars.the_name_must_not_be_empty'));
            return false;
        }

        switch($sType){
            case 'model':
                $bIsUpdated = $this->database()->update($this->_sTableModel, array('title'=>$aVals['name']), 'model_id = '.$iEditId);
                break;
            case 'mark':
                $bIsUpdated = $this->database()->update($this->_sTableMark, array('title'=>$aVals['name']), 'mark_id = '.$iEditId);
                break;
            case 'type':
                $bIsUpdated = $this->database()->update($this->_sTableType, array('title'=>$aVals['name']), 'type_id = '.$iEditId);
                break;
        }
        return $bIsUpdated;
    }

    public function deleteFilter($iId, $sIsFilterType='type'){
        $bIsDeleted = false;
        switch($sIsFilterType){
            case 'model':
                $bIsDeleted = $this->database()->delete($this->_sTableModel, 'model_id='.(int)$iId);
                break;
            case 'mark':
                $this->database()->delete($this->_sTableModel, 'mark_id='.(int)$iId);
                $bIsDeleted = $this->database()->delete($this->_sTableMark, 'mark_id='.(int)$iId);
                break;
            case 'type':

                $this->database()->delete($this->_sTableModel, 'type_id='.(int)$iId);
                $this->database()->delete($this->_sTableMark, 'type_id='.(int)$iId);
                $bIsDeleted = $this->database()->delete($this->_sTableType, 'type_id='.(int)$iId);
                break;
        }
        return $bIsDeleted;
    }

    public function getTypes($bOnlyName=false){

        $aTypes = array();
        if ($bOnlyName) {
            foreach ($this->_aTypes as $aType) {
                $aTypes[$aType['type_id']] = $aType['title'];
            }
        }
        return $bOnlyName?$aTypes:$this->_aTypes;

    }

    public function getMarks($iType=null){
        return $iType != null?(isset($this->_aMarks[$iType])?$this->_aMarks[$iType]:array()):$this->_aMarks;
    }

    public function getModels($iMark=null){
        return $iMark != null?(isset($this->_aModels[$iMark])?$this->_aModels[$iMark]:array()):$this->_aModels;
    }

    public function getType($iTypeId){

        return isset($this->_aTypes[$iTypeId])?$this->_aTypes[$iTypeId]:array();
    }

    public function getMark($iTypeId, $iMarkId){

        if (empty($iTypeId) || empty($iMarkId) || empty($this->_aMarks)){
            return false;
        }

        return isset($this->_aMarks[$iTypeId][$iMarkId])?$this->_aMarks[$iTypeId][$iMarkId]:array();
    }

    public function getModel($iMarkId, $iModelId){

        if (empty($this->_aModels) || empty($iModelId) || empty($iMarkId)) {
            return false;
        }

        return isset($this->_aModels[$iMarkId][$iModelId])?$this->_aModels[$iMarkId][$iModelId]:array();
    }

    public function getForEditType($iId = null)
    {
        if ($iId !== null)
        {
            $this->database()->where('ct.type_id = \'' . $this->database()->escape($iId) . '\'');
        }

        return $this->database()->select('ct.*')
            ->from($this->_sTableType, 'ct')
            ->order('ct.ordering ASC')
            ->execute(($iId == null ? 'getSlaveRows' : 'getRow'));
    }
    public function getForEditMark($iId = null, $iTypeId=null)
    {
        if ($iId !== null)
        {
            $this->database()->where('cm.mark_id = \'' . $this->database()->escape($iId) . '\'');
        }
        if($iTypeId !== null){
            $this->database()->where('cm.type_id = \'' . $this->database()->escape($iTypeId) . '\'');
        }
        return $this->database()->select('cm.*')
            ->from($this->_sTableMark, 'cm')
            ->order('cm.title ASC')
            ->execute(($iId == null ? 'getRows' : 'getRow'));
    }

    public function getForEditModel($iId = null, $iMarkId=null)
    {
        if ($iId !== null)
        {
            $this->database()->where('cmd.model_id = \'' . $this->database()->escape($iId) . '\'');
        }
        if ($iMarkId !== null)
        {
            $this->database()->where('cmd.mark_id = \'' . $this->database()->escape($iMarkId) . '\'');
        }

        return $this->database()->select('cmd.*')
            ->from($this->_sTableModel, 'cmd')
            ->order('cmd.title ASC')
            ->execute(($iId == null ? 'getRows': 'getRow'));
    }

    public function getPhotos($iCarId=null){
        if (!is_null($iCarId)){
            $this->database()->where('car_id = '.$iCarId);
        }
        return $this->database()->select('*')->from(Phpfox::getT('cars_photo'))->execute('getSlaveRows');
    }

    public function getPhotoById($iPhotoId){
        $this->database()->where('photo_id = '.$iPhotoId);
        return $this->database()->select('*')->from(Phpfox::getT('cars_photo'))->execute('getSlaveRow');
    }

    public function getReleaseYears($bDesc=null){
        $aYears = array_combine(range(date("Y"), Phpfox::getParam('cars.range_years')), range(date("Y"), Phpfox::getParam('cars.range_years')));
        if ($bDesc !== null){
            asort($aYears);
        }
        return $aYears;
    }

    public function getSectionMenu()
    {
//        if(Phpfox::getUserId()){
//            $aFilterMenu = array(
//               Phpfox::getPhrase('cars.all_cars') => '',
//               Phpfox::getPhrase('cars.my_cars') => 'index.my'
//            );
//        }else{
//            $aFilterMenu = array(
//                Phpfox::getPhrase('cars.all_cars') => '',
//            );
//        }
//
//        $sTemplate = '<div class="content"><div class="sub_section_menu"><ul>';
//        foreach($aFilterMenu as $sPhrase=>$sLink){
//            $aUrls = explode('.', $sLink);
//
//            $sTemplate .=  sprintf('<li class="%s"><a href="%s">%s</a></li>', Phpfox::getLib('request')->get("req3") == $aUrls[sizeof($aUrls)-1]?'active':'', Phpfox::getLib('url')->makeUrl('cars', $aUrls), $sPhrase);
//
//        }
//	    $sTemplate .= '</ul></div></div>';
//        return $sTemplate;
        $aFilterMenu = array();
        if (!defined('PHPFOX_IS_USER_PROFILE'))
        {
            if(Phpfox::getUserId()){
                $aFilterMenu = array(
                   Phpfox::getPhrase('cars.all_cars') => '',
                   Phpfox::getPhrase('cars.my_cars') => 'cars.index.my',
                    Phpfox::getPhrase('cars.sold_cars') => 'cars.index.my.sold'
                );
            }else{
                $aFilterMenu = array(
                    Phpfox::getPhrase('cars.all_cars') => '',
                );
            }

            if (Phpfox::getUserParam('cars.can_approve_cars')){
                $aFilterMenu[Phpfox::getPhrase('cars.moderation')] = 'cars.index.moderate';
            }
        }

        Phpfox_Template::instance()->buildSectionMenu('cars', $aFilterMenu);
    }

    public function deleteMultiple($aIds)
    {
        Phpfox::isAdmin(true);
        foreach ($aIds as $iId)
        {

            $this->database()->delete($this->_sTable, 'car_id = ' . (int) $iId);
            $this->database()->delete(Phpfox::getT('cars_track'), 'item_id = ' . (int) $iId);
            (Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->delete('cars',(int) $iId) : null);
            (Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->delete('comment_cars', $iId) : null);
            $aPhotos = $this->database()->select('*')->from(Phpfox::getT('cars_photo'))->where('car_id = '.(int)$iId)->execute('getSlaveRows');

            if (count($aPhotos)){
                $aSizes = array_merge(array(''), Phpfox::getParam('cars.photo_sizes'));
                foreach ($aPhotos as $aPhoto){
                    foreach($aSizes as $sSize){
                        @unlink(Phpfox::getParam('cars.dir_photo').sprintf($aPhoto['destination'], empty($sSize)?$sSize:'_'.$sSize));
                    }
                }
                $this->database()->delete(Phpfox::getT('cars_photo'), 'car_id = ' . (int) $iId);
            }

        }
        return true;
    }

    public function getRecomendedCars($iCarId, $aCarsExclude=null){
        $aCar = Phpfox::getService('cars.process')->getCar($iCarId);
        if (!isset($aCar['car_id'])){
            return array();
        }

        $aCars = $this->database()->select("c.*")
            ->from($this->_sTable, 'c')
            ->order('c.car_id ASC')
            ->where("c.model_id = ".$aCar['model_id']." AND c.car_id <> ".$aCar['car_id'].(!empty($aCarsExclude)?" AND c.car_id NOT IN(".implode(",", $aCarsExclude).")":""))
            ->limit(Phpfox::getParam('cars.recomended_cars_size'))
            ->execute("getSlaveRows");

        if (empty($aCars)){
            $aCars = $this->database()->select("c.*")
                ->from($this->_sTable, 'c')
                ->order('c.car_id ASC')
                ->where("c.mark_id = ".$aCar['mark_id']." AND c.car_id <> ".$aCar['car_id'].(!empty($aCarsExclude)?" AND c.car_id NOT IN(".implode(",", $aCarsExclude).")":""))
                ->limit(Phpfox::getParam('cars.recomended_cars_size'))
                ->execute("getSlaveRows");
        }

        if (empty($aCars)){
            $aCars = $this->database()->select("c.*")
                ->from($this->_sTable, 'c')
                ->order('c.car_id ASC')
                ->where("c.car_id <> ".$aCar['car_id'].(!empty($aCarsExclude)?" AND c.car_id NOT IN(".implode(",", $aCarsExclude).")":""))
                ->limit(Phpfox::getParam('cars.recomended_cars_size'))
                ->execute("getSlaveRows");
        }

        if (count($aCars)){
            foreach ($aCars as $iIndex=>$aCar){

                if (!isset($aCar['car_id'])){
                    continue;
                }

                $aPhotos = Phpfox::getService('cars.cars')->getPhotos($aCar['car_id']);

                foreach($aPhotos as $aPhoto){

                    if (isset($aPhoto['is_main']) && !empty($aPhoto['is_main'])){
                        $aCars[$iIndex]['destination'] = $aPhoto['destination'];
                    }else{
                        $aCars[$iIndex]['destination'] = $aPhotos[mt_rand(0, sizeof($aPhotos)-1)]['destination'];
                    }
                }

            }

        }


        return $aCars;
    }

    public function __call($sMethod, $aArguments)
	{
		if ($sPlugin = Phpfox_Plugin::get('cars.service_cars__call'))
		{
			return eval($sPlugin);
		}
		
		Phpfox_Error::trigger('Call to undefined method ' . __CLASS__ . '::' . $sMethod . '()', E_USER_ERROR);
	}
}

?>
