<?php
/**
 * [PHPFOX_HEADER]
 *
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		CodeMake.Org
 * @package  		Module_User
 * @version 		$Id: browse.html.php 6960 2013-12-02 16:17:27Z Bolot_Kalil $
 * {* *}
 */

defined('PHPFOX') or exit('NO DICE!');

?>

{if isset($aCars) && count($aCars)}
{foreach from=$aCars name=cars item=aCar}
<div class="cars-index-item" itemscope itemtype="http://schema.org/Product">
    <meta itemprop="url" content="{permalink module='cars' id=$aCar.car_id title=$aCar.title}" />
    {if Phpfox::getParam('cars.car_browse_display_results_default') == 'name_photo_detail'}
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="col-xs-3 col-sm-3 col-md-3">
                <a href="{permalink module='cars' id=$aCar.car_id title=$aCar.title}">
                    {img class='js_mp_fix_width' path='cars.url_photo' file=$aCar.destination suffix='_150' max_width=100 max_height=100 title=$aCar.title}
                </a>
                {if $aCar.view_id == '1' && Phpfox::getUserParam('cars.can_approve_cars')}
                <a href="#" class="btn btn-success" onclick="$(this).hide(); $.ajaxCall('cars.approve', 'id={$aCar.car_id}'); return false;">{phrase var='cars.approve'}</a>
                {/if}
            </div>
            <div class="col-xs-9 col-sm-9 col-md-9">
                <div class="row">
                    <a {if Phpfox::isAdmin() && isset($aCar.view_id) && $aCar.view_id==1} style="color: red;" {/if} href="{permalink module='cars' id=$aCar.car_id title=$aCar.title}">{$aCar.title}{if $aCar.is_sold == 1 && ($aCar.user_id == Phpfox::getUserId() || Phpfox::isAdmin())}({phrase var='cars.sold'}){/if}</a>
                </div>
                <div class="row">
                    {if !empty($aCar.price)}
                    <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                        <div class="info">
                            <div class="info_left">
                                {phrase var='cars.price'}:
                            </div>
                            <div class="info_right" itemprop="price" content="{$aCar.price}">
                                {$aCar.price}<span itemprop="priceCurrency" content="{if isset($aCar.currency)}{$aCar.currency}{/if}"> {if isset($aCar.currency)}{$aCar.currency}{/if}</span>
                            </div>
                        </div>
                    </div>
                    {/if}
                    {if !empty($aCar.phone_number)}
                    <div class="info">
                        <div class="info_left">
                            {phrase var='cars.phone'}:
                        </div>
                        <div class="info_right">
                            {$aCar.phone_number}
                        </div>
                    </div>
                    {/if}
                    {if !empty($aCar.country_iso) && !empty($aCar.name)}
                    <div class="info">
                        <div class="info_left">
                            {phrase var='cars.location'}:
                        </div>
                        <div class="info_right">
                            {$aCar.name|clean}
                        </div>
                    </div>
                    {/if}
                    {if !empty($aCar.mark_id)}
                    <div class="info">
                        <div class="info_left">
                            {phrase var='cars.mark'}:
                        </div>
                        <div class="info_right">
                            {$aCar.mark_id|parse|shorten:25:'..'}
                        </div>
                    </div>
                    {/if}
                    {if !empty($aCar.model_id)}
                    <div class="info">
                        <div class="info_left">
                            {phrase var='cars.model'}:
                        </div>
                        <div class="info_right">
                            {$aCar.model_id|parse|shorten:25:'..'}
                        </div>
                    </div>
                    {/if}
                    {if !empty($aCar.release_year)}
                    <div class="info">
                        <div class="info_left">
                            {phrase var='cars.release_year'}:
                        </div>
                        <div class="info_right">
                            {$aCar.release_year}
                        </div>
                    </div>
                    {/if}
                    {if !empty($aCar.sFields)}
                    {$aCar.sFields}
                    {/if}
                </div>
            </div>
        </div>
    </div>
    {else}

        {if (is_int($phpfox.iteration.cars / 4))}
            <div class="row">
        {/if}
            <div class="col-xs-3 col-sm-3 col-md-3">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <a href="{permalink module='cars' id=$aCar.car_id title=$aCar.title}">{img class='js_mp_fix_width' path='cars.url_photo' file=$aCar.destination suffix='_150' max_width=100 max_height=100 title=$aCar.title}</a>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <a {if Phpfox::isAdmin() && isset($aCar.view_id) && $aCar.view_id==1} style="color: red;" {/if} href="{permalink module='cars' id=$aCar.car_id title=$aCar.title}">{$aCar.title}</a>
		{if $aCar.view_id == '1' && Phpfox::getUserParam('cars.can_approve_cars')}
                	<a href="#" class="btn btn-success" onclick="$(this).hide(); $.ajaxCall('cars.approve', 'id={$aCar.car_id}'); return false;">{phrase var='cars.approve'}</a>
                {/if}
                </div>
            </div>
        {if (is_int($phpfox.iteration.cars / 4))}
            </div>
        {/if}
    {/if}
</div>
{/foreach}


<div class="clear"></div>

{if !PHPFOX_IS_AJAX}
<div id="js_view_more_cars"></div>
{/if}

{pager}

{else}
<p>{phrase var='cars.ars_not_found'}</p>

{/if}