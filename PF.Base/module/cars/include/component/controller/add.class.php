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
 * @version 		$Id: index.class.php 7264 2014-04-09 21:00:49Z Fern $
 */
class Cars_Component_Controller_Add extends Phpfox_Component
{
    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
        Phpfox::getLib('setting')->setParam('cars.dir_photo', Phpfox::getParam('core.dir_pic') . 'cars'.PHPFOX_DS);
        Phpfox::getLib('setting')->setParam('cars.url_photo', Phpfox::getParam('core.url_pic') . 'cars/');
        
        $bIsEdit = false;

//        $bCanEditPersonalData = true;

        if (($iEditId = $this->request()->getInt('id')))
        {

            $oCars = Phpfox::getService('cars.process');
            list($aRow, $aPhotos) = $oCars->getCarForEdit($iEditId);
            $bIsEdit = true;

            $this->template()->assign(array(
//                    'aRow' => $aRow,
                    'aPhotos' => $aPhotos,
//                    'aTypes' => Phpfox::getService('cars')->getTypes(),
                    'aMarksEdit' => Phpfox::getService('cars')->getMarks($aRow['type_id']),
                    'aModelsEdit' => Phpfox::getService('cars')->getModels($aRow['mark_id']),
                    'aSettings' => Phpfox::getService('cars.custom')->getForEdit(array('cars_advanced_filter'), $aRow['car_id'], null, false, $aRow['car_id'])
                )
            );


        }
        else
        {
//            if (!Phpfox::getService('cars.employee')->isEmployee()){
            Phpfox::getUserParam('cars.add_new_car', true);
//            }
        }
        $aValidation = array(
            'title' => array(
                'def' => 'required',
                'title' => Phpfox::getPhrase('cars.fill_title_for_car')
            ),
            'location_iso' => array(
                'def' => 'required',
                'title' => Phpfox::getPhrase('cars.select_location_of_the_car')
            ),
            'type' => array(
                'def' => 'required',
                'title' => Phpfox::getPhrase('cars.select_type_of_the_car')
            ),
            'release' => array(
                'def' => 'required',
                'title' => Phpfox::getPhrase('cars.select_release_year_of_the_car')
            ),
            'phone_number' => array(
                'def' => 'required',
                'title' => Phpfox::getPhrase('cars.provide_phone_number')
            ),
            'price' => array(
                'def' => 'required',
                'title' => Phpfox::getPhrase('cars.enter_the_price_of_the_car')
            )
        );

        $aVals = $this->request()->getArray('val');
        $aCustom = $this->request()->get('custom');
        $aImages = $this->request()->get('image');

        if (isset($aVals['mark'])){

            $aValidation['mark'] = array('title'=>Phpfox::getPhrase('cars.provide_car_mark'), 'def'=>'required');
            $this->template()->assign(array(
                    'aMarks' => !empty($aVals['type'])?Phpfox::getService('cars')->getMarks($aVals['type']):'',
                    'sSelectedMarkId' => (int) $aVals['mark']
                )
            );

        }

        if(isset($aVals['model'])){

            $this->template()->assign(array(
                    'aModels' => !empty($aVals['mark'])?Phpfox::getService('cars')->getModels($aVals['mark']):'',
                    'sSelectedModelId' => (int) $aVals['model']
                )
            );

        }
//        if (!empty($aCustom)){
        if (($aCustomFields = Phpfox::getService('cars.custom')->getFields('cars_main_browse'))){
            foreach ($aCustomFields as $aCustomField)
            {
                if (empty($aCustom[$aCustomField['field_id']]) && $aCustomField['is_required'])
                {
                    $aValidation['custom'.$aCustomField['field_id']] = array('def' => 'required','title'=>Phpfox::getPhrase('cars.custom_provide_name', array('name'=>Phpfox::getPhrase($aCustomField['phrase_var_name']))));
                }
            }
        }
//        }


        if (!$bIsEdit){
            if (isset($aImages['size'][0]) && empty($aImages['size'][0]) && empty($aImages['type'][0])){

                $aValidation['image'] = array('def' => 'required','title'=>Phpfox::getPhrase('cars.select_image_file'));
            }
        }

        if (Phpfox::isModule('captcha') && Phpfox::getUserParam('cars.captcha_on_car_add'))
        {
            $aValidation['image_verification'] = Phpfox::getPhrase('captcha.complete_captcha_challenge');
        }

        (($sPlugin = Phpfox_Plugin::get('cars.component_controller_add_process_validation')) ? eval($sPlugin) : false);


        $oValid = Phpfox::getLib('validator')->set(array(
                'sFormName' => 'core_js_cars_form',
                'aParams' => $aValidation
            )
        );

