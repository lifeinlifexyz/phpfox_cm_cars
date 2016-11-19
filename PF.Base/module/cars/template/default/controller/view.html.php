<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		CodeMake.Org
 * @package  		Module_Cars
 * @version 		$Id: view.html.php 5844 2013-05-09 08:00:59Z Bolot_Kalil $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 
$aForms = $this->getVar('aForms');
?>
{if isset($aForms.view_id) && $aForms.view_id == 1}
<div class="message js_moderation_off" style="margin-bottom: 10px;">
    {phrase var='cars.car_is_pending_approval'}
</div>
{/if}

<div class="item_view cars_item_view">
<div id="js_cars_outer_content" class="row">
    <div class="col-xs-11 col-md-11">
        {phrase var='cars.full_name_s_car_from_time_stamp' time_stamp=$aForms.time_stamp|convert_time full_name=$aForms|user:'':'':35:'':'author'}
    </div>
    {if (Phpfox::getUserParam('cars.can_edit_own_car') && $aForms.user_id == Phpfox::getUserId()) || (Phpfox::getUserParam('cars.can_delete_own_car') && $aForms.user_id == Phpfox::getUserId()) || Phpfox::isAdmin()}
    <div class="col-xs-1 col-md-1">
        <div class="item_bar_action_holder">
            <a role="button" data-toggle="dropdown" href="#" class="item_bar_action"><span>{phrase var='cars.actions'}</span></a>
            <ul class="dropdown-menu">
                {if $aForms.view_id == '1' && Phpfox::getUserParam('cars.can_approve_cars')}
                <li><a href="#" onclick="$(this).hide(); $.ajaxCall('cars.approve', 'inline=true&amp;id={$aForms.car_id}'); return false;">{phrase var='cars.approve'}</a></li>
                {/if}
                {template file='cars.block.menu'}
            </ul>
        </div>
    </div>
    {/if}
    <div class="clear"></div>
    <div class="row t_center">
        {if $aPhotoStream.total > 1}
        {phrase var='cars.car_current_of_total' current=$aPhotoStream.current total=$aPhotoStream.total}
        {/if}
    </div>
    <div class="clear"></div>
    <div class="t_center" id="js_photo_view_holder">

        {* if (Phpfox::getUserParam('cars.can_edit_own_car') && $aForms.user_id == Phpfox::getUserId()) || Phpfox::getService('cars.employee')->isEmployee() || Phpfox::isAdmin()}
        <div class="photo_rotate">
            <ul>
                <li>
                    <a href="#" onclick="$('#menu').remove(); $('#noteform').hide(); $('#js_photo_view_image').imgAreaSelect({left_curly} hide: true {right_curly}); $('#js_photo_view_holder').hide(); $('#js_photo_view_holder_process').html($.ajaxProcess('', 'large')).height($('#js_photo_view_holder').height()).show();$.ajaxCall('cars.rotate', 'photo_id={$aForms.photo_id}&amp;cars_cmd=left&amp;currenturl=' + $('#js_current_page_url').html()); return false;" class="left js_hover_title">
                        <span class="js_hover_info">
                            {phrase var='cars.rotate_left'}
                        </span>
                    </a>
                </li>
                <li>
                    <a href="#" onclick="$('#menu').remove(); $('#noteform').hide(); $('#js_photo_view_image').imgAreaSelect({left_curly} hide: true {right_curly}); $('#js_photo_view_holder').hide(); $('#js_photo_view_holder_process').html($.ajaxProcess('', 'large')).height($('#js_photo_view_holder').height()).show();  $.ajaxCall('cars.rotate', 'photo_id={$aForms.photo_id}&amp;cars_cmd=right&amp;currenturl=' + $('#js_current_page_url').html()); return false;" class="right js_hover_title"><span class="js_hover_info">{phrase var='cars.rotate_right'}</span></a>
                </li>
            </ul>
            <div class="clear"></div>
        </div>
        {/if *}

        {if isset($aPhotoStream.next.car_id)}
        <a href="{$aPhotoStream.next.link}">
        {/if}
        <?php if (file_exists(Phpfox::getParam('cars.dir_photo').sprintf($aForms['destination'], '_500'))):?>
        <meta itemprop="image" content="{img server_id=$aForms.server_id path='cars.url_photo' file=$aForms.destination suffix='_500' return_url=true}" />
        <?php endif;?>
        <div id="main_photo" style="float: left;position: absolute; {if empty($aForms.is_main)} display:none; {/if}">
            <img src="<?php echo(Phpfox::getLib('template')->getStyle('image', 'favorite.png', 'cars'));?>" width="25" />
        </div>

        {if $aForms.user_id == Phpfox::getUserId()}
            {img id='js_photo_view_image' server_id=$aForms.server_id path='cars.url_photo' file=$aForms.destination suffix='_500' max_width=800 max_height=800 title=$aForms.title time_stamp=true onmouseover="$('.photo_next_previous .next a').addClass('is_hover_active');" onmouseout="$('.photo_next_previous .next a').removeClass('is_hover_active');"}
        {else}
            {img id='js_photo_view_image' server_id=$aForms.server_id path='cars.url_photo' file=$aForms.destination suffix='_500' max_width=800 max_height=800 title=$aForms.title onmouseover="$('.photo_next_previous .next a').addClass('is_hover_active');" onmouseout="$('.photo_next_previous .next a').removeClass('is_hover_active');"}
        {/if}

        <script type="text/javascript">
            $Behavior.autoLoadFullPhoto = function(){l}

            {if isset($iNewImageHeight)}
                $('#js_photo_view_image').attr({l}height: 'auto', width: 'auto'{r});
                {/if}

                    var sImageHeight = $('#js_photo_view_image').height();
                    var sImageWidth = $('#js_photo_view_image').width();

                    /*$('#js_photo_view_holder').css({l}
                    'position': 'absolute',
                    'left': '50%',
                    'margin-left': '-' + (sImageWidth / 2) + 'px'
                    {r});*/

                    if (sImageHeight > 0)
                    {l}
                    $('#js_photo_view_main_holder').css('height', sImageHeight);
                    {r}

                    $('#js_photo_view_image').load(function(){l}
                    $('#js_photo_view_main_holder').css('height', $('#js_photo_view_image').height());
                    {r});

                    $Behavior.autoLoadFullPhoto = function(){l}{r}
                    {r}
        </script>

        {if isset($aPhotoStream.next.car_id)}
        </a>
        {/if}

    </div>
    <div class="row" style="">
        <div class="col-xs-5 col-md-5">
            {if $aPhotoStream.total > 1 && isset($aPhotoStream.previous.car_id)}
                <a class="pull-right" href="{$aPhotoStream.previous.link}" style="font-size:16px;"><i class="fa fa-chevron-circle-left" aria-hidden="true"></i> {phrase var='cars.previous'}</a>
            {/if}
        </div>
        <div class="col-xs-2 col-md-2">
        </div>
        <div class="col-xs-5 col-md-5">
            {if $aPhotoStream.total > 1 && isset($aPhotoStream.next.car_id)}
                <a class="pull-left" href="{$aPhotoStream.next.link}" style="font-size:16px;">{phrase var='cars.next'} <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a>
            {/if}
        </div>
    </div>
    <div class="t_center" style="border-bottom: 1px solid #ebebeb;margin-bottom: 10px;margin-top: 10px;"></div>
    <div class="row">
        <div class="col-xs-12 col-md-4">
            <div class="info_holder">
                {module name='cars.detail'}
            </div>
        </div>
        <div class="col-xs-12 col-md-7">
            {if !empty($aForms.description)}
                {$aForms.description}
            {/if}

            <div style="{if $aForms.view_id != 0}display:none;{/if}">
                {module name='feed.comment'}
            </div>
        </div>
    </div>
</div>
</div>