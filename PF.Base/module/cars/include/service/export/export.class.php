<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class Cars_Service_Export_Export extends Phpfox_Service
{
    private $_aCars;

    public function getSizeMark($iMark){
        if (empty($this->_aCars)){
            return false;
        }
        $iIteration = 0;
        foreach($this->_aCars as $iKey=>$aCar):

            if ($iMark == $aCar['mark_id']){
                $iIteration += 1;
            }
        endforeach;
        return $iIteration;
    }

    public function getFromToReleaseYear($iMark){

        if (empty($this->_aCars)){
            return false;
        }
        $aCars = array();
        foreach($this->_aCars as $iKey=>$aCar):

            if ($iMark == $aCar['mark_id']){
                $aCars[] = $aCar;
            }
        endforeach;
        $iMin = $aCars[0]['release_year'];
        $iMax = $aCars[0]['release_year'];
        foreach ($aCars as $aCar){
            if ($iMax < $aCar['release_year']){
                $iMax = $aCar['release_year'];
            }
            if($iMin > $aCar['release_year']){
                $iMin = $aCar['release_year'];
            }
        }
        return array($iMin, $iMax);
    }

    public function getCustomFields($iCarId){
        $aCustomFields = Phpfox::getService('cars.custom')->getForEdit(array('cars_advanced_filter'), $iCarId, null, false, $iCarId, true);
        $aFileds = array();
        foreach($aCustomFields as $iIndex=>$aCustom){

            if ($aCustom['var_type'] == 'select'){

                foreach ($aCustom['options'] as $iSelectKey=>$aOption){
                    if (isset($aOption['value']) && isset($aOption['selected']) && $aOption['selected'] == true){
                        $aFileds[] = array('value'=>$aOption['value'], 'phrase'=>Phpfox::getPhrase($aCustom['phrase_var_name']));
                    }
                }
            }elseif ($aCustom['var_type'] == 'multiselect'){
                foreach ($aCustom['options'] as $aOption){
                    if (isset($aOption['value']) && isset($aOption['selected']) && $aOption['selected'] == true){
                        $aFileds[] = array('value'=>$aOption['value'], 'phrase'=>Phpfox::getPhrase($aCustom['phrase_var_name']));
                    }
                }
            }elseif ($aCustom['var_type'] == 'radio'){

                foreach ($aCustom['options']  as $aOption){
                    if (isset($aOption['selected']) && $aOption['selected'] == true){
                        $aFileds[] = array('value'=>$aOption['value'], 'phrase'=>Phpfox::getPhrase($aCustom['phrase_var_name']));

                    }
                }

            }elseif ($aCustom['var_type'] == 'checkbox'){

                foreach ($aCustom['options']  as $aOption){
                    if (isset($aOption['selected']) && $aOption['selected'] == true){
                        $aFileds[] = array('value'=>$aOption['value'], 'phrase'=>Phpfox::getPhrase($aCustom['phrase_var_name']));
                    }
                }
            }elseif ($aCustom['var_type'] == 'textarea'){
                if (!empty($aCustom['value'])){
                    $aFileds[] = array('value'=>$aCustom['value'], 'phrase'=>Phpfox::getPhrase($aCustom['phrase_var_name']));
                }
            }elseif($aCustom['var_type'] == 'text'){
                if (!empty($aCustom['value'])){
                    $aFileds[] = array('value'=>$aCustom['value'], 'phrase'=>Phpfox::getPhrase($aCustom['phrase_var_name']));
                }
            }
        }
        return $aFileds;
    }

    private function getCarsSepatateMark(){
        if (empty($this->_aCars)){
            return false;
        }
        $aCurrentCar = array('mark_id'=>0);
        $aNewCars = array();
        $iIteration = 0;
        foreach ($this->_aCars as $aCar){
            if ($aCar['mark_id'] != $aCurrentCar['mark_id']){
                $iIteration += 1;
            }
            $aCurrentCar = $aCar;
            $aNewCars[$iIteration][] = $aCurrentCar;
        }
        return $aNewCars;
    }

}

?>