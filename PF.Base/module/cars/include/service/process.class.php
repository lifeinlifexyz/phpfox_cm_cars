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
 * @version 		$Id: process.class.php 6876 2013-11-12 10:48:57Z Bolot_Kalil $
 */
class Cars_Service_Process extends Phpfox_Service 
{
	/**
	 * Class constructor
	 */	
	public function __construct()
	{
		$this->_sTable = Phpfox::getT('cars');
        Phpfox::getLib('setting')->setParam('cars.dir_photo', Phpfox::getParam('core.dir_pic') . 'cars'.PHPFOX_DS);
        Phpfox::getLib('setting')->setParam('cars.url_photo', Phpfox::getParam('core.url_pic') . 'cars/');
	}	
	
	public function add($iUserId, $aVals, $aCustom)
	{
        if ($sPlugin = Phpfox_Plugin::get('cars.service_process_add_begin')) {return eval($sPlugin);}
        Phpfox::isUser(true);
        $oParseInput = Phpfox::getLib('parse.input');

        // Create the fields to insert.
        $aInserts = array();

        // Define all the fields we need to enter into the database
        $aInserts['user_id'] = $iUserId;
        $aInserts['type_id'] = !empty($aVals['type'])?$aVals['type']:0;
        $aInserts['mark_id'] = !empty($aVals['mark'])?$aVals['mark']:0;
        $aInserts['model_id'] = !empty($aVals['model'])?$aVals['model']:0;
        $aInserts['time_stamp'] = PHPFOX_TIME;
        $aInserts['server_id'] = Phpfox::getLib('request')->getServer('PHPFOX_SERVER_ID');
        $aInserts['release_year'] = $aVals['release'];
        $aInserts['price'] = $aVals['price'];
        $aInserts['currency'] = $aVals['currency'];
        $aInserts['is_sold'] = (isset($aVals['is_sold'])?$aVals['is_sold']:0);
        $aInserts['zip'] = $aVals['zip'];
        $aInserts['phone_number'] = $aVals['phone_number'];
        $aInserts['view_id'] = Phpfox::isAdmin()?0:(Phpfox::getUserParam('cars.cars_must_be_approved') ? '1' : '0');
        $aInserts['title'] = $oParseInput->clean(rtrim(preg_replace("/^(.*?)\.(jpg|jpeg|gif|png)$/i", "$1", $aVals['title'])), 255);
        $aInserts['location_iso'] = $aVals['location_iso'];
        if (isset($aVals['text']))
        {
            $aInserts['description'] = (empty($aVals['text']) ? '' : $this->preParse()->prepare($aVals['text']));
        }
        if (isset($aVals['privacy_comment']))
        {
            $aInserts['privacy_comment'] = $aVals['privacy_comment'];
        }
        if ($sPlugin = Phpfox_Plugin::get('cars.service_process_add_after_variables')) {return eval($sPlugin);}
        // Insert the data into the database.
        $iId = $this->database()->insert($this->_sTable, $aInserts);
        if (!empty($iId)){

            Phpfox::getService('cars.custom.process')->updateFields($iId, Phpfox::getUserId(), $aCustom);
        }

        if (!empty($_FILES['image']['name'][0])) {
            $oFile = Phpfox::getLib('file');
            $oImage = Phpfox::getLib('image');


            foreach ($_FILES['image']['error'] as $iKey => $sError) {
                if ($sError == UPLOAD_ERR_OK) {
                    if ($oFile->load('image[' . $iKey . ']', array(
                            'jpg',
                            'gif',
                            'png'
                        ), (Phpfox::getUserParam('cars.photo_max_upload_size') === 0 ? null : (Phpfox::getUserParam('cars.photo_max_upload_size')))
                    )
                    ) {
                        $iPhotoId = $this->database()->insert(Phpfox::getT('cars_photo'), array('car_id' => $iId, 'ordering'=>'0', 'user_id'=>$iUserId));

                        $sFileName = $oFile->upload('image[' . $iKey . ']',
                            Phpfox::getParam('cars.dir_photo'),
                            $iPhotoId
                        );

                        $iFileSizes = filesize(Phpfox::getParam('cars.dir_photo') . sprintf($sFileName, ''));


                        foreach (Phpfox::getParam('cars.photo_sizes') as $iSize) {
                            $oImage->createThumbnail(Phpfox::getParam('cars.dir_photo') . sprintf($sFileName, ''),Phpfox::getParam('cars.dir_photo') . sprintf($sFileName, '_' . $iSize), $iSize, $iSize);

                            $iFileSizes += filesize(Phpfox::getParam('cars.dir_photo') . sprintf($sFileName, '_' . $iSize));
                            if (Phpfox::getParam('cars.enabled_watermark_on_cars'))
                            {
                                $oImage->addMark(Phpfox::getParam('cars.dir_photo') . sprintf($sFileName, '_' . $iSize));
                            }
                        }

                        if (Phpfox::getParam('cars.enabled_watermark_on_cars'))
                        {
                            $oImage->addMark(Phpfox::getParam('cars.dir_photo') . sprintf($sFileName, ''));
                        }


                        $this->database()->update(Phpfox::getT('cars_photo'), array('destination' => $sFileName), 'photo_id = ' . $iPhotoId);
                        Phpfox::getService('user.space')->update($iUserId, 'photo', $iFileSizes);
                    }

                }

            }

        }

        if (!(Phpfox::isAdmin()?0:(Phpfox::getUserParam('cars.cars_must_be_approved') ? 1 : 0))){
            (Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->add('cars', $iId, 0, $aVals['privacy_comment'], 0, $iUserId) : null);
        }
        if ($sPlugin = Phpfox_Plugin::get('cars.service_process_add_end')) {return eval($sPlugin);}
        // Return the car ID#
        return $iId;
	}
	
