<?php
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Bolot_Kalil
 * @package 		Phpfox
 * @version 		$Id: index.html.php 3332 2011-10-20 12:50:29Z Bolot_Kalil $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>

<form method="post" action="{url link="admincp.cars"}">
<div class="table_header">
    {phrase var='cars.admin_menu_manage_cars'}
</div>
<div class="table" id="title">
    <div class="table_left">
        {phrase var='cars.title'}:
    </div>
    <div class="table_right">
        {filter key='title'}
    </div>
    <div class="clear"></div>
</div>
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12">
    <div class="col-xs-4 col-sm-4 col-md-4">
        <div class="table">
            <div class="table_left">
                {phrase var='cars.location'}:
            </div>
            <div class="table_right">
                {filter key='location'}
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-4">
        <div class="table">
            <div class="table_left">
                {phrase var='cars.type'}:
            </div>
            <div class="table_right">
                {filter key='type'}
                <?php
                printf('<span id="js_type_loader" style="visibility:hidden;"><img src="%s" class="v_middle" /></span>', Phpfox::getLib('template')->getStyle('image', 'ajax/small.gif'));
                ?>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-4">
        <div class="table" id="release_container">
            <div class="table_left">
                {phrase var='cars.release_year'}:
            </div>
            <div class="table_right">
                <div class="pull-left">{filter key='from'}</div><div class="pull-right">{filter key='to'}</div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12">
    <div class="col-xs-4 col-sm-4 col-md-4">
        <div class="table">
            <div class="table_left">
                {phrase var='cars.approved'}:
            </div>
            <div class="table_right">
                <select name="search[approved]" style="width: 67px;margin-left: 1px;">

                    <option value="0" {if $sApproved == 0} selected="selected" {/if}>{phrase var='cars.yes'}</option>
                    <option value="1" {if $sApproved == 1} selected="selected" {/if}>{phrase var='cars.no'}</option>
                    <option value="" {if $sApproved == ''} selected="selected" {/if}>{phrase var='core.any'}</option>
                </select>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-4">
        <div class="table">
            <div class="table_left">
                {phrase var='cars.display'}:
            </div>
            <div class="table_right">
                {filter key='display'}
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-4">
        <div class="table">
            <div class="table_left">
                {phrase var='cars.is_featured'}:
            </div>
            <div class="table_right">
                <select name="search[is_featured]" style="width: 67px;margin-left: 1px;">
                    <option value="1" {if $sIsFeatured == 1} selected="selected" {/if}>{phrase var='cars.yes'}</option>
                    <option value="0" {if $sIsFeatured == 0} selected="selected" {/if}>{phrase var='cars.no'}</option>
                    <option value="" {if $sIsFeatured == ''} selected="selected" {/if}>{phrase var='core.any'}</option>
                </select>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12">
    <div class="col-xs-4 col-sm-4 col-md-4">
        <div class="table">
            <div class="table_left">
                {phrase var='cars.sort_results_by'}:
            </div>
            <div class="table_right">
                {filter key='sort'} {filter key='sort_by'}
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-4">
        <div class="table">
            <div class="table_left">
                {phrase var='cars.phone'}:
            </div>
            <div class="table_right">
                {filter key='phone_number'}
                <div id="zip_container" class="clear">
                    <div>{phrase var='cars.of_zip'}:</div>
                    <div class="table_right">
                        {filter key='zip'}
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-4">
        <div class="table" id="price_container">
            <div class="table_left">
                {phrase var='cars.price'}:
            </div>
            <div class="table_right" >
                {filter key='priceFrom'} - {filter key='priceTo'}
                <div>{phrase var='cars.currency'}:</div>
                <div class="table_right">
                    {filter key='currencies'}
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
</div>
<div class="table_clear">
    <input type="submit" name="search[submit]" value="{phrase var='core.submit'}" class="button" />
    <input type="submit" name="search[reset]" value="{phrase var='core.reset'}" class="button" />
</div>
</form>

