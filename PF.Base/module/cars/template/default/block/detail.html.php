<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		CodeMake.Org
 * @package  		Module_Photo
 * @version 		$Id: detail.html.php 4158 2012-05-11 19:00:36Z Bolot_Kalil $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<div class="row">
{foreach from=$aCarDetails name=carDetails key=sKey item=sValue}
    <div class="col-xs-12 col-sm-12 col-md-12">
        <span class="col-xs-5 col-sm-5 col-md-5 cars-caption-bold">{$sKey}:</span>
        <span class="col-xs-7 col-sm-7 col-md-7 cars-caption-value">{$sValue}</span>
    </div>
{/foreach}
{if count($aSettings)}
    {foreach from=$aSettings item=aSetting}
        {if $aSetting.var_type == 'textarea'}
            {if !empty($aSetting.value)}
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <span class="col-xs-5 col-sm-5 col-md-5 cars-caption-bold">
                        {phrase var=$aSetting.phrase_var_name}:
                    </span>
                    <span class="col-xs-7 col-sm-7 col-md-7 cars-caption-value">
                        {$aSetting.value|clean}
                    </span>
                </div>

            {/if}
        {elseif $aSetting.var_type == 'text'}
            {if !empty($aSetting.value)}
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <span class="col-xs-5 col-sm-5 col-md-5 cars-caption-bold">
                        {phrase var=$aSetting.phrase_var_name}:
                    </span>
                    <span class="col-xs-7 col-sm-7 col-md-7 cars-caption-value">
                        {$aSetting.value|clean}
                    </span>
                </div>
            {/if}
        {elseif $aSetting.var_type == 'select'}
            {if isset($aSetting.isHas)}
            <div class="col-xs-12 col-sm-12 col-md-12">
                <span class="col-xs-5 col-sm-5 col-md-5 cars-caption-bold">
                    {phrase var=$aSetting.phrase_var_name}:
                </span>
                <span class="col-xs-7 col-sm-7 col-md-7 cars-caption-value">
                    {foreach from=$aSetting.options key=iKey item=aOption}
                        {if isset($aOption.value) && isset($aOption.selected) && $aOption.selected == true}
                            {$aOption.value}&nbsp;
                        {/if}
                    {/foreach}
                </span>
            </div>
            {/if}
        {elseif $aSetting.var_type == 'multiselect'}
            {if isset($aSetting.isHas)}
            <div class="col-xs-12 col-sm-12 col-md-12">
                <span class="col-xs-5 col-sm-5 col-md-5 cars-caption-bold">
                    {phrase var=$aSetting.phrase_var_name}:
                </span>
                <span class="col-xs-7 col-sm-7 col-md-7 cars-caption-value">
                    {foreach from=$aSetting.options key=iKey item=aOption}
                        {if !empty($aOption.value) && isset($aOption.selected) && $aOption.selected == true}
                            {$aOption.value}&nbsp;
                        {/if}
                    {/foreach}
                </span>
            </div>
            {/if}
        {elseif $aSetting.var_type == 'radio'}
            {if isset($aSetting.isHas)}
            <div class="col-xs-12 col-sm-12 col-md-12">
                <span class="col-xs-5 col-sm-5 col-md-5 cars-caption-bold">
                    {phrase var=$aSetting.phrase_var_name}:
                </span>
                <span class="col-xs-7 col-sm-7 col-md-7 cars-caption-value">
                    {foreach from=$aSetting.options key=iKey item=aOption}
                        {if !empty($aOption.selected) && $aOption.selected == true}
                            {$aOption.value}&nbsp;
                        {/if}
                    {/foreach}
                </span>
            </div>
            {/if}
        {elseif $aSetting.var_type == 'checkbox'}
            {if isset($aSetting.isHas)}
            <div class="col-xs-12 col-sm-12 col-md-12">
                <span class="col-xs-5 col-sm-5 col-md-5 cars-caption-bold">
                    {phrase var=$aSetting.phrase_var_name}:
                </span>
                <span class="col-xs-7 col-sm-7 col-md-7 cars-caption-value">
                    {foreach from=$aSetting.options key=iKey item=aOption}
                        {if !empty($aOption.selected) && $aOption.selected == true}
                            {$aOption.value}&nbsp;
                        {/if}
                    {/foreach}
                </span>
            </div>
            {/if}
        {/if}
    {/foreach}
{/if}
</div>