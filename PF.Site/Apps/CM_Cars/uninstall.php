<?php
function delTree($dir) {
    $files = array_diff(scandir($dir), array('.','..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
}
if (Phpfox::getService('admincp.product.process')->delete('Cars')){
    if (file_exists(PHPFOX_DIR_XML.'Cars.xml')){
        unlink(PHPFOX_DIR_XML.'Cars.xml');
        if (is_dir(PHPFOX_DIR_MODULE.'cars')){
            delTree(PHPFOX_DIR_MODULE.'cars');
        }
    }
    Phpfox::getLib('database')->query("DROP TABLE `".Phpfox::getT('cars')."`;");
    Phpfox::getLib('database')->query("DROP TABLE `".Phpfox::getT('cars_custom')."`;");
    Phpfox::getLib('database')->query("DROP TABLE `".Phpfox::getT('cars_custom_group')."`;");
    Phpfox::getLib('database')->query("DROP TABLE `".Phpfox::getT('cars_custom_field')."`;");
    Phpfox::getLib('database')->query("DROP TABLE `".Phpfox::getT('cars_custom_option')."`;");
    Phpfox::getLib('database')->query("DROP TABLE `".Phpfox::getT('cars_custom_data')."`;");
    Phpfox::getLib('database')->query("DROP TABLE `".Phpfox::getT('cars_custom_multiple_value')."`;");
    Phpfox::getLib('database')->query("DROP TABLE `".Phpfox::getT('cars_custom_value')."`;");
    Phpfox::getLib('database')->query("DROP TABLE `".Phpfox::getT('cars_location')."`;");
    Phpfox::getLib('database')->query("DROP TABLE `".Phpfox::getT('cars_type')."`;");
    Phpfox::getLib('database')->query("DROP TABLE `".Phpfox::getT('cars_mark')."`;");
    Phpfox::getLib('database')->query("DROP TABLE `".Phpfox::getT('cars_model')."`;");
    Phpfox::getLib('database')->query("DROP TABLE `".Phpfox::getT('cars_photo')."`;");
    Phpfox::getLib('database')->query("DROP TABLE `".Phpfox::getT('cars_track')."`;");
}