<?php
/**
 * [PHPFOX_HEADER]
 *
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		CodeMake.Org
 * @package 		Phpfox
 * @version 		$Id: filter.html.php 6860 2013-11-06 20:17:19Z Fern $
 */

defined('PHPFOX') or exit('NO DICE!');

?>
<div class="block" id="cm_cars_filters">
    <form method="post" action="{url link='cars'}">
        <div class="content">
            <div id="title">
                <div>{phrase var='cars.title'}:</div>
                <div class="table_right">
                    {filter key='title'}
                </div>
            </div>
            <div>
                <div>{phrase var='cars.location'}:</div>
                <div class="table_right">
                    {filter key='location'}
                </div>
            </div>
            <div id="type_container">
                <div>{phrase var='cars.type'}:</div>
                <div class="table_right">
                    {filter key='type'}
                    <?php
                        printf('<span id="js_type_loader" style="display:none;"><img src="%s" class="v_middle" /></span>', Phpfox::getLib('template')->getStyle('image', 'ajax/small.gif'));
                    ?>
                </div>
            </div>
            {module name='cars.type-child'}
            <div id="release_container">
                <div>{phrase var='cars.release_year'}:</div>
                <div class="table_right">
                    {filter key='from'}{filter key='to'}
                </div>
            </div>
            <div id="currency_container" class="clear">
                <div>{phrase var='cars.currency'}:</div>
                <div class="table_right">
                    {filter key='currencies'}
                </div>
            </div>
            <div id="price_container" class="clear">
                <div>{phrase var='cars.price_range'}:</div>
                <div class="table_right">
                    <div class="row">
                        <div class="col-xs-6 col-md-6">
                            {filter key='priceFrom'}
                        </div>
                        <div class="col-xs-6 col-md-6">
                            {filter key='priceTo'}
                        </div>
                    </div>
                </div>
            </div>
            <div id="zip_container" class="clear">
                <div>{phrase var='cars.of_zip'}:</div>
                <div class="table_right">
                    {filter key='zip'}
                </div>
            </div>
            <div class="clear">
                <div>{phrase var='cars.display'}:</div>
                <div class="table_right">
                    {filter key='display'}
                </div>
		    </div>
            <br/>
            <div class="row" id="cars-filter-btn">
                <div class="col-xs-6 col-md-6">
                    <input type="submit" value="{phrase var='cars.search'}" class="button btn btn-primary" name="search[submit]" />
                </div>
                <div class="col-xs-6 col-md-6">
                    <input type="submit" value="{phrase var='cars.reset'}" class="button btn btn-warning pull-right" name="search[reset]" />
                </div>
            </div>
	        {if is_array($aCustomFields)}
            <ul id="js_user_browse_advanced_link">
                <li><a href="#" onclick="$('.main_search_filter_button').toggle(); $('#cars-advanced-filters').toggleClass('cars-filter-active'); $('#cars-filter-btn').toggleClass('cars-filter-inactive'); return false;" id="user_browse_advanced_link">
                        <span class="main_search_filter_button">{phrase var='user.view_advanced_filters'}</span>
                        <span class="main_search_filter_button" style="display: none">{phrase var='user.close_advanced_filters'}</span>
                    </a>
                </li>
            </ul>
            {/if}
        </div>
        {if is_array($aCustomFields)}
            <div id="cars-advanced-filters">
                {foreach from=$aCustomFields name=customfield item=aCustomField}
                <div class="go_left">
                    {if isset($aCustomField.fields)}
                    {template file='cars.block.custom.foreachcustom'}
                    {/if}
                </div>
                {if is_int($phpfox.iteration.customfield / 3)}
                <div class="clear"> </div>
                {/if}
                {/foreach}
                <div class="clear"></div>
                <div class="p_top_4" style="border-top: 1px #DFDFDF solid;">
                    <span>{phrase var='cars.sort_results_by'}:</span>
                    <div class="p_top_4">
                        {filter key='sort'} {filter key='sort_by'}
                    </div>
                </div>
                <br/>
                <div class="row" id="cars-filter-btn">
                    <div class="col-xs-6 col-md-6">
                        <input type="submit" value="{phrase var='cars.search'}" class="button btn btn-primary" name="search[submit]" />
                    </div>
                    <div class="col-xs-6 col-md-6">
                        <input type="submit" value="{phrase var='cars.reset'}" class="button btn btn-warning pull-right" name="search[reset]" />
                    </div>
                </div>
            </div>
        {/if}
    </form>
    {literal}
        <script type="text/javascript">
            $Ready(function(){
                $('#cm_country_iso').removeClass('form-control');
                $('#cm_country_iso').selectize({
                    sortField: {
                        field: 'text',
                        direction: 'asc'
                    }
                });
                $('#specie').removeClass('form-control');
                $('#specie').selectize({
                    sortField: {
                        field: 'text',
                        direction: 'asc'
                    }
                });

                $('#type').removeClass('form-control');
                $('#type').selectize({
                    sortField: {
                        field: 'text',
                        direction: 'asc'
                    }
                });

                $('#mark').selectize({
                    sortField: {
                        field: 'text',
                        direction: 'asc'
                    }
                });
                $('#model').selectize({
                    sortField: {
                        field: 'text',
                        direction: 'asc'
                    }
                });
            });
        </script>
    {/literal}
</div>
