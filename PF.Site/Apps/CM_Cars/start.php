<?php

event('app_settings', function ($settings){
    if (isset($settings['cm_cars_enabled'])) {
        \Phpfox::getService('admincp.module.process')->updateActivity('cars', $settings['cm_cars_enabled']);
    }
});

if (strtolower(Phpfox_Request::instance()->get('req1')) == Phpfox::getParam('admincp.admin_cp')){

    if (!Phpfox::getService('admincp.product')->isProduct('Cars')) {
        if (file_exists(PHPFOX_DIR_XML . 'Cars.xml')) {
            route('/admincp/setting/edit', function (){
                if (request()->get('module-id') == 'cars'){
                    echo('Cars module not installed, please install the module on the <a href="' . Phpfox::getLib('url')->makeUrl('admincp') . '">dashboard</a>');
                    return 'controller';
                }
            });
            route('/admincp/cars/*', function (){
                echo('Cars module not installed, please install the module on the <a href="' . Phpfox::getLib('url')->makeUrl('admincp') . '">dashboard</a>');
                return 'controller';
            });
        }
    }else if (!Phpfox::isModule('cars')){
        route('/admincp/setting/edit', function (){
            if (request()->get('module-id') == 'cars'){
                echo('Cars module is not active');
                return 'controller';
            }
        });
        route('/admincp/cars/*', function (){
            echo('Cars module is not active');
            return 'controller';
        });
    }
}