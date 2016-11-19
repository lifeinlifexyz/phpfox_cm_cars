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
 * @version 		$Id: view.class.php 7019 2014-01-06 17:06:31Z Bolot_Kalil $
 */
class Cars_Component_Controller_View extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
        if ($sPlugin = Phpfox_Plugin::get('cars.controller_view_process_begin')) {return eval($sPlugin);}
        Phpfox::getLib('setting')->setParam('cars.dir_photo', Phpfox::getParam('core.dir_pic') . 'cars'.PHPFOX_DS);
        Phpfox::getLib('setting')->setParam('cars.url_photo', Phpfox::getParam('core.url_pic') . 'cars/');
		if ($this->request()->getInt('id'))
		{
			return Phpfox::getLib('module')->setController('error.404');
		}

        Phpfox::getUserParam('cars.can_view_cars', true);
        $sId = $this->request()->get('req2');
        $sPhotoId = $this->request()->get('photo');

        if (Phpfox::isUser() && Phpfox::isModule('notification'))
        {
            Phpfox::getService('notification.process')->delete('comment_cars', $this->request()->getInt('req2'), Phpfox::getUserId());
            Phpfox::getService('notification.process')->delete('cars_like', $this->request()->getInt('req2'), Phpfox::getUserId());
        }


        $aCar = Phpfox::getService('cars.process')->getCar($sId, $sPhotoId);

        if (!isset($aCar['car_id']))
        {
            return Phpfox_Error::display(Phpfox::getPhrase('cars.sorry_the_car_you_are_looking_for_no_longer_exists', array('link' => $this->url()->makeUrl('cars'))));
        }

        /*
            Don't like that this is here, but if added in the service class it would require an extra JOIN to the user table and its such a waste of a query when we could
            just get the users details vis the cached user array.
        */
        $aCar['bookmark_url'] = $this->url()->permalink('cars', $aCar['car_id'], $aCar['title']);

        // Increment the total view
        $aCar['total_view'] = ((int) $aCar['total_view'] + 1);

        // Assign the photo array so other blocks can use this information
        $this->setParam('aCar', $aCar);

        // Increment the view counter
        if (Phpfox::isModule('track') && Phpfox::isUser() && Phpfox::getUserId() != $aCar['user_id'] && !$aCar['is_viewed'])
        {
            Phpfox::getService('track.process')->add('cars', $aCar['car_id']);
            Phpfox::getService('cars.process')->updateCounter($aCar['car_id'], 'total_view');
        }
        $this->template()->setTitle($aCar['title']);

        $this->setParam('aFeed', array(
                'comment_type_id' => 'cars',
                'privacy' => '0',
                'comment_privacy' => $aCar['privacy_comment'],
                'like_type_id' => 'cars',
                'feed_is_liked' => $aCar['is_liked'],
                'feed_is_friend' => $aCar['is_friend'],
                'item_id' => $aCar['car_id'],
                'user_id' => $aCar['user_id'],
                'total_comment' => $aCar['total_comment'],
                'total_like' => $aCar['total_like'],
                'feed_link' => $this->url()->permalink('cars', $aCar['car_id'], $aCar['title']),
                'feed_title' => $aCar['title'],
                'feed_display' => 'view',
                'feed_total_like' => $aCar['total_like'],
                'report_module' => 'cars',
                'report_phrase' => Phpfox::getPhrase('cars.report_this_car')
            )
        );

        $aHeader = array(
            'jquery/plugin/jquery.highlightFade.js' => 'static_script',
            'jquery/plugin/jquery.scrollTo.js' => 'static_script',
            'jquery/plugin/imgnotes/jquery.tag.js' => 'static_script',
            'imgnotes.css' => 'style_css',
            'quick_edit.js' => 'static_script',
            'comment.css' => 'style_css',
            'view.js' => 'module_cars',
            'switch_legend.js' => 'static_script',
            'switch_menu.js' => 'static_script',
            'view.css' => 'module_cars',
            'feed.js' => 'module_feed',
        );
//        if (!Phpfox::isMobile()){
//            $aHeader['imgpreview.full.jquery.js'] = 'module_cars';
//        }

        $this->template()->setHeader('cache', $aHeader);

        $sDescription = '';
        $sDescription .= Phpfox::getPhrase('cars.release_year').':'.$aCar['release_year'].',';
        $sDescription .= Phpfox::getPhrase('cars.price').':'.$aCar['price'].',';
        $aMark  = Phpfox::getService('cars.cars')->getMark($aCar['type_id'], $aCar['mark_id']);
        $aModel = Phpfox::getService('cars.cars')->getModel($aCar['mark_id'], $aCar['model_id']);
        if (!empty($aMark['title']))  $sDescription .= $aMark['title'].',';
        if (!empty($aModel['title'])) $sDescription .= $aModel['title'].'.';
        $sDescription = trim($sDescription, ',');
        if ($sPlugin = Phpfox_Plugin::get('cars.controller_view_process_before_assert')) {return eval($sPlugin);}
        $this->template()
//            ->setFullSite()
            ->setBreadcrumb(Phpfox::getPhrase('cars.cars'), $this->url()->makeUrl('cars'))
            ->setBreadcrumb($aCar['title'], $this->url()->permalink('cars', $aCar['car_id'], $aCar['title']), true)
            ->setMeta('description',  $sDescription)
            ->setMeta('keywords', $this->template()->getKeywords($aCar['title']))
            ->setMeta('og:image', Phpfox::getParam('cars.url_photo').sprintf($aCar['destination'], '_150'))
            ->setHeader(array(
            ))
            ->setEditor(array(
                    'load' => 'simple'
                )
            )->assign(array(
                    'aForms' => $aCar,
                    'aPhotoStream' => Phpfox::getService('cars.process')->getPhotoStream($aCar['car_id'], !empty($iPhotoId)?$iPhotoId:$aCar['photo_id'], $aCar['user_id']),
                    'sCurrentPhotoUrl' => Phpfox::getLib('url')->makeUrl('current'),
                    'sMicroPropType' => 'Photograph'
                )
            );
        if ($sPlugin = Phpfox_Plugin::get('cars.controller_view_process_end')) {return eval($sPlugin);}
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('cars.component_controller_view_clean')) ? eval($sPlugin) : false);
	}
}

?>