	public function update($iId, $iUserId, $aVals, $aCustom)
	{
        if ($sPlugin = Phpfox_Plugin::get('cars.service_process_update_begin')) {return eval($sPlugin);}
        Phpfox::isUser(true);
        if (empty($iId)){
            return false;
        }
        $oParseInput = Phpfox::getLib('parse.input');

        // Create the fields to insert.
        $mUpdated = false;
        $aUpdates = array();

        // Define all the fields we need to enter into the database
        $aUpdates['user_id'] = $iUserId;
        $aUpdates['type_id'] = !empty($aVals['type'])?$aVals['type']:0;
        $aUpdates['mark_id'] = !empty($aVals['mark'])?$aVals['mark']:0;
        $aUpdates['model_id'] = !empty($aVals['model'])?$aVals['model']:0;
        $aUpdates['time_stamp'] = PHPFOX_TIME;
        $aUpdates['server_id'] = Phpfox::getLib('request')->getServer('PHPFOX_SERVER_ID');
        $aUpdates['release_year'] = $aVals['release'];
        $aUpdates['price'] = $aVals['price'];
        $aUpdates['currency'] = $aVals['currency'];
        $aUpdates['is_sold'] = (isset($aVals['is_sold'])?$aVals['is_sold']:0);
        $aUpdates['zip'] = $aVals['zip'];
        $aUpdates['phone_number'] = $aVals['phone_number'];
        $aUpdates['view_id'] = Phpfox::isAdmin()?0:(Phpfox::getUserParam('cars.cars_must_be_approved') ? '1' : '0');
        $aUpdates['title'] = $oParseInput->clean(rtrim(preg_replace("/^(.*?)\.(jpg|jpeg|gif|png)$/i", "$1", $aVals['title'])), 255);
        $aUpdates['location_iso'] = $aVals['location_iso'];
        if (isset($aVals['text']))
        {
            $aUpdates['description'] = (empty($aVals['text']) ? '' : $this->preParse()->prepare($aVals['text']));
        }
        if (isset($aVals['privacy_comment']))
        {
            $aUpdates['privacy_comment'] = $aVals['privacy_comment'];
        }
        if ($sPlugin = Phpfox_Plugin::get('cars.service_process_update_after_variables')) {return eval($sPlugin);}
        // Update the data into the database.
        if ($this->database()->update($this->_sTable, $aUpdates, 'car_id = '.$iId) && Phpfox::getService('cars.custom.process')->updateFields($iId, Phpfox::getUserId(), $aCustom)){
            $mUpdated = $iId;
        }

        if (!empty($_FILES['image']['name'][0])) {
            $oFile = Phpfox::getLib('file');
            $oImage = Phpfox::getLib('image');

            foreach ($_FILES['image']['error'] as $iKey => $sError) {
                if ($sError == UPLOAD_ERR_OK) {

                    if ($oFile->load('image[' . $iKey . ']', array(
                            'jpg',
                            'gif',
                            'png'
                        ), (Phpfox::getUserParam('cars.photo_max_upload_size') === 0 ? null : (Phpfox::getUserParam('cars.photo_max_upload_size')))
                    )) {

                        $iPhotoId = $this->database()->insert(Phpfox::getT('cars_photo'), array('car_id' => $iId, 'ordering'=>'0', 'user_id'=>$iUserId));

                        $sFileName = $oFile->upload('image[' . $iKey . ']',
                            Phpfox::getParam('cars.dir_photo'),
                            $iPhotoId
                        );

                        $iFileSizes = filesize(Phpfox::getParam('cars.dir_photo') . sprintf($sFileName, ''));


                        foreach (Phpfox::getParam('cars.photo_sizes') as $iSize) {
                            $oImage->createThumbnail(Phpfox::getParam('cars.dir_photo') . sprintf($sFileName, ''),Phpfox::getParam('cars.dir_photo') . sprintf($sFileName, '_' . $iSize), $iSize, $iSize);

                            $iFileSizes += filesize(Phpfox::getParam('cars.dir_photo') . sprintf($sFileName, '_' . $iSize));
                            if (Phpfox::getParam('cars.enabled_watermark_on_cars'))
                            {
                                $oImage->addMark(Phpfox::getParam('cars.dir_photo') . sprintf($sFileName, '_' . $iSize));
                            }
                        }

                        if (Phpfox::getParam('cars.enabled_watermark_on_cars'))
                        {
                            $oImage->addMark(Phpfox::getParam('cars.dir_photo') . sprintf($sFileName, ''));
                        }


                        $this->database()->update(Phpfox::getT('cars_photo'), array('destination' => $sFileName), 'photo_id = ' . $iPhotoId);
                        Phpfox::getService('user.space')->update($iUserId, 'photo', $iFileSizes);
                    }
                    else
                    {
//                        Phpfox_Error::set();
                    }

                }

            }

        }
        if (!(Phpfox::isAdmin()?0:(Phpfox::getUserParam('cars.cars_must_be_approved') ? 1 : 0))) {
            (Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->add('cars', $iId, 0, $aVals['privacy_comment'], 0, $iUserId) : null);
        }
        if ($sPlugin = Phpfox_Plugin::get('cars.service_process_update_end')) {return eval($sPlugin);}
        return $mUpdated;
	}


