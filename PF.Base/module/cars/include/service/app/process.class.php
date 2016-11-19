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
 * @package  		Module_Core
 * @version 		$Id: country.class.php 7031 2014-01-08 17:53:30Z Fern $
 */
class Cars_Service_App_Process extends Phpfox_Service
{
    private function getShortTypes($aTypes){
        if (empty($aTypes)){
            return array();
        }
        $aNewTypes = array();
        foreach($aTypes as $aType){
            $aNewTypes[$aType['type_id']] = $aType['title'];
        }
        return $aNewTypes;
    }
    private function getShortMarks($aMarks){
        
        if (empty($aMarks)){
            return array();
        }
        $aNewMarks = array();
    
        foreach($aMarks as $aMark){

            foreach($aMark as $iMark=>$aMarkData){

                 $aNewMarks[$aMarkData['mark_id']]['title'] = $aMarkData['title'];
                 $aNewMarks[$aMarkData['mark_id']]['type_id'] = $aMarkData['type_id'];
            } 
        }

        return $aNewMarks;
    }
    private function getShortModels($aModels){
        if (empty($aModels)){
            return array();
        }
        $aNewModels = array();
        foreach($aModels as $aModel){

            foreach($aModel as $iModel=>$aModelData){
                 $aNewModels[$aModelData['model_id']]['title'] = $aModelData['title'];
                 $aNewModels[$aModelData['model_id']]['mark_id'] = $aModelData['mark_id']; 
            } 
            
        }

        return $aNewModels;
    }
    public function getSetting()
    {

        $aFields = array(
             /*array(
                'is_required' => '1',
                'title' => Phpfox::getPhrase('cars.title'),
                'type' => 'text',
                'name' => 'title'
            ),
            array(
                'is_required'=>'0',
                'title' => Phpfox::getPhrase('cars.description'),
                'type' => 'textarea',
                'name' => 'text'
            ),*/
            array(
                'is_required' => '1',
                'title' => Phpfox::getPhrase('cars.location'),
                'type' => 'select',
                'options'=>Phpfox::getService('cars.location')->get(),
                'name' => 'location_iso'
            ),
            array(
                'is_required' => '1',
                'title' => Phpfox::getPhrase('cars.type'),
                'type' => 'select',
                'options'=>$this->getShortTypes(Phpfox::getService('cars')->getTypes()),
                'name' => 'type'
            ),
            array(
                'is_required' => '1',
                'title' => Phpfox::getPhrase('cars.mark'),
                'type' => 'select',
                'options'=>$this->getShortMarks(Phpfox::getService('cars')->getMarks()),
                'name' => 'mark'
            ),
            array(
                'is_required' => '1',
                'title' => Phpfox::getPhrase('cars.model'),
                'type' => 'select',
                'options'=>$this->getShortModels(Phpfox::getService('cars')->getModels()),
                'name'=>'model'
            ),
            array(
                'is_required' => '1',
                'title' => Phpfox::getPhrase('cars.release_year'),
                'type' => 'select',
                'options'=>Phpfox::getService('cars')->getReleaseYears(),
                'name' => 'release'
            )/*,
            array(
                'is_required' => '1',
                'title' => Phpfox::getPhrase('cars.phone_number'),
                'type' => 'text',
                'name' => 'phone_number'
            ),
            array(
                'is_required' => '1',
                'title' => Phpfox::getPhrase('cars.price'),
                'type' => 'text',
                'name' => 'price'
            )*/
        );

        $aCustomFields = Phpfox::getService('cars.custom')->getFields('cars_main_browse');
        foreach ($aCustomFields as $iIndex=>$aCustomField)
        {

            if (($aCustomField['var_type'] == 'select' || $aCustomField['var_type'] == 'multiselect' || $aCustomField['var_type'] == 'radio' || $aCustomField['var_type'] == 'checkbox')){

                $aOptions = array();
                foreach ($aCustomField['options'] as $iKey=>$aOption){
                    $aOptions[$aOption['option_id']] = Phpfox::getPhrase($aOption['phrase_var_name']);
                    
//$aOptions[$iKey]['option_id'] = $aOption['option_id'];
                }
                $aFields[] = array('field_id'=>$aCustomField['field_id'], 'is_required'=>$aCustomField['is_required'], 'title'=>Phpfox::getPhrase($aCustomField['phrase_var_name']), 'name'=>$aCustomField['field_name'], 'type'=>$aCustomField['var_type'], 'options'=>$aOptions);
            }elseif(($aCustomField['var_type'] == 'textarea' || $aCustomField['var_type'] == 'text')){

                $aFields[] = array('field_id'=>$aCustomField['field_id'], 'is_required'=>$aCustomField['is_required'], 'title'=>Phpfox::getPhrase($aCustomField['phrase_var_name']), 'name'=>$aCustomField['field_name'], 'type'=>$aCustomField['var_type']);
            }
        }
        $sJson = html_entity_decode($this->json_encode($aFields), ENT_QUOTES, 'UTF-8');
        Phpfox::getLib('file')->writeToCache('form.json', $sJson);
        if (file_exists(PHPFOX_DIR_CACHE.PHPFOX_DS.'form.json')){
            return Phpfox::getLib('file')->forceDownload(PHPFOX_DIR_CACHE.PHPFOX_DS.'form.json', 'form.json');
        }

        return true;
    }

    public function json_encode($data){
        return preg_replace_callback('/\\\\ud([89ab][0-9a-f]{2})\\\\ud([c-f][0-9a-f]{2})|\\\\u([0-9a-f]{4})/i', function($val){
            return html_entity_decode(
                empty($val[3])?
                    sprintf('&#x%x;', ((hexdec($val[1])&0x3FF)<<10)+(hexdec($val[2])&0x3FF)+0x10000):
                    '&#x'.$val[3].';',
                ENT_NOQUOTES, 'utf-8'
            );
        }, json_encode($data));
    }
    /**
	 * If a call is made to an unknown method attempt to connect
	 * it to a specific plug-in with the same name thus allowing 
	 * plug-in developers the ability to extend classes.
	 *
	 * @param string $sMethod is the name of the method
	 * @param array $aArguments is the array of arguments of being passed
	 */
	public function __call($sMethod, $aArguments)
	{
		/**
		 * Check if such a plug-in exists and if it does call it.
		 */
		if ($sPlugin = Phpfox_Plugin::get('cars.service_app_process__call'))
		{
			return eval($sPlugin);
		}
			
		/**
		 * No method or plug-in found we must throw a error.
		 */
		Phpfox_Error::trigger('Call to undefined method ' . __CLASS__ . '::' . $sMethod . '()', E_USER_ERROR);
	}	
}

?>
