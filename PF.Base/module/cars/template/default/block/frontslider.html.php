<?php
/**
 * [PHPFOX_HEADER]
 *
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		CodeMake.Org
 * @package  		Module_Blog
 * @version 		$Id: add.html.php 6216 2013-07-08 08:20:46Z Bolot_Kalil $
 */

defined('PHPFOX') or exit('NO DICE!');

?>
{literal}
<style>

    .rslides {
        position: relative;
        list-style: none;
        overflow: hidden;
        width: 100%;
        padding: 0;
        margin: 0;
    }

    .rslides li {
        -webkit-backface-visibility: hidden;
        position: absolute;
        display: none;
        width: 100%;
        left: 0;
        top: 0;
    }

    .rslides li:first-child {
        position: relative;
        display: block;
        float: left;
    }

    .rslides img {
        display: block;
        height: auto;
        float: left;
        width: 100%;
        border: 0;
    }

    .callbacks_container {
        margin-bottom: 15px;
        position: relative;
        float: left;
        width: 100%;
        margin-top: 15px;
        max-height: 400px;
    }

    .callbacks {
        position: relative;
        list-style: none;
        overflow: hidden;
        width: 100%;
        padding: 0;
        margin: 0;
        max-height: 337px;
    }

    .callbacks li {
        position: absolute;
        width: 100%;
        left: 0;
        top: 0;
    }

    .callbacks img {
        display: block;
        position: relative;
        z-index: 1;
        height: auto;
        width: 100%;
        border: 0;
    }

    .callbacks .caption {
        display: block;
        position: absolute;
        z-index: 2;
        font-size: 20px;
        text-shadow: none;
        color: #fff;
        background: #000;
        background: rgba(0,0,0, .8);
        left: 0;
        right: 0;
        bottom: 0;
        padding: 10px 20px;
        margin: 0;
        max-width: none;
    }

    .callbacks_nav {
        position: absolute;
        -webkit-tap-highlight-color: rgba(0,0,0,0);
        top: 52%;
        left: 0;
        opacity: 0.7;
        z-index: 3;
        text-indent: -9999px;
        overflow: hidden;
        text-decoration: none;
        height: 61px;
        width: 38px;
        background: transparent url("{/literal}<?php echo(Phpfox::getParam('core.path_file'));?>{literal}module/cars/static/image/default/default/themes.gif") no-repeat left top;
        margin-top: -45px;
    }

    .callbacks_nav:active {
        opacity: 1.0;
    }

    .callbacks_nav.next {
        left: auto;
        background-position: right top;
        right: 0;
    }

</style>

<script type="text/javascript" src="{/literal}<?php echo(Phpfox::getParam('core.path_file').'module/cars/static/jscript/responsiveslides.min.js');?>{literal}"></script>
<script type="text/javascript">

    $Behavior.onLoadFrontSlider = function()
    {
        // Slideshow 4
        $("#frontslider").responsiveSlides({
            auto: true,
            pager: false,
            nav: true,
            speed: {/literal}<?php echo(Phpfox::getParam('cars.slider_speed')?Phpfox::getParam('cars.slider_speed'):500);?>{literal},
            namespace: "callbacks"
        });

    }
</script>
{/literal}
{if isset($aCars) && count($aCars)}
<div class="callbacks_container">
<ul class="rslides" id="frontslider">
    {foreach from=$aCars item=aCar}
    <li>        
        <a href="{permalink module='cars' id=$aCar.car_id title=$aCar.title}">{img class='js_mp_fix_width' path='cars.url_photo' file=$aCar.destination suffix='_500' title=$aCar.title}</a>
    </li>
    {/foreach}
</ul>
</div>
{/if}