{if count($aCars)}
{pager}
<form method="post" action="{url link='current'}">
    <table>
        <tr>
            <th style="width:10px;"><input type="checkbox" name="val[id]" value="" id="js_check_box_all" class="main_checkbox" /></th>
            <th>#</th>
            <th>{phrase var='cars.title'}</th>
            <th>{phrase var='cars.location'}</th>
            <th>{phrase var='cars.type'}</th>
            <th>{phrase var='cars.mark'}</th>
            <th>{phrase var='cars.model'}</th>
            <th>{phrase var='cars.release_year'}</th>
            <th>{phrase var='cars.add_date'}</th>
            <th>{phrase var='cars.price'}</th>
            <th>{phrase var='cars.currency'}</th>
            <th>{phrase var='cars.phone'}</th>
            <th>{phrase var='cars.of_zip'}</th>
            <th>{phrase var='cars.approved'}</th>
            <th>{phrase var='cars.is_featured'}</th>
        </tr>
        {foreach from=$aCars key=iKey item=aCar}
        <tr id="js_row{$aCar.car_id}" class="checkRow{if is_int($iKey/2)} tr{else}{/if}">
            <td><input type="checkbox" name="id[]" class="checkbox" value="{$aCar.car_id}" id="js_id_row{$aCar.car_id}" /></td>
            <td>{if !empty($aCar.car_id)}{$aCar.car_id}{else}-{/if}</td>
            <td id="js_cars_edit_title{$aCar.car_id}"><a {if Phpfox::isAdmin() && isset($aCar.view_id) && $aCar.view_id==1} style="color: red;" {/if} href="{permalink module='cars' id=$aCar.car_id title=$aCar.title}" class="quickEdit" id="js_car{$aCar.car_id}">{$aCar.title|convert|clean}</a></td>
            <td>{if !empty($aCar.location_name)}{$aCar.location_name}({$aCar.location_iso}){else}-{/if}</td>
            <td>{if !empty($aCar.type_id)}{$aCar.type_id}{else}-{/if}</td>
            <td>{if !empty($aCar.mark_id)}{$aCar.mark_id}{else}-{/if}</td>
            <td>{if !empty($aCar.model_id)}{$aCar.model_id}{else}-{/if}</td>
            <td>{if !empty($aCar.release_year)}{$aCar.release_year}{else}-{/if}</td>
            <td>{if !empty($aCar.date)}{$aCar.date}{else}-{/if}</td>
            <td>{if !empty($aCar.price)}{$aCar.price}{else}-{/if}</td>
            <td>{if !empty($aCar.currency)}{$aCar.currency}{else}-{/if}</td>
            <td>{if !empty($aCar.phone_number)}{$aCar.phone_number}{else}-{/if}</td>
            <td>{if !empty($aCar.zip)}{$aCar.zip}{else}-{/if}</td>
            <td>{if isset($aCar.view_id)}
                {phrase var='cars.yes'}<br/><input onclick="$.ajaxCall('cars.approveAdminCp', 'approve=0&car_id={$aCar.car_id}');" {if empty($aCar.view_id)} checked="checked" {/if} type="radio" name="approve{$aCar.car_id}" value="0">
                <br>
                {phrase var='cars.no'}<br/><input onclick="$.ajaxCall('cars.approveAdminCp', 'approve=1&car_id={$aCar.car_id}');" {if !empty($aCar.view_id)} checked="checked" {/if} type="radio" name="approve{$aCar.car_id}" value="1">
                {/if}</td>
            <td>
                {if isset($aCar.is_featured)}
                {phrase var='cars.no'}<br/>
                <input onclick="$.ajaxCall('cars.setAsFeatured', 'car_id={$aCar.car_id}');"
                       {if empty($aCar.is_featured)} checked="checked" {/if} type="radio"
                       name="featured{$aCar.car_id}" value="1">
                <br>
                {phrase var='cars.yes'}<br/>
                <input onclick="$.ajaxCall('cars.unsetAsFeatured', 'car_id={$aCar.car_id}');"
                       {if !empty($aCar.is_featured)} checked="checked" {/if} type="radio"
                       name="featured{$aCar.car_id}" value="0">
                {/if}
            </td>
        </tr>
        {/foreach}
    </table>
    <div class="table_bottom">
        <input type="submit" name="delete" value="{phrase var='cars.delete_selected'}" class="sJsConfirm delete button sJsCheckBoxButton disabled" disabled="true" />
    </div>
</form>
{pager}
{else}
<p>{phrase var='cars.ars_not_found'}</p>

{/if}
