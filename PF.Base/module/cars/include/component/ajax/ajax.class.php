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
 * @version 		$Id: ajax.class.php 3642 2011-12-02 10:01:15Z Bolot_Kalil $
 */
class Cars_Component_Ajax_Ajax extends Phpfox_Ajax
{
	public function displayFilters(){

        Phpfox::getBlock('cars.type-child', array('type_id' => $this->get('type_id'), 'mark_id'=>$this->get('mark_id')));
        $this->remove('#model_container')->remove('#mark_container')->after('#type_container', $this->getContent(false));
        $this->call("$('#js_type_loader').css({'display':'none'});");
        $this->call('$("#mark").selectize({sortField: {field: "text",direction: "asc"}});$("#model").selectize({sortField: {field: "text",direction: "asc"}});');
    }

    public function displayAddFilters(){

        Phpfox::getBlock('cars.addfilters', array('type_id' => $this->get('type_id'), 'mark_id'=>$this->get('mark_id')));
        $this->remove('#mark_container')->remove('#model_container')->after('#type_container', $this->getContent(false));
        $this->call("$('#js_type_loader').css({'display':'none'});");
        $this->call('$("#mark").selectize({sortField: {field: "text",direction: "asc"}});$("#model").selectize({sortField: {field: "text",direction: "asc"}});');
    }

    public function displayAddFiltersAdmin(){

        Phpfox::getBlock('cars.filtersadmin', array('type_id' => $this->get('type_id'), 'mark_id'=>$this->get('mark_id')));
        $this->remove('#mark_container')->remove('#model_container')->after('#type_container', $this->getContent(false));
        $this->call("$('#js_type_loader').css({'display':'none'});");
    }

    public function edit()
    {
        if (($sContent = Phpfox::getService('cars.custom')->getFieldForEdit($this->get('field_id'), $this->get('item_id'), $this->get('edit_user_id'))))
        {
            $this->call('$(\'#js_custom_field_' . $this->get('field_id') . '\').html(\'' . str_replace(array("'", '<br />'), array("\'", "\n"), $sContent) . '\');')
                ->show('#js_custom_field_' . $this->get('field_id'));
            // ->hide('#js_custom_loader_' . $this->get('field_id'))
            // ->show('#js_custom_link_' . $this->get('field_id'));
        }
    }

    public function update()
    {
        if (($sContent = Phpfox::getService('cars.custom.process')->updateField($this->get('field_id'), $this->get('item_id'), $this->get('edit_user_id'), $this->get('custom_field_value'))))
        {
            $this->hide('#js_custom_field_' . $this->get('field_id'))
                ->html('#js_custom_content_' . $this->get('field_id'), $sContent)
                ->show('#js_custom_content_' . $this->get('field_id'));
        }
        else
        {
            $this->call('$(\'#js_custom_field_' . $this->get('field_id') . '\').parents(\'.block:first\').remove();');
        }
    }

    public function addGroup()
    {
        if (($iId = Phpfox::getService('cars.custom.group.process')->add($this->get('val'))) && ($aGroup = Phpfox::getService('cars.custom.group')->getGroup($iId)))
        {
            $this->append('#js_group_listing', '<option value="' . $aGroup['group_id'] . '" selected="selected">' . Phpfox::getPhrase($aGroup['phrase_var_name']) . '</option>')
                ->hide('#js_group_holder')
                ->show('#js_field_holder');
        }
    }

    public function toggleActiveGroup()
    {
        if (Phpfox::getService('cars.custom.group.process')->toggleActivity($this->get('id')))
        {
            $this->call('$Core.custom.toggleGroupActivity(' . $this->get('id') . ')');
        }
    }

    public function toggleActiveField()
    {
        if (Phpfox::getService('cars.custom.process')->toggleActivity($this->get('id')))
        {
            $this->call('$Core.custom.toggleFieldActivity(' . $this->get('id') . ')');
        }
    }

    public function deleteField()
    {
        if (Phpfox::getService('cars.custom.process')->delete($this->get('id')))
        {
            $this->call('$(\'#js_field_' . $this->get('id') . '\').parents(\'li:first\').remove();');
        }
    }

    public function deleteOption()
    {
        if (Phpfox::getService('cars.custom.process')->deleteOption($this->get('id')))
        {
            $this->call('$(\'#js_current_value_' . $this->get('id') . '\').remove();');
        }
        else
        {
            $this->alert(Phpfox::getPhrase('custom.could_not_delete'));
        }
    }

    public function approve(){
        Phpfox::isUser(true);
        Phpfox::getUserParam('cars.can_approve_cars', true);

        if (Phpfox::getService('cars.process')->approve($this->get('id')))
        {
            $this->alert(Phpfox::getPhrase('cars.car_has_been_approved'), Phpfox::getPhrase('cars.car_approved'), 300, 100, true);
//            $this->hide('#js_item_bar_approve_image');
            $this->hide('.js_moderation_off');
            $this->show('.js_moderation_on');
        }
    }

    public function approveAdminCp(){
        Phpfox::isUser(true);
        Phpfox::getUserParam('cars.can_approve_cars', true);

        if (Phpfox::getService('cars.process')->approveAdminCp($this->get('approve'), $this->get('car_id'))){
            $this->call(sprintf('$("#global_ajax_message").text("%s").fadeIn(1000).fadeOut(1000);', html_entity_decode(Phpfox::getPhrase('cars.car_has_been_approved'), ENT_QUOTES, 'UTF-8')));
        }else{
            $this->call(sprintf('$("#global_ajax_message").text("%s").fadeIn(1000).fadeOut(1000);', html_entity_decode(Phpfox::getPhrase('cars.car_not_approved'), ENT_QUOTES, 'UTF-8')));
        }
    }

//    public function rotate()
//    {
//        Phpfox::isUser(true);
//        if ($aPhoto = Phpfox::getService('cars.process')->rotate($this->get('photo_id'), $this->get('cars_cmd')))
//        {
//            $this->call('window.location.href = \'' . Phpfox::getLib('url')->makeUrl('cars', array($aPhoto['car_id'], 'photo'=>$aPhoto['photo_id'])) . '\';');
//        }
//    }

