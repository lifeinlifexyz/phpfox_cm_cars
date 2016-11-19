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
 * @package 		Phpfox_Module
 * @version 		$Id: index.class.php 6113 2013-06-21 13:58:40Z Bolot_Kalil $
 */
class Cars_Component_Controller_Admincp_Filter_Index extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
        $sAjax = "";
		$bSub = false;
        $bDoubleSub = false;
        $iEditId = null;
//        $iTypeId = $this->request()->getInt('id');
        $iMarkId = $this->request()->getInt('sub');
        $iModelId = $this->request()->getInt('doublesub');
        if($iModelId){
            $sAjax = "cars.modelOrdering";
            $bDoubleSub = true;
            $iEditId = $iModelId;
            if (($iDelete = $this->request()->getInt('delete')))
            {
                if (Phpfox::getService('cars.cars')->deleteFilter($iDelete, 'model'))
                {
                    $this->url()->send('admincp.cars.filter', array('doublesub'=>$this->request()->get('from')), Phpfox::getPhrase('cars.successfully_deleted_the_filter'));
                }
            }
        }
		elseif ($iMarkId)
		{
            $sAjax = "cars.markOrdering";
			$bSub = true;
            $iEditId = $iMarkId;
			if (($iDelete = $this->request()->getInt('delete')))
			{
				if (Phpfox::getService('cars.cars')->deleteFilter($iDelete, 'mark'))
				{
					$this->url()->send('admincp.cars.filter',array('sub'=>$this->request()->get('from')), Phpfox::getPhrase('cars.successfully_deleted_the_filter'));
				}
			}
		}else{
            $sAjax = "cars.typeOrdering";
			if (($iDelete = $this->request()->getInt('delete')))
			{
				if (Phpfox::getService('cars.cars')->deleteFilter($iDelete))
				{
					$this->url()->send('admincp.cars.filter', null, Phpfox::getPhrase('cars.successfully_deleted_the_filter'));
				}
			}			
		}

		$this->template()->setTitle(Phpfox::getPhrase('cars.manage_filters'))
			->setBreadcrumb(Phpfox::getPhrase('cars.manage_filters'))
			->setHeader(array(
					'drag.js' => 'static_script',
					'<script type="text/javascript">$Behavior.coreDragInit = function() { Core_drag.init({table: \'#js_drag_drop\', ajax: \'' . ($sAjax) . '\'}); }</script>'
				)
			)			
			->assign(array(
                    'iEditId' => $iEditId,
					'bSub' => $bSub,
                    'bDoubleSub' => $bDoubleSub,
					'aTypes' => Phpfox::getService('cars.cars')->getForEditType(),
                    'aMarks' => $bSub?Phpfox::getService('cars.cars')->getForEditMark(null, $iMarkId):array(),
                    'aModels' => $bDoubleSub?Phpfox::getService('cars.cars')->getForEditModel(null, $iModelId):array()
				)
			);
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('cars.component_controller_admincp_filter_index_clean')) ? eval($sPlugin) : false);
	}
}

?>