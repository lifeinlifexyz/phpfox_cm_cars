<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * Callbacks
 *
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		CodeMake.Org
 * @package  		Module_User
 * @version 		$Id: callback.class.php 7164 2014-02-28 16:40:41Z Bolot_Kalil $
 */
class Cars_Service_Callback extends Phpfox_Service
{
	public function  __construct()
	{
		$this->_sTable = Phpfox::getT('cars');
        Phpfox::getLib('setting')->setParam('cars.dir_photo', Phpfox::getParam('core.dir_pic') . 'cars'.PHPFOX_DS);
        Phpfox::getLib('setting')->setParam('cars.url_photo', Phpfox::getParam('core.url_pic') . 'cars/');
	}
	
	public function getActionsStatus()
	{
		return $this->getActions();	
	}

	public function mobileMenu()
	{
		return array(
			'phrase' => Phpfox::getPhrase('cars.cars'),
			'link' => Phpfox::getLib('url')->makeUrl('cara'),
			'icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'mobile/small_groups.png'))
		);
	}

    public function globalSearch($sQuery, $bIsTagSearch = false)
    {
        $sCondition = '';
        if ($bIsTagSearch == false)
        {
            $sCondition .= ' AND (cp.title LIKE \'%' . $this->database()->escape($sQuery) . '%\' OR cp.description LIKE \'%' . $this->database()->escape($sQuery) . '%\')';
        }

        $iCnt = $this->database()->select('COUNT(*)')
            ->from($this->_sTable, 'cp')
            ->where($sCondition)
            ->execute('getSlaveField');

        $aRows = $this->database()->select('cph.destination, cp.car_id, cp.title, b.time_stamp, ' . Phpfox::getUserField())
            ->from($this->_sTable, 'cp')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = cp.user_id')
            ->leftJoin(Phpfox::getT('cars_photo'), 'cph', 'cph.car_id = cp.car_id')
            ->where($sCondition)
            ->limit(10)
            ->order('cp.time_stamp DESC')
            ->execute('getSlaveRows');

        if (count($aRows))
        {
            $aResults = array();
            $aResults['total'] = $iCnt;
            $aResults['menu'] = Phpfox::getPhrase('cars.search_cars');
            $aResults['form'] = '<form method="post" action="' . Phpfox::getLib('url')->makeUrl('blog') . '"><div><input type="hidden" name="' . Phpfox::getTokenName() . '[security_token]" value="' . Phpfox::getService('log.session')->getToken() . '" /></div><div><input name="search[search]" value="' . Phpfox::getLib('parse.output')->clean($sQuery) . '" size="20" type="hidden" /></div><div><input type="submit" name="search[submit]" value="' . Phpfox::getPhrase('cars.view_more_cars') . '" class="search_button" /></div></form>';

            foreach ($aRows as $iKey => $aRow)
            {
                $aResults['results'][$iKey] = array(
                    'title' => $aRow['title'],
                    'link' => Phpfox::getLib('url')->makeUrl(array('cars', $aRow['car_id'], $aRow['title'])),
                    'image' => Phpfox::getLib('image.helper')->display(array(
                                'server_id' => $aRow['server_id'],
                                'title' => $aRow['title'],
                                'path' => 'cars.url_photo',
                                'file' => $aRow['destination'],
                                'suffix' => '_120',
                                'max_width' => 75,
                                'max_height' => 75
                            )
                        ),
                    'extra_info' => Phpfox::getPhrase('cars.car_created_on_time_stamp_by_full_name', array(
                                'link' => Phpfox::getLib('url')->makeUrl('cars'),
                                'time_stamp' => Phpfox::getTime(Phpfox::getParam('core.global_update_time'), $aRow['time_stamp']),
                                'user_link' => Phpfox::getLib('url')->makeUrl($aRow['user_name']),
                                'full_name' => $aRow['full_name']
                            )
                        )
                );
            }
            return $aResults;
        }
    }

	public function getNewsFeedStatus($aRow)
	{
		$oParseOutput = Phpfox::getLib('parse.output');

		$aRow['text'] = '<a href="' . Phpfox::getLib('url')->makeUrl($aRow['owner_user_name']) . '">' . $aRow['owner_full_name'] . '</a> ' . Phpfox::getService('feed')->shortenText($oParseOutput->clean($aRow['content'])) . '';
		$aRow['icon'] = 'misc/user_feed.png';
		$aRow['enable_like'] = true;

		return $aRow;
	}