    public function delete($iId)
    {
        Phpfox::isUser(true);
        $aCar = $this->database()->select('*')
            ->from(Phpfox::getT('cars'))
            ->where('car_id = ' . (int) $iId)
            ->execute('getSlaveRow');

        if (!isset($aCar['car_id'])){
            return false;
        }
        $this->database()->delete(Phpfox::getT('cars'), "car_id = " . (int) $aCar['car_id']);
        $this->database()->delete(Phpfox::getT('cars_custom_multiple_value'), "car_id = " . (int) $aCar['car_id']);
        $this->database()->delete(Phpfox::getT('cars_custom'), "car_id = " . (int) $aCar['car_id']);
        $this->database()->delete(Phpfox::getT('cars_custom_data'), "car_id = " . (int) $aCar['car_id']);
        $this->database()->delete(Phpfox::getT('cars_custom_value'), "car_id = " . (int) $aCar['car_id']);
        (Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->delete('cars',(int) $aCar['car_id']) : null);
        (Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->delete('comment_cars', $aCar['car_id']) : null);
        $aPhotos = Phpfox::getService('cars.cars')->getPhotos($aCar['car_id']);
        if (!empty($aPhotos)){
            foreach($aPhotos as $aPhoto)
            {
                foreach(Phpfox::getParam('cars.photo_sizes') as $iSize):
                    @unlink(Phpfox::getParam('core.dir_pic').sprintf($aPhoto['destination'], $iSize));
                endforeach;
            }
            $this->database()->delete(Phpfox::getT('cars_photo'), "car_id = " . (int) $aCar['car_id']);
        }
        return $aCar;
    }
	
