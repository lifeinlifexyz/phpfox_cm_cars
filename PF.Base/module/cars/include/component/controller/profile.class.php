<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * Displays a users photo and album gallery on their profile.
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		CodeMake.Org
 * @package  		Module_Cars
 * @version 		$Id: profile.class.php 5143 2013-01-15 14:16:21Z Miguel_Espinoza $
 */
class Cars_Component_Controller_Profile extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$this->setParam('bIsProfile', true);
        $aUser = $this->getParam('aUser');
        Phpfox::getComponent('cars.index', array('bNoTemplate' => true), 'controller');
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('cars.component_controller_profile_clean')) ? eval($sPlugin) : false);
	}
}

?>