    /*

    public function preview()
    {
        Phpfox::getBlock('cars.preview', array('sText' => $this->get('text')));
    }*/

    public function inLineDeletePhoto(){
        Phpfox::isUser(true);
        if (Phpfox::getService('cars.process')->deletePhoto($this->get('photo_id')))
        {
            $this->call('$("#js_photo_'.$this->get('photo_id').'").fadeOut(1000);');
        }else{
            $this->alert(Phpfox::getPhrase('cars.failed_to_delete_photo'));
        }
    }
    public function setAsFeatured()
    {
        if (Phpfox::getService('cars.process')->setAsFeatured($this->get('car_id')))
        {
            $this->call('$("#js_set_as_featured_'.$this->get('car_id').'").hide();');
            $this->call('$("#js_unset_as_featured_'.$this->get('car_id').'").show();');
            $this->alert(Phpfox::getPhrase('cars.successfully_set_as_featured'));
        }else{
            $this->alert(Phpfox::getPhrase('cars.failed_to_set_unset'));
        }
    }

    public function unsetAsFeatured()
    {
        if (Phpfox::getService('cars.process')->unsetAsFeatured($this->get('car_id')))
        {
            $this->call('$("#js_set_as_featured_'.$this->get('car_id').'").show();');
            $this->call('$("#js_unset_as_featured_'.$this->get('car_id').'").hide();');
            $this->alert(Phpfox::getPhrase('cars.successfully_unset_featured'));
        }else{
            $this->alert(Phpfox::getPhrase('cars.failed_to_set_unset'));
        }
    }
    
    public function setAsMainPhoto(){
        if (Phpfox::getService('cars.process')->setAsMainPhoto($this->get('photo_id')))
        {
//            $this->call('$("#js_set_as_main_photo").hide();');
            $this->call('$("#main_photo").fadeIn(500);');
        }else{
            $this->alert(Phpfox::getPhrase('cars.failed_to_set_the_main_photo'));
        }
    }

    public function typeOrdering()
    {
        $aVals = $this->get('val');
        Phpfox::getService('core.process')->updateOrdering(array(
                'table' => 'cars_type',
                'key' => 'type_id',
                'values' => $aVals['ordering']
            )
        );

        Phpfox::getLib('cache')->remove('cars', 'substr');
    }

    public function markOrdering()
    {
        $aVals = $this->get('val');
        Phpfox::getService('core.process')->updateOrdering(array(
                'table' => 'cars_mark',
                'key' => 'mark_id',
                'values' => $aVals['ordering']
            )
        );

        Phpfox::getLib('cache')->remove('cars', 'substr');
    }
    public function modelOrdering()
    {
        $aVals = $this->get('val');
        Phpfox::getService('core.process')->updateOrdering(array(
                'table' => 'cars_model',
                'key' => 'model_id',
                'values' => $aVals['ordering']
            )
        );

        Phpfox::getLib('cache')->remove('cars', 'substr');
    }
    public function locationOrdering()
    {
        $aVals = $this->get('val');
        Phpfox::getService('core.process')->updateOrdering(array(
                'table' => 'cars_location',
                'key' => 'country_iso',
                'values' => $aVals['ordering']
            )
        );

        Phpfox::getLib('cache')->remove('cars', 'substr');
    }
    public function changePrint(){
        if (Phpfox::getService('cars.process')->changePrint($this->get('car_id'), $this->get('to_print'))){
            $this->call(sprintf('$("#global_ajax_message").text("%s").fadeIn(1000).fadeOut(1000);', Phpfox::getPhrase('core.saving')));
        }else{
            $this->call(sprintf('$("#global_ajax_message").text("%s").fadeIn(1000).fadeOut(1000);', Phpfox::getPhrase('cars.failed_to_save')));
        }
    }
    public function moreCars(){
        $aIds = explode(',', $this->get('cars'));

        if(!empty($aIds)){

            $aCars = Phpfox::getService('cars.cars')->getRecomendedCars($this->get('carid'), $aIds);

            if (!empty($aCars)){

                Phpfox::getBlock('cars.cars', array('isAjax'=>true, 'carId' => $this->get('carid'), 'aCars'=>$aCars));
                $this->before('.car_more_button', $this->getContent(false));
                if (sizeof($aCars)<Phpfox::getParam('cars.recomended_cars_size')){
                    $this->call("$('.car_more_button').hide();");
                }else{
                    $this->call("$('.car_more_button').show();");
                }

            }
        }
        $this->call("$('#js_more_loader').css('visibility','hidden');");
    }

    public function play()
    {
        $sId = $this->get('id');
        if (!empty($sId)){
            $this->setTitle(Phpfox::getPhrase('link.viewing_video'));
            echo '<div class="t_center">';
            printf('<object width="640" height="360" data="http://www.youtube.com/v/%s" type="application/x-shockwave-flash"><param name="src" value="http://www.youtube.com/v/%s" /></object>', $sId, $sId);
            //echo '<iframe width="420" height="315" src="'.$sUrl.'" frameborder="0" allowfullscreen></iframe>';
            echo '</div>';
        }
    }
}

?>
