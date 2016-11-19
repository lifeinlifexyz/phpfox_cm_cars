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
 * @version 		$Id: delete.class.php 5840 2013-05-09 06:14:35Z Bolot_Kalil $
 */
class Cars_Component_Controller_Delete extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{	
		Phpfox::isUser(true);		
		
		if ($iId = $this->request()->getInt('id'))
		{
			$mReturn = Phpfox::getService('cars.process')->delete($iId);
			if (!empty($mReturn))
			{
                $this->url()->send('cars', array(), Phpfox::getPhrase('cars.car_successfully_deleted'));
			}else{
                Phpfox_Error::set(Phpfox::getPhrase('cars.unable_delete_the_car'));
            }
		}
	}
}

?>