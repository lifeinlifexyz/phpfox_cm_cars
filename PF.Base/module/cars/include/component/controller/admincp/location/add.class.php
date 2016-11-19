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
 * @package 		Phpfox_Component
 * @version 		$Id: add.class.php 3402 2011-11-01 09:07:31Z Bolot_Kalil $
 */
class Cars_Component_Controller_Admincp_Location_Add extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$bIsEdit = false;
		if (($iEditId = $this->request()->get('id')))
		{
			$aRow = Phpfox::getService('cars.location')->getForEdit($iEditId);
			$bIsEdit = true;
			$this->template()->assign(array(			
					'aForms' => $aRow,
					'iEditId' => $iEditId
				)
			);
		}

        if (($aVals = $this->request()->getArray('val')))
		{
			if ($bIsEdit)
			{
				if (Phpfox::getService('cars.location.process')->update($iEditId, $aVals))
				{
                    $this->url()->send('admincp.cars.location', null, Phpfox::getPhrase('cars.location_successfully_updated'));
				}				
			}
			else
			{
				if (Phpfox::getService('cars.location.process')->add($aVals))
                {
					$this->url()->send('admincp.cars.location.add', null, Phpfox::getPhrase('cars.successfully_created_a_new_location'));
				}
			}
		}
		
		$this->template()->setTitle(Phpfox::getPhrase('cars.add_location'))
			->setBreadcrumb(Phpfox::getPhrase('cars.add_location'))
			->assign(array(
				'bIsEdit' => $bIsEdit,
                'aLocations' => Phpfox::getService('cars.location')->getForEdit()
			)
		);
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('pages.component_controller_admincp_add_clean')) ? eval($sPlugin) : false);
	}
}

?>