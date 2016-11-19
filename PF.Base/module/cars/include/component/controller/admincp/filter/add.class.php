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
class Cars_Component_Controller_Admincp_Filter_Add extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$bIsEdit = false;
		$bIsSub = false;
        $bIsDoubleSub = false;
        $iFrom = $this->request()->get('from');
		if (($iEditId = $this->request()->getInt('id')))
		{
			$aRow = Phpfox::getService('cars.cars')->getForEditType($iEditId);
                        $bIsEdit = true;
			$this->template()->assign(array(			
					'aForms' => $aRow,
					'iEditId' => $iEditId
				)
			);
		}
		
		if (($iSubtEditId = $this->request()->getInt('sub')))
		{
			$aRow = Phpfox::getService('cars.cars')->getForEditMark($iSubtEditId);
			$iEditId = $iSubtEditId;
			$bIsEdit = true;
			$bIsSub = true;
			$this->template()->assign(array(			
					'aForms' => $aRow,
					'iSubtEditId' => $iSubtEditId
				)
			);
		}

        if (($iDoubleSubtEditId = $this->request()->getInt('doublesub')))
        {
            $aRow = Phpfox::getService('cars.cars')->getForEditModel($iDoubleSubtEditId);
            $iEditId = $iDoubleSubtEditId;
            $bIsEdit = true;
//            $bIsSub = true;
            $bIsDoubleSub = true;
            $this->template()->assign(array(
                    'aForms' => $aRow,
                    'iDoubleSubtEditId' => $iDoubleSubtEditId
                )
            );
        }

        if (($aVals = $this->request()->getArray('val')))
		{
			if ($bIsEdit)
			{
				if (Phpfox::getService('cars.cars')->updateFilter($iEditId, $aVals, $this->request()->get('type')))
				{

					if ($bIsDoubleSub)
					{
						$this->url()->send('admincp.cars.filter', array('doublesub' => $iFrom), Phpfox::getPhrase('cars.successfully_updated_the_filter'));
					}
					elseif($bIsSub)
					{
                        $this->url()->send('admincp.cars.filter', array('sub' => $iFrom), Phpfox::getPhrase('cars.successfully_updated_the_filter'));
					}else{
                        $this->url()->send('admincp.cars.filter', null, Phpfox::getPhrase('cars.successfully_updated_the_filter'));
                    }
				}				
			}
			else
			{
				if (Phpfox::getService('cars.cars')->addFilter($aVals))
                {
					$this->url()->send('admincp.cars.filter.add', null, Phpfox::getPhrase('cars.successfully_created_a_new_filter'));
				}else{
                                        Phpfox_Error::set(Phpfox::getPhrase('cars.error_adding'));
                                }
			}
		}
		
		$this->template()->setTitle(Phpfox::getPhrase('cars.add_filter'))
			->setBreadcrumb(Phpfox::getPhrase('cars.add_filter'))
			->assign(array(
				'bIsEdit' => $bIsEdit,
				'aTypes' => Phpfox::getService('cars.cars')->getForEditType(),
                'iFrom' => $iFrom
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