	public function getNewsFeedPhoto($aRow)
	{
		$oParseOutput = Phpfox::getLib('parse.output');

		$aRow['text'] = Phpfox::getPhrase('user.a_href_link_full_name_a_updated_their_profile_picture', array(
				'link' => Phpfox::getLib('url')->makeUrl($aRow['owner_user_name']),
				'full_name' => $aRow['owner_full_name']
			)
		);

		if (defined('PHPFOX_IS_USER_PROFILE'))
		{
			$aImage = unserialize($aRow['content']);
			$sImage = Phpfox::getLib('image.helper')->display(array(
					'server_id' => $aImage['server_id'],
					'path' => 'core.url_user',
					'file' => $aImage['destination'],
					'suffix' => '_50',
					'max_width' => 75,
					'max_height' => 75,
					'style' => 'vertical-align:top; padding-right:5px;'
				)
			);
			$aRow['text'] .= '<div class="p_4"><a href="' . Phpfox::getLib('url')->makeUrl($aRow['owner_user_name']) . '">' . $sImage . '</a></div>';
		}
		
		$aRow['icon'] = 'misc/profile_photo.png';
		$aRow['enable_like'] = true;
		
		return $aRow;
	}

    public function getReportRedirect($iId)
	{
		$aRow = $this->database()->select('c.car_id, c.title')
			->from(Phpfox::getT('cars'), 'c')
			->where('c.car_id = ' . (int) $iId)
			->execute('getSlaveRow');;
			
		if (!isset($aRow['car_id']))
		{
			return false;
		}
			
		return Phpfox::permalink('cars', $aRow['car_id'], $aRow['title']);
	}

	public function getCustomFieldLocations()
	{
		return array(
			'cars_advanced_filter' => Phpfox::getPhrase('cars.cars_advanced_filter')
		);
	}

	public function getCustomGroups()
	{
		return array(
			'cars_main_browse' => Phpfox::getPhrase('cars.main_browse')
		);
	}


    public function getActivityFeedComment($aRow)
    {
        if (Phpfox::isUser() && Phpfox::isModule('like'))
        {
            $this->database()->select('l.like_id AS is_liked, ')
                ->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = \'feed_mini\' AND l.item_id = c.comment_id AND l.user_id = ' . Phpfox::getUserId());
        }

        $aItem = $this->database()->select('cp.car_id, cp.title, cp.time_stamp, cp.total_comment, cp.total_like, c.total_like, ct.text_parsed AS text, ' . Phpfox::getUserField())
            ->from(Phpfox::getT('comment'), 'c')
            ->join(Phpfox::getT('comment_text'), 'ct', 'ct.comment_id = c.comment_id')
            ->join(Phpfox::getT('cars'), 'cp', 'c.type_id = \'cars\' AND c.item_id = cp.car_id AND c.view_id = 0')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = cp.user_id')
            ->where('c.comment_id = ' . (int) $aRow['item_id'])
            ->execute('getSlaveRow');

        if (!isset($aItem['car_id']))
        {
            return false;
        }

