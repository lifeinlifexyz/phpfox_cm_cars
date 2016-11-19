<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * Creates the sub menu for photos when we are viewing them.
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		CodeMake.Org
 * @package  		Module_Cars
 * @version 		$Id: menu.class.php 2536 2011-04-14 19:37:29Z Bolot_Kalil $
 */
class Cars_Component_Block_Menu extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{		
		// Not a valid image lets get out of here
		$aCar = $this->getParam('aCar');

		if (empty($aCar))
		{
			return false;
		}
		
		$aUser = $this->getParam('aUser');
		
		// Assign the template vars
		$this->template()->assign(array(
				'sUserName' => $aUser['user_name'],
				'sTitle' => $aCar['title'],
//				'sBookmarkUrl' => $aCar['bookmark_url']
			)
		);
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('photo.component_block_menu_clean')) ? eval($sPlugin) : false);
		
		$this->template()->assign(array(
				'sPhotoUrl',
				'sAlbumUrl',
				'iAlbumId',
				'sUserName',
				'sPhotoTitle',
				'sBookmarkUrl'
			)
		);		
	}
}

?>