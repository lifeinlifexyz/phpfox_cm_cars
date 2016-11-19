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
class Cars_Component_Block_Generalmenu extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
        if (!$this->getParam('bIsProfile')){
            $this->template()->assign(
                array(
                    'aMenus'=>Phpfox::getService('cars.cars')->buildMenu()
                )
            );
        }

        return 'block';
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('cars.component_block_generalmenu_clean')) ? eval($sPlugin) : false);
	}
}

?>