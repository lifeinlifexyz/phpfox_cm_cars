<?php
/**
 * [PHPFOX_HEADER]
 *
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		CodeMake.Org
 * @package 		Phpfox
 * @version 		$Id: country-child.html.php 982 2009-09-16 08:11:36Z Bolot_Kalil $
 */

defined('PHPFOX') or exit('NO DICE!');
?>
{if isset($aCars) && count($aCars)}
{literal}
<style>
    .cars-caption-recommended-bold{
        font-weight: bold;
        white-space: nowrap;
        overflow: hidden;
        padding-left: 0px !important;
    }
    .cars-caption-recommended-value{
        margin-bottom: 15px;
        word-wrap: break-word;
    }
    .car_more_button{
        text-align: center;
        cursor: pointer;
        color: #56a8e3;
        font-weight: bold;
        text-decoration: underline;
    }
    #cars_recommended_title{
        padding-left: 0px;
        word-wrap: break-word;
    }
    #cars_recommended_image{
        margin-bottom: 10px;
    }
</style>

{/literal}
{foreach from=$aCars name=cars item=aCar}
<div class="cars-car-id" data-car-id="{$aCar.car_id}">
    {if Phpfox::getParam('cars.car_browse_display_results_default') == 'name_photo_detail'}
    <div class="row" id="js_parent_car_{$aCar.car_id}">
        <div class="col-xs-6 col-sm-6 col-md-6" id="cars_recommended_image">
            <a href="{permalink module='cars' id=$aCar.car_id title=$aCar.title}">
                {img class='js_mp_fix_width' path='cars.url_photo' file=$aCar.destination suffix='_150' max_width=100 max_height=100 title=$aCar.title}
            </a>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6" id="cars_recommended_title">
            <a href="{permalink module='cars' id=$aCar.car_id title=$aCar.title}">{$aCar.title}</a>
        </div>
        {if !empty($aCar.release_year)}
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="col-xs-5 col-sm-5 col-md-5 cars-caption-recommended-bold">
                {phrase var='cars.release_year'}:
            </div>
            <div class="col-xs-7 col-sm-7 col-md-7 cars-caption-recommended-value">
                {$aCar.release_year}
            </div>
        </div>
        {/if}
        {if !empty($aCar.price)}
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="col-xs-5 col-sm-5 col-md-5 cars-caption-recommended-bold">
                {phrase var='cars.price'}:
            </div>
            <div class="col-xs-7 col-sm-7 col-md-7 cars-caption-recommended-value">
                {$aCar.price} {$aCar.currency}
            </div>
        </div>
        {/if}
        {if !empty($aCar.phone_number)}
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="col-xs-5 col-sm-5 col-md-5 cars-caption-recommended-bold">
                {phrase var='cars.phone'}:
            </div>
            <div class="col-xs-7 col-sm-7 col-md-7 cars-caption-recommended-value">
                {$aCar.phone_number}
            </div>
        </div>
        {/if}
        {if !empty($aCar.country_iso) && !empty($aCar.name)}
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="col-xs-5 col-sm-5 col-md-5 cars-caption-recommended-bold">
                {phrase var='cars.location'}:
            </div>
            <div class="col-xs-7 col-sm-7 col-md-7 cars-caption-recommended-value">
                {$aCar.name|clean}
            </div>
        </div>
        {/if}
        {if !empty($aCar.mark_id)}
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="col-xs-5 col-sm-5 col-md-5 cars-caption-recommended-bold">
                {phrase var='cars.mark'}:
            </div>
            <div class="col-xs-7 col-sm-7 col-md-7 cars-caption-recommended-value">
                {$aCar.mark_id|parse|shorten:25:'..'}
            </div>
        </div>
        {/if}
        {if !empty($aCar.model_id)}
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="col-xs-5 col-sm-5 col-md-5 cars-caption-recommended-bold">
                {phrase var='cars.model'}:
            </div>
            <div class="col-xs-7 col-sm-7 col-md-7 cars-caption-recommended-value">
                {$aCar.model_id|parse|shorten:25:'..'}
            </div>
        </div>
        {/if}
    </div>
    {else}
    <div class="row" id="js_parent_car_{$aCar.car_id}">
        <div class="col-xs-6 col-sm-6 col-md-6" id="cars_recommended_image">
        <a href="{permalink module='cars' id=$aCar.car_id title=$aCar.title}">
            {img class='js_mp_fix_width' path='cars.url_photo' file=$aCar.destination suffix='_150' max_width=100 max_height=100 title=$aCar.title}
        </a>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6" id="cars_recommended_title">
            <a href="{permalink module='cars' id=$aCar.car_id title=$aCar.title}">{$aCar.title}</a>
        </div>
    </div>
    <div class="clear"></div>
    {/if}
</div>
{/foreach}
{if !empty($iCarId)}
{literal}
<script type="text/javascript">
    $Behavior.onLoadPageIndex = function() {

        $('.car_more_button').click(function () {
            $('#js_more_loader').css('visibility','visible');
            var cars = [];
            $('.cars-car-id').each(function (key, value) {
                cars[key] = $(this).attr('data-car-id');
            });
            $('.car_more_button').fadeOut(100);
            $.ajaxCall('cars.moreCars', 'carid='+{/literal}{$iCarId}{literal}+'&cars='+cars);
        });
    }
</script>
{/literal}

<div class="car_more_button">{phrase var='cars.more'}</div>
<span id="js_more_loader" style="visibility:hidden;">
    <?php
    printf('<img src="%s" class="v_middle" />', Phpfox::getLib('template')->getStyle('image', 'ajax/small.gif'));
    ?>
</span>
{/if}
<div class="clear"></div>

{else}
<p>{phrase var='cars.ars_not_found'}</p>

{/if}