        $sLink = Phpfox::permalink('cars', $aItem['car_id'], $aItem['title']);
        $sTitle = Phpfox::getLib('parse.output')->shorten($aItem['title'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') :50));
        $sUser = '<a href="' . Phpfox::getLib('url')->makeUrl($aItem['user_name']) . '">' . $aItem['full_name'] . '</a>';
        $sGender = Phpfox::getService('user')->gender($aItem['gender'], 1);

        if ($aRow['user_id'] == $aItem['user_id'])
        {
            $sMessage = Phpfox::getPhrase('cars.posted_a_comment_on_gender_car_a_href_link_title_a', array('gender' => $sGender, 'link' => $sLink, 'title' => $sTitle));
        }
        else
        {
            $sMessage = Phpfox::getPhrase('cars.posted_a_comment_on_user_name_s_car_a_href_link_title_a', array('user_name' => $sUser, 'link' => $sLink, 'title' => $sTitle));
        }
        return array(
            'no_share' => true,
            'feed_info' => $sMessage,
            'feed_link' => $sLink,
            'feed_status' => $aItem['text'],
            'feed_total_like' => $aItem['total_like'],
            'feed_is_liked' => isset($aItem['is_liked']) ? $aItem['is_liked'] : false,
            'feed_icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'module/car.png', 'return_url' => true)),
            'time_stamp' => $aRow['time_stamp'],
            'like_type_id' => 'feed_mini'
        );
    }

    public function getActivityFeed($aRow, $aCallback = null, $bIsChildItem = false)
    {

        if (!Phpfox::getUserParam('cars.can_view_cars'))
        {
            return false;
        }

        if (Phpfox::isUser())
        {
            $this->database()->select('l.like_id AS is_liked, ')
                ->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = \'cars\' AND l.item_id = cp.car_id AND l.user_id = ' . Phpfox::getUserId());
        }

        if ($bIsChildItem)
        {
            $this->database()->select(Phpfox::getUserField('u2') . ', ')->join(Phpfox::getT('user'), 'u2', 'u2.user_id = cp.user_id');
        }

        $aRow = $this->database()->select('cp.server_id, cp.car_id, cp.title, cp.time_stamp, cp.total_comment, cp.total_like, cp.description')
            ->from(Phpfox::getT('cars'), 'cp')
            ->where('cp.car_id = ' . (int) $aRow['item_id'])
            ->execute('getSlaveRow');

        $aPhotos = Phpfox::getService('cars.cars')->getPhotos($aRow['car_id']);

        if (!isset($aRow['car_id']))
        {
            return false;
        }
        $aListPhotos = array();
        $iTotal = count($aPhotos);

        foreach($aPhotos as $aPhoto){
            $iIndexing =  count($aListPhotos);
            if($iIndexing > 4){
                continue;
            }
            $sPhotoImage = Phpfox::getLib('image.helper')->display(array(
                    'server_id' => $aRow['server_id'],
                    'path' => 'cars.url_photo',
                    'file' => $aPhoto['destination'],
                    'suffix' => '_500',
                    'class' => 'photo_holder',
                    'userid' => isset($aPhoto['user_id']) ? $aPhoto['user_id'] : '',
                    'defer' => true, // Further controlled in the library image.helper.,
                    'return_url' => true
                )
            );
            if($iIndexing == 3 && $iTotal > 4){
                $aListPhotos[] = '<a href="' . Phpfox::getLib('url')->makeUrl('cars', array($aPhoto['car_id'], 'photo'=>$aPhoto['photo_id'])).'" class="photo_holder_image" rel="' . $aPhoto['photo_id'] . '" style="background-image: url(\''.$sPhotoImage.'\')"><span>'.Phpfox::getPhrase('feed.view_more_plus').'</span></a>';
            }else{
                $aListPhotos[] = '<a href="' . Phpfox::getLib('url')->makeUrl('cars', array($aPhoto['car_id'], 'photo'=>$aPhoto['photo_id'])).'" class="photo_holder_image" rel="' . $aPhoto['photo_id'] . '" style="background-image: url(\''.$sPhotoImage.'\')"></a>';
            }
        }
        return array_merge(array(
            'feed_title' => '',
            'feed_info' => Phpfox::getPhrase('cars.posted_a_car', array('title'=>$aRow['title'])), //.':'.$aRow['title'],
            'feed_image' => (count($aListPhotos) ? $aListPhotos : null),
            'feed_link' => Phpfox::permalink('cars', $aRow['car_id'], $aRow['title']),
//            'feed_content' => $aRow['description'],
            'total_comment' => $aRow['total_comment'],
            'feed_total_like' => $aRow['total_like'],
            'feed_is_liked' => isset($aRow['is_liked']) ? $aRow['is_liked'] : false,
            'feed_icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'module/car.png', 'return_url' => true)),
            'time_stamp' => $aRow['time_stamp'],
            'enable_like' => true,
            'comment_type_id' => 'cars',
            'like_type_id' => 'cars',
            'custom_data_cache' => $aRow,
            'custom_rel' => $aRow['car_id'],
            'total_image'=> (count($aListPhotos) > 1 ? count($aListPhotos) : 1),
        ), $aRow);
    }

    public function addLike($iItemId, $bDoNotSendEmail = false)
    {
        $aRow = $this->database()->select('car_id, title, user_id')
            ->from(Phpfox::getT('cars'))
            ->where('car_id = ' . (int) $iItemId)
            ->execute('getSlaveRow');

        if (!isset($aRow['car_id']))
        {
            return false;
        }

        $this->database()->updateCount('like', 'type_id = \'cars\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'cars', 'car_id = ' . (int) $iItemId);

        if (!$bDoNotSendEmail)
        {
            $sLink = Phpfox::permalink('cars', $aRow['car_id'], $aRow['title']);

            Phpfox::getLib('mail')->to($aRow['user_id'])
                ->subject(array('cars.full_name_liked_your_car_title', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $aRow['title'])))
                ->message(array('cars.full_name_liked_your_car_link_title', array('full_name' => Phpfox::getUserBy('full_name'), 'link' => $sLink, 'title' => $aRow['title'])))
                ->notification('like.new_like')
                ->send();

            Phpfox::getService('notification.process')->add('cars_like', $aRow['car_id'], $aRow['user_id']);
        }
    }