        $this->template()
            ->setBreadcrumb(Phpfox::getPhrase('cars.cars'), $this->url()->makeUrl('cars'))
            ->setBreadcrumb((!empty($iEditId) ? Phpfox::getPhrase('cars.editing_car') . ': ' . Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getService('core')->getEditTitleSize(), '...') : Phpfox::getPhrase('cars.adding_a_new_car')), ($iEditId > 0 ? $this->url()->makeUrl('cars', array('add', 'id' => $iEditId)) : $this->url()->makeUrl('cars', array('add'))), true);


        if (!empty($aVals))
        {
            if ($oValid->isValid($aVals))
            {
                $sMessage = Phpfox::getPhrase('cars.your_car_has_been_added');


                if (($iFlood = Phpfox::getUserParam('cars.flood_control_car')) !== 0)
                {
                    $aFlood = array(
                        'action' => 'last_post', // The SPAM action
                        'params' => array(
                            'field' => 'time_stamp', // The time stamp field
                            'table' => Phpfox::getT('cars'), // Database table we plan to check
                            'condition' => 'user_id = ' . Phpfox::getUserId(), // Database WHERE query
                            'time_stamp' => $iFlood * 60 // Seconds);
                        )
                    );

                    // actually check if flooding
                    if (Phpfox::getLib('spam')->check($aFlood))
                    {
                        Phpfox_Error::set(Phpfox::getPhrase('cars.your_are_posting_a_little_too_soon') . ' ' . Phpfox::getLib('spam')->getWaitTime());
                    }
                }

                if (Phpfox_Error::isPassed() && !$bIsEdit)
                {
                    $iId = Phpfox::getService('cars.process')->add(Phpfox::getUserId(), $aVals, $aCustom);
                }

                // Update a car

                if (isset($aVals['update']) && isset($aRow['car_id']) && $bIsEdit)
                {
                    // Update the car
                    $iId = Phpfox::getService('cars.process')->update($aRow['car_id'], $aRow['user_id'], $aVals, $aCustom);
                    $sMessage = Phpfox::getPhrase('cars.car_updated');
                }

                if (isset($iId) && $iId)
                {
                    Phpfox::getLib('url')->send('cars', array($iId, $aVals['title']), $sMessage);
                }
            }
        }
        if ((!isset($iId) && empty($iId)) || $bIsEdit){

            if ($bIsEdit){
                $aCustom = array_merge($aRow, $aCustom?$aCustom:array());
            }

            $this->template()->assign(array(
                'aVals' => $aVals,
                'aForms'=> $aCustom,
                'aImages'=>$aImages
            ));
        }

        $aCustomFields = Phpfox::getService('cars.custom')->getForPublic('cars_main_browse');
        $iMaxFileSize = (Phpfox::getUserParam('cars.photo_max_upload_size') === 0 ? null : ((Phpfox::getUserParam('cars.photo_max_upload_size') / 1024) * 1048576));
        $bCantUploadMore = (Phpfox::getParam('cars.total_photo_inputs') > Phpfox::getUserParam('cars.max_images_per_upload'));
        $aCurrencies = Phpfox::getService('core.currency')->get();
        foreach ($aCurrencies as $iKey => $aCurrency)
        {
            $aCurrencies[$iKey] = $iKey;
        }
        $this->template()
            ->setTitle((!empty($iEditId) ? Phpfox::getPhrase('cars.editing_car') . ': ' . $aRow['title'] : Phpfox::getPhrase('cars.adding_a_new_car')))
            ->setFullSite()
            ->assign(array(
                    'sCreateJs' => $oValid->createJS(),
                    'sGetJsForm' => $oValid->getJsForm(),
                    'bIsEdit' => $bIsEdit,
                    'aCustomFields' => $aCustomFields,
                    'aLocations' => Phpfox::getService('cars.location')->get(),
                    'aTypes' => Phpfox::getService('cars')->getTypes(),
                    'aReleaseYears' => Phpfox::getService('cars')->getReleaseYears(),
                    'iMaxFileSize' => $iMaxFileSize,
                    'sNameJsValidation' => $oValid->getJsForm(false),
                    'aCurrencies' => $aCurrencies
                )
            )
            ->setEditor(array('wysiwyg' => Phpfox::getUserParam('cars.can_use_editor_on_car')))
            ->setHeader('cache', array(
                    'selectize.min.js' => 'module_cars',
                    'selectize.default.css' => 'module_cars',
                    'jquery/plugin/jquery.highlightFade.js' => 'static_script',
                    'switch_legend.js' => 'static_script',
                    'switch_menu.js' => 'static_script',
                    'quick_edit.js' => 'static_script',
                    'browse.css' => 'style_css',
                    'add.js' => 'module_cars',
                    'progress.js' => 'static_script',
                    'elastislide.css' => 'module_cars',
					'jquery.easing.1.3.js' => 'module_cars',
					'jquery.elastislide.js' => 'module_cars'
                )
            );
        $this->template()->setHeader('<script type="text/javascript">$Behavior.photoProgressBarSettings = function(){ if ($Core.exists(\'#js_photo_form_holder\')) { oProgressBar = {html5upload: false, holder: \'#js_photo_form_holder\', progress_id: \'#js_progress_bar\', uploader: \'#js_photo_upload_input\', add_more: ' . ($bCantUploadMore ? 'false' : 'true') . ', max_upload: ' . Phpfox::getUserParam('cars.max_images_per_upload') . ', total: 1, frame_id: \'js_upload_frame\', file_id: \'image[]\', valid_file_ext: new Array(\'gif\', \'png\', \'jpg\', \'jpeg\')}; $Core.progressBarInit(); } }</script>');

    }
}

?>