	public function updateView($iId)
	{
		$this->database()->query("
			UPDATE " . $this->_sTable . "
			SET total_view = total_view + 1
			WHERE car_id = " . (int) $iId . "
		");			
		
		return true;
	}

    /**
     * Update the photo counters.
     *
     * @param int $iId ID# of the photo
     * @param string $sCounter Field we plan to update
     * @param boolean $bMinus True increases to the count and false decreases the count
     */
    public function updateCounter($iId, $sCounter, $bMinus = false)
    {
        $this->database()->update($this->_sTable, array(
                $sCounter => array('= ' . $sCounter . ' ' . ($bMinus ? '-' : '+'), 1)
            ), 'car_id = ' . (int) $iId
        );
    }


    public function getCar($iId, $iPhotoId = null){


        if (!empty($iPhotoId)){
            $aPhoto = Phpfox::getService('cars.cars')->getPhotoById($iPhotoId);
            if (!isset($aPhoto['photo_id'])){
                unset($aPhoto);
            }
        }
        if (Phpfox::isModule('like'))
        {
            $this->database()->select('l.like_id as is_liked, ')
                ->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = "cars" AND l.item_id = c.car_id AND l.user_id = ' . Phpfox::getUserId() . '');
        }

        $this->database()->where('c.car_id = \'' . (int)$this->database()->escape($iId) . '\''.(!empty($aPhoto['photo_id'])?(' AND cp.photo_id = '.$aPhoto['photo_id']):''));

        $this->database()->select('f.friend_id AS is_friend, ')->leftJoin(Phpfox::getT('friend'), 'f', "f.user_id = c.user_id AND f.friend_user_id = " . Phpfox::getUserId());
        $aCar = $this->database()->select('' . Phpfox::getUserField() . ', c.*, ct.item_id AS is_viewed, cp.destination, cp.photo_id, cp.is_main')
            ->from($this->_sTable, 'c')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = c.user_id')
            ->leftJoin(Phpfox::getT('cars_track'), 'ct', 'ct.item_id = c.car_id AND ct.user_id = ' . Phpfox::getUserId())
            ->leftJoin(Phpfox::getT('cars_photo'), 'cp', 'c.car_id = cp.car_id')
            ->execute('getSlaveRow');
        if (isset($aCar['is_sold']) && $aCar['is_sold'] == 1 && Phpfox::getUserId() != $aCar['user_id']){
            return false;
        }
        if (!isset($aCar['car_id'])){
            return false;
        }
        if (!Phpfox::isModule('like'))
        {
            $aCar['is_liked'] = false;
        }
        if ($sPlugin = Phpfox_Plugin::get('cars.service_process_get_car_end')) {return eval($sPlugin);}
        return $aCar;
    }

    public function getCarForEdit($iId)
    {
        $aCar= $this->database()->select("c.*, u.user_name, c.description AS text")
            ->from($this->_sTable, 'c')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = c.user_id')
            ->where('c.car_id = ' . (int) $iId)
            ->execute('getSlaveRow');
        if (!isset($aCar['car_id'])){
            return false;
        }
        $aPhotos = Phpfox::getService('cars')->getPhotos($aCar['car_id']);

        return array($aCar, $aPhotos);
    }

    public function getPhotoStream($iCarId, $iPhotoId, $iUserId = 0)
    {
     //   $sQuery = '';

        /*if ($iUserId > 0)
        {
            $sQuery .= ' AND cp.user_id = ' . (int) $iUserId;
        }*/


        // Check permissions
//        if (!Phpfox::isAdmin())
//        {
//
//            /*
//                4 => Custom
//                3 => Only Me
//                2 => Friends of Friends
//                1 => Friends
//                0 => Everyone
//            */
//            $sQuery .= (empty($sQuery) ? '' : ' AND ') . '(';
//            if (Phpfox::getParam('core.section_privacy_item_browsing'))
//            {
//                $sQuery .= ' OR ';
//                // 3 - "Only me" privacy
//                $sQuery .= ' (cp.user_id = ' . Phpfox::getUserId() . ') ';
//
//                // Can view Pending-Approval photos
//                if (Phpfox::getUserParam('cars.can_approve_cars') == false)
//                {
//                    $sQuery .= ' AND cp.view_id = 0';
//                }
//                $iCnt = 0;
//                $aFriends = array();
//                if (Phpfox::isModule('friend'))
//                {
//                    list($iCnt, $aFriends) = Phpfox::getService('friend')->get(array('AND friend.user_id = ' . (int) Phpfox::getUserId()), '', '', false);
//                }
//                if ($iCnt > 0)
//                {
//                    // 1 - Friends
//                    $sFriendsIn = '(';
//                    foreach ($aFriends as $aFriend)
//                    {
//                        $sFriendsIn .= $aFriend['friend_user_id'] .',';
//                    }
//                    $sFriendsIn = rtrim($sFriendsIn, ',') .')';
//
//                    $sQuery .= ' OR (cp.user_id IN ' . $sFriendsIn .')';
//
//                    // 2 - Friends of Frends
//                    $aFriendsOfFriends = Phpfox::getService('friend')->getFriendsOfFriends($sFriendsIn);
//                    if (!empty($aFriendsOfFriends))
//                    {
//                        $sIn = implode(',', $aFriendsOfFriends);
//                        $sQuery .= ' OR (cp.user_id IN (' . $sIn . '))';
//                    }
//
//                    $aInList = Phpfox::getService('friend.list')->getUsersInAnyList();
//                    if (!empty($aInList))
//                    {
//                        $sIn = implode(',', $aInList);
//                        $sQuery .= ' OR (cp.user_id IN (' . $sIn . '))';
//                    }
//                }
//                else
//                {
//                    $sQuery .= ') AND (cp.car_id = 0';
//                }
//            }
//            $sQuery .= ')';
//        }


        list($iPreviousCnt, $aPrevious) = $this->_getPhoto('AND cp.car_id = '.$iCarId.' AND cp.photo_id < ' . (int) $iPhotoId, 'DESC', true);
        list($iNextCount, $aNext) = $this->_getPhoto('AND cp.car_id = '.$iCarId.' AND cp.photo_id > ' . (int) $iPhotoId, 'ASC', true);

        return array(
            'total' => ($iNextCount + $iPreviousCnt + 1),
            'current' => ($iPreviousCnt + 1),
            'previous' => $aPrevious,
            'next' => $aNext
        );
    }

    public function approve($iId)
    {
        $aCar = $this->database()->select('cp.*, ' . Phpfox::getUserField())
            ->from($this->_sTable, 'cp')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = cp.user_id')
            ->where('cp.car_id = ' . (int) $iId)
            ->execute('getSlaveRow');

        if (!isset($aCar['car_id']))
        {
            return false;
        }
        if ($aCar['view_id'] == '0')
        {
            return true;
        }

        $this->database()->update($this->_sTable, array('view_id' => 0, 'time_stamp' => PHPFOX_TIME), 'car_id = ' . $aCar['car_id']);
        if (!(Phpfox::isAdmin()?0:(Phpfox::getUserParam('cars.cars_must_be_approved') ? 1 : 0))){
            (Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->add('cars', $aCar['car_id'], 0, $aCar['privacy_comment'], 0, $aCar['user_id']) : null);
        }
        if (Phpfox::isModule('notification'))
        {
            Phpfox::getService('notification.process')->add('cars', $aCar['car_id'], $aCar['user_id']);
        }

        $sLink = Phpfox::permalink('cars', $aCar['car_id'], $aCar['title']);

        Phpfox::getLib('mail')->to($aCar['user_id'])
            ->subject(array('cars.your_car_title_has_been_approved', array('title' => $aCar['title'])))
            ->message(Phpfox::getPhrase('cars.your_car_has_been_approved_message', array('sLink' => $sLink, 'title' => $aCar['title'])))
            ->send();

        return true;
    }

    public function approveAdminCp($iApprove, $iId)
    {
        $aCar = $this->database()->select('cp.*, ' . Phpfox::getUserField())
            ->from($this->_sTable, 'cp')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = cp.user_id')
            ->where('cp.car_id = ' . (int) $iId)
            ->execute('getSlaveRow');

        if (!isset($aCar['car_id']))
        {
            return false;
        }
        $this->database()->update($this->_sTable, array('view_id' => $iApprove, 'time_stamp' => PHPFOX_TIME), 'car_id = ' . $aCar['car_id']);

        if (Phpfox::isModule('notification'))
        {
            Phpfox::getService('notification.process')->add('cars', $aCar['car_id'], $aCar['user_id']);
        }

        $sLink = Phpfox::permalink('cars', $aCar['car_id'], $aCar['title']);

        if ($iApprove){

            if (!(Phpfox::isAdmin()?0:(Phpfox::getUserParam('cars.cars_must_be_approved') ? 1 : 0))){
                (Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->add('cars', $aCar['car_id'], 0, $aCar['privacy_comment'], 0, $aCar['user_id']) : null);
            }
            Phpfox::getLib('mail')->to($aCar['user_id'])
                ->subject(array('cars.your_car_title_has_been_approved', array('title' => $aCar['title'])))
                ->message(Phpfox::getPhrase('cars.your_car_has_been_approved_message', array('sLink' => $sLink, 'title' => $aCar['title'])))
                ->send();
        }else{

            Phpfox::getLib('mail')->to($aCar['user_id'])
                ->subject(array('cars.your_car_title_has_not_been_approved', array('title' => $aCar['title'])))
                ->message(Phpfox::getPhrase('cars.your_car_has_not_been_approved_message', array('sLink' => $sLink, 'title' => $aCar['title'])))
                ->send();
        }

        return true;
    }

//    public function rotate($iId, $sCmd)
//    {
//        $aPhoto = $this->database()->select('cp.photo_id, cp.user_id, cp.car_id, cp.destination, c.title, c.server_id')
//            ->from(Phpfox::getT('cars_photo'), 'cp')
//            ->leftJoin($this->_sTable, 'c', 'c.car_id = cp.car_id')
//            ->where('photo_id = ' . (int) $iId)
//            ->execute('getSlaveRow');
//
//        if (!isset($aPhoto['photo_id']))
//        {
//            return Phpfox_Error::set(Phpfox::getPhrase('cars.unable_to_find_the_photo_you_plan_to_edit'));
//        }
//
//        if (($aPhoto['user_id'] == Phpfox::getUserId() && Phpfox::getUserParam('cars.can_edit_own_car')))
//        {
//            $aSizes = array_merge(array(''), Phpfox::getParam('cars.photo_sizes'));
//            $aParts = explode('/', $aPhoto['destination']);
//            $sParts = '';
//            if(is_array($aParts))
//            {
//                foreach($aParts as $sPart)
//                {
//                    if(!empty($sPart))
//                    {
//                        if(!preg_match('/jpg|gif|png/i', $sPart))
//                        {
//                            $sParts .= $sPart . '/';
//                        }
//                    }
//                }
//            }
//
//            foreach($aSizes as $iSize)
//            {
//                $sFile = Phpfox::getParam('cars.dir_photo') . sprintf($aPhoto['destination'], (empty($iSize) ? '' : '_') . $iSize);
//                if (file_exists($sFile) || Phpfox::getParam('core.allow_cdn'))
//                {
//                    if (Phpfox::getParam('core.allow_cdn') && $aPhoto['server_id'] > 0)
//                    {
//                        $sMainFile = $sFile;
//                        $sActualFile = Phpfox::getLib('image.helper')->display(array(
//                                'server_id' => $aPhoto['server_id'],
//                                'path' => 'cars.url_photo',
//                                'file' => $aPhoto['destination'],
//                                'suffix' => (empty($iSize) ? '' : '_') . $iSize,
//                                'return_url' => true
//                            )
//                        );
//
//                        $aExts = preg_split("/[\/\\.]/", $sActualFile);
//                        $iCnt = count($aExts)-1;
//                        $sExt = strtolower($aExts[$iCnt]);
//
//                        $sFile = Phpfox::getParam('cars.dir_photo') . $sParts . md5($aPhoto['destination']) . (empty($iSize) ? '' : '_') . $iSize . '.' . $sExt;
//
//                        copy($sActualFile, $sFile);
//                        //p($sFile);
//                    }
//
//                    Phpfox::getLib('image')->rotate($sFile, $sCmd);
//                }
//
//                if (Phpfox::getParam('core.allow_cdn') && $aPhoto['server_id'] > 0)
//                {
//                    $this->database()->update(Phpfox::getT('cars_photo'), array('destination' => $sParts . md5($aPhoto['destination']) . '%s.' . $sExt), 'photo_id = ' . (int) $aPhoto['photo_id']);
//                }
//            }
//
//            return $aPhoto;
//        }
//
//        return false;
//    }
    public function deletePhoto($iPhotoId){
        $aPhoto = $this->database()->select('*')
                       ->from(Phpfox::getT('cars_photo'))
                       ->where('photo_id = '.$iPhotoId)
                       ->execute('getSlaveRow');
        if (!isset($aPhoto['photo_id'])){
            return false;
        }

        if (Phpfox::isAdmin() || $aPhoto['user_id'] == Phpfox::getUserId()){
            return $this->database()->delete(Phpfox::getT('cars_photo'), 'photo_id = '.$aPhoto['photo_id']);
        }

        return false;
    }

    public function setAsFeatured($iCarId){
        $aCar = $this->database()->select('*')
            ->from(Phpfox::getT('cars'))
            ->where('car_id = '.$iCarId)
            ->execute('getSlaveRow');
        if (!isset($aCar['car_id'])){
            return false;
        }
        if (Phpfox::isAdmin() && isset($aCar['car_id'])){
            return $this->database()->update(Phpfox::getT('cars'), array('is_featured'=>1), 'car_id = '.$aCar['car_id']);
        }
        return false;
    }

    public function unsetAsFeatured($iCarId){
        $aCar = $this->database()->select('*')
            ->from(Phpfox::getT('cars'))
            ->where('car_id = '.$iCarId)
            ->execute('getSlaveRow');
        if (!isset($aCar['car_id'])){
            return false;
        }
        if (Phpfox::isAdmin() && isset($aCar['car_id'])){
            return $this->database()->update(Phpfox::getT('cars'), array('is_featured'=>0), 'car_id = '.$aCar['car_id']);
        }
        return false;
    }

    public function setAsMainPhoto($iPhotoId){
        $aPhoto = $this->database()->select('*')
            ->from(Phpfox::getT('cars_photo'))
            ->where('photo_id = '.$iPhotoId)
            ->execute('getSlaveRow');
        if (!isset($aPhoto['photo_id'])){
            return false;
        }
        if (Phpfox::isAdmin() || $aPhoto['user_id'] == Phpfox::getUserId()){
            $this->database()->update(Phpfox::getT('cars_photo'), array('is_main'=>0), 'car_id = '.$aPhoto['car_id']);
            return $this->database()->update(Phpfox::getT('cars_photo'), array('is_main'=>1), 'photo_id = '.$aPhoto['photo_id']);
        }
        return false;
    }

    private function _getPhoto($sCondition, $sOrder, $bNoPrivacy = false)
    {
        if ($bNoPrivacy === true)
        {
            $iPreviousCnt = $this->database()->select('COUNT(*)')
                ->from(Phpfox::getT('cars_photo'), 'cp')
//                ->leftJoin($this->_sTable, 'c', 'c.car_id = cp.car_id')
                ->where(array($sCondition))
                ->execute('getSlaveField');

            $aPrevious = (array) $this->database()->select('cp.*')
                ->from(Phpfox::getT('cars_photo'), 'cp')
//                ->leftJoin($this->_sTable, 'c', 'c.car_id = cp.car_id')
                ->where(array($sCondition))
                ->order('cp.photo_id ' . $sOrder)
                ->execute('getSlaveRow');

            if (!empty($aPrevious['photo_id']))
            {
                $aPrevious['link'] = Phpfox::getLib('url')->makeUrl('cars', array($aPrevious['car_id'], 'photo'=>$aPrevious['photo_id']));
            }

            return array($iPreviousCnt, $aPrevious);
        }

        $aBrowseParams = array(
            'module_id' => 'cars',
            'alias' => 'cp',
            'field' => 'photo_id',
            'table' => Phpfox::getT('cars_photo'),
            'hide_view' => array('pending', 'my')
        );

        $this->search()->set(array(
                'type' => 'cars',
                'filters' => array(
                    'display' => array('type' => 'option', 'default' => '1'),
                    'sort' => array('type' => 'option', 'default' => 'photo_id'),
                    'sort_by' => array('type' => 'option', 'default' => $sOrder)
                )
            )
        );

        $this->search()->setCondition($sCondition);
        $this->search()->setCondition('AND c.view_id = 0');

        $this->search()->browse()->params($aBrowseParams)->execute();
        $iPreviousCnt = $this->search()->browse()->getCount();
        $aPreviousRows = $this->search()->browse()->getRows();

        $this->search()->browse()->reset();

        $aPrevious = array();
        if (isset($aPreviousRows[0]))
        {
            $aPrevious = $aPreviousRows[0];
        }

        return array($iPreviousCnt, $aPrevious);
    }

    public function changePrint($iCarId, $sToPrint){
        if(empty($iCarId)){
            return false;
        }

        if ($sToPrint){
            $bReturn = $this->database()->update(Phpfox::getT('cars'), array('to_print'=>1), 'car_id = '.$this->database()->escape($iCarId));
        }else{
            $bReturn = $this->database()->update(Phpfox::getT('cars'), array('to_print'=>0), 'car_id = '.$this->database()->escape($iCarId));
        }
        return $bReturn;

    }

    public function getCountCars($iUserId=null){
        if (!empty($iUserId)){
            $this->database()->where('user_id = '.$iUserId);
        }
        return $this->database()->select('COUNT(*)')
                    ->from($this->_sTable)
                    ->execute('getField');
    }

    public function getLatestCars($iLimit=8){

        $aCars = $this->database()->select('c.*, cl.*')
            ->from($this->_sTable, 'c')
            ->leftJoin(Phpfox::getT('cars_location'), 'cl', 'c.location_iso = cl.country_iso')
            ->limit(0, $iLimit)
            ->order('time_stamp DESC')
            ->execute('getSlaveRows');

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

    public function getMostLikedCars($iLimit=8){

        $aCars = $this->database()->select('*')
            ->from($this->_sTable)
            ->where('is_featured = 1')
            ->limit(0, $iLimit)
            ->order('total_like DESC')
            ->execute('getSlaveRows');
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
		if ($sPlugin = Phpfox_Plugin::get('cars.service_process__call'))
		{
			return eval($sPlugin);
		}
		
		Phpfox_Error::trigger('Call to undefined method ' . __CLASS__ . '::' . $sMethod . '()', E_USER_ERROR);
	}	
}

?>