    public function getNotificationLike($aNotification)
    {
        $aRow = $this->database()->select('cp.car_id, cp.title, cp.user_id, u.gender, u.full_name')
            ->from(Phpfox::getT('cars'), 'cp')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = cp.user_id')
            ->where('cp.car_id = ' . (int) $aNotification['item_id'])
            ->execute('getSlaveRow');

        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
        $sTitle = Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...');

        $sPhrase = '';
        if ($aNotification['user_id'] == $aRow['user_id'])
        {
            $sPhrase = Phpfox::getPhrase('cars.users_liked_gender_own_car_title', array('users' => $sUsers, 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => $sTitle));
        }
        elseif ($aRow['user_id'] == Phpfox::getUserId())
        {

            $sPhrase = Phpfox::getPhrase('cars.users_liked_your_car_title', array('users' => $sUsers, 'title' => $sTitle));
        }
        else
        {
            $sPhrase = Phpfox::getPhrase('cars.users_liked_span_class_drop_data_user_row_full_name_s_span_car_title', array('users' => $sUsers, 'row_full_name' => $aRow['full_name'], 'title' => $sTitle));
        }

        return array(
            'link' => Phpfox::getLib('url')->permalink('cars', $aRow['car_id'], $aRow['title']),
            'message' => $sPhrase,
            'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'cars')
        );
    }

    public function getNotification($aNotification)
    {
        $aRow = $this->database()->select('cp.car_id, cp.title, cp.user_id, u.gender, u.full_name')
            ->from(Phpfox::getT('cars'), 'cp')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = cp.user_id')
            ->where('cp.car_id = ' . (int) $aNotification['item_id'])
            ->execute('getSlaveRow');
        $sPhrase = Phpfox::getPhrase('cars.your_car_title_has_been_approved', array('title' => $aRow['title']));
        return array(
            'link' => Phpfox::getLib('url')->permalink('cars', $aRow['car_id'], $aRow['title']),
            'message' => $sPhrase,
            'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'cars')
        );
    }
    public function deleteLike($iItemId)
    {
        $this->database()->updateCount('like', 'type_id = \'cars\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'cars', 'car_id = ' . (int) $iItemId);
    }

    public function getNewsFeed($aRow, $iUserId = null)
    {
        $oUrl = Phpfox::getLib('url');
        $oParseOutput = Phpfox::getLib('parse.output');

        $aRow['text'] = Phpfox::getPhrase('cars.owner_full_name_added_a_new_car_a_href_title_link_title_a',
            array(
                'owner_full_name' => $aRow['owner_full_name'],
                'title' => Phpfox::getService('feed')->shortenTitle($aRow['content']),
                'user_link' => $oUrl->makeUrl('feed.user', array('id' => $aRow['user_id'])),
                'title_link' => $aRow['link']
            )
        );

        $aRow['icon'] = 'module/car.png';
        $aRow['enable_like'] = true;
        $aRow['comment_type_id'] = 'cars';

        return $aRow;
    }

    public function getCommentNewsFeed($aRow, $iUserId = null)
    {
        $oUrl = Phpfox::getLib('url');
        $oParseOutput = Phpfox::getLib('parse.output');

        if ($aRow['owner_user_id'] == $aRow['item_user_id'])
        {
            $aRow['text'] =
                Phpfox::getPhrase('cars.user_added_a_new_comment_on_their_own_car', array(
                    'user_name' => $aRow['owner_full_name'],
                    'user_link' => $oUrl->makeUrl('feed.user', array('id' => $aRow['user_id'])),
                    'title_link' => $aRow['link']
                )
            );
        }
        elseif ($aRow['item_user_id'] == Phpfox::getUserBy('user_id'))
        {
            $aRow['text'] = Phpfox::getPhrase('cars.user_added_a_new_comment_on_your_car', array(
                    'user_name' => $aRow['owner_full_name'],
                    'user_link' => $oUrl->makeUrl('feed.user', array('id' => $aRow['user_id'])),
                    'title_link' => $aRow['link']
                )
            );
        }
        else
        {
            $aRow['text'] = Phpfox::getPhrase('cars.user_name_added_a_new_comment_on_item_user_name_car', array(
                    'user_name' => $aRow['owner_full_name'],
                    'user_link' => $oUrl->makeUrl('feed.user', array('id' => $aRow['user_id'])),
                    'title_link' => $aRow['link'],
                    'item_user_name' => $aRow['viewer_full_name'],
                    'item_user_link' => $oUrl->makeUrl('feed.user', array('id' => $aRow['viewer_user_id']))
                )
            );
        }

        $aRow['text'] .= Phpfox::getService('feed')->quote($aRow['content']);

        return $aRow;
    }

    public function getAjaxCommentVar()
    {
        return 'cars.can_post_comment_on_car';
    }

    public function addComment($aVals, $iUserId = null, $sUserName = null)
    {
        $aCar = $this->database()->select('u.full_name, u.user_id, u.gender, u.user_name, cp.title, cp.car_id, cp.privacy_comment')
            ->from($this->_sTable, 'cp')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = cp.user_id')
            ->where('cp.car_id = ' . (int) $aVals['item_id'])
            ->execute('getSlaveRow');

        if ($iUserId === null)
        {
            $iUserId = Phpfox::getUserId();
        }

        (Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->add($aVals['type'] . '_comment', $aVals['comment_id'], 0, 0, 0, $iUserId) : null);

        // Update the post counter if its not a comment put under moderation or if the person posting the comment is the owner of the item.
        if (empty($aVals['parent_id']))
        {
            $this->database()->updateCounter('cars', 'total_comment', 'car_id', $aVals['item_id']);
        }

        // Send the user an email
        $sLink = Phpfox::permalink('cars', $aCar['car_id'], $aCar['title']);

        Phpfox::getService('comment.process')->notify(array(
                'user_id' => $aCar['user_id'],
                'item_id' => $aCar['car_id'],
                'owner_subject' => Phpfox::getPhrase('cars.full_name_commented_on_your_car_title', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $aCar['title'])),
                'owner_message' => Phpfox::getPhrase('cars.full_name_commented_on_your_car_message', array('full_name' => Phpfox::getUserBy('full_name'), 'link' => $sLink, 'title' => $aCar['title'])),
                'owner_notification' => 'comment.add_new_comment',
                'notify_id' => 'comment_cars',
                'mass_id' => 'cars',
                'mass_subject' => (Phpfox::getUserId() == $aCar['user_id'] ? Phpfox::getPhrase('cars.full_name_commented_on_gender_car', array('full_name' => Phpfox::getUserBy('full_name'), 'gender' =>  Phpfox::getService('user')->gender($aCar['gender'], 1))) : Phpfox::getPhrase('cars.full_name_commented_on_car_full_name_s_car', array('full_name' => Phpfox::getUserBy('full_name'), 'car_full_name' => $aCar['full_name']))),
                'mass_message' => (Phpfox::getUserId() == $aCar['user_id'] ? Phpfox::getPhrase('cars.full_name_commented_on_gender_car_message', array('full_name' => Phpfox::getUserBy('full_name'), 'gender' => Phpfox::getService('user')->gender($aCar['gender'], 1), 'link' => $sLink, 'title' => $aCar['title'])) : Phpfox::getPhrase('cars.full_name_commented_on_car_full_name_s_car_message', array('full_name' => Phpfox::getUserBy('full_name'), 'car_full_name' => $aCar['full_name'], 'link' => $sLink, 'title' => $aCar['title'])))
            )
        );

    }

    public function getCommentItem($iId)
    {
        $aRow = $this->database()->select('car_id AS comment_item_id, privacy_comment, user_id AS comment_user_id')
            ->from($this->_sTable)
            ->where('car_id = ' . (int) $iId)
            ->execute('getSlaveRow');

        $aRow['comment_view_id'] = '0';

        if (!Phpfox::getService('comment')->canPostComment($aRow['comment_user_id'], $aRow['privacy_comment']))
        {
            Phpfox_Error::set(Phpfox::getPhrase('cars.unable_to_post_a_comment_on_this_item_due_to_privacy_settings'));

            unset($aRow['comment_item_id']);
        }

        return $aRow;
    }

    public function getCommentItemName()
    {
        return 'cars';
    }

    public function deleteComment($iId)
    {
        $this->database()->update($this->_sTable, array('total_comment' => array('= total_comment -', 1)), 'car_id = ' . (int) $iId);
    }

    public function getCommentNotification($aNotification)
    {
        $aRow = $this->database()->select('cp.car_id, cp.title, cp.user_id, u.gender, u.full_name')
            ->from(Phpfox::getT('cars'), 'cp')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = cp.user_id')
            ->where('cp.car_id = ' . (int) $aNotification['item_id'])
            ->execute('getSlaveRow');

        if (!isset($aRow['car_id']))
        {
            return false;
        }

        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
        $sTitle = Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...');

        $sPhrase = '';
        if ($aNotification['user_id'] == $aRow['user_id'] && !isset($aNotification['extra_users']))
        {
            $sPhrase = Phpfox::getPhrase('cars.users_commented_on_gender_car_title', array('users' => $sUsers, 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => $sTitle));
        }
        elseif ($aRow['user_id'] == Phpfox::getUserId())
        {
            $sPhrase = Phpfox::getPhrase('cars.users_commented_on_your_car_title', array('users' => $sUsers, 'title' => $sTitle));
        }
        else
        {
            $sPhrase = Phpfox::getPhrase('cars.users_commented_on_span_class_drop_data_user_row_full_name_s_span_car_title', array('users' => $sUsers, 'row_full_name' => $aRow['full_name'], 'title' => $sTitle));
        }

        return array(
            'link' => Phpfox::getLib('url')->permalink('cars', $aRow['car_id'], $aRow['title']),
            'message' => $sPhrase,
            'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'cars')
        );
    }

    public function getCommentNotificationFeed($aRow)
    {
        return array(
            'message' => Phpfox::getPhrase('cars.full_name_wrote_a_comment_on_your_car_car_title', array(
                        'user_link' => Phpfox::getLib('url')->makeUrl($aRow['user_name']),
                        'full_name' => $aRow['full_name'],
                        'cars_link' => Phpfox::getLib('url')->makeUrl('cars', array('redirect' => $aRow['item_id'])),
                        'cars_title' => Phpfox::getLib('parse.output')->shorten($aRow['item_title'], 20, '...')
                    )
                ),
            'link' => Phpfox::getLib('url')->makeUrl('cars', array('redirect' => $aRow['item_id'])),
            'path' => 'core.url_user',
            'suffix' => '_50'
        );
    }


    public function globalUnionSearch($sSearch)
    {
        $this->database()->select('item.car_id AS item_id, item.title AS item_title, item.time_stamp AS item_time_stamp, item.user_id AS item_user_id, \'cars\' AS item_type_id, cph.destination AS item_photo, item.server_id AS item_photo_server')
            ->from(Phpfox::getT('cars'), 'item')
            ->leftJoin(Phpfox::getT('cars_photo'), 'cph', 'cph.car_id = item.car_id')
            ->where($this->database()->searchKeywords('item.title', $sSearch) . '')
            ->union();
    }

    public function getSearchInfo($aRow)
    {
        $aInfo = array();
        $aInfo['item_link'] = Phpfox::getLib('url')->permalink('cars', $aRow['item_id'], $aRow['item_title']);
        $aInfo['item_name'] = Phpfox::getPhrase('cars.cars');

        $aInfo['item_display_photo'] = Phpfox::getLib('image.helper')->display(array(
                'server_id' => $aRow['item_photo_server'],
                'file' => $aRow['item_photo'],
                'path' => 'cars.url_photo',
                'suffix' => '_150',
                'max_width' => '150',
                'max_height' => '150'
            )
        );

        return $aInfo;
    }

    public function getSearchTitleInfo()
    {
        return array(
            'name' => Phpfox::getPhrase('cars.cars')
        );
    }

    public function addTrack($iId, $iUserId = null)
    {
        $this->database()->insert(Phpfox::getT('cars_track'), array(
                'item_id' => (int) $iId,
                'user_id' => Phpfox::getUserBy('user_id'),
                'time_stamp' => PHPFOX_TIME
            )
        );
    }

    public function getTotalItemCount($iUserId)
    {
        return array(
            'field' => 'total_cars',
            'total' => $this->database()->select('COUNT(*)')->from(Phpfox::getT('cars'))->where('user_id = ' . (int) $iUserId . '')->execute('getSlaveField')
        );
    }
    public function getAjaxProfileController()
    {
        return 'profile.cars';
    }

    public function getProfileMenu($aUser)
    {

        $aMenus[] = array(
            'phrase' => Phpfox::getPhrase('cars.cars'),
            'url' => 'profile.cars',
            'total' => (int) Phpfox::getService('cars.process')->getCountCars($aUser['user_id']),
            'icon' => 'module/car.png'
        );

        return $aMenus;
    }
    public function getProfileLink()
    {
        return 'profile.cars';
    }

}

?>
