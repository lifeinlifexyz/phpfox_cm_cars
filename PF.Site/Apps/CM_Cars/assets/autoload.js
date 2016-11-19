var cars_admincp_flag = false;
$Ready(function() {
    function QueryString() {
        var query_string = {};
        var query = window.location.search.substring(1);
        var vars = query.split("&");
        for (var i=0;i<vars.length;i++) {
            var pair = vars[i].split("=");
            if (typeof query_string[pair[0]] === "undefined") {
                query_string[pair[0]] = decodeURIComponent(pair[1]);
            } else if (typeof query_string[pair[0]] === "string") {
                var arr = [ query_string[pair[0]],decodeURIComponent(pair[1]) ];
                query_string[pair[0]] = arr;
            } else {
                query_string[pair[0]].push(decodeURIComponent(pair[1]));
            }
        }
        return query_string;
    }

    var query = QueryString();
    if ((oCore['core.is_admincp'] == true && oCore['core.section_module'] == 'cars') || (typeof query.id != 'undefined' && query.id == '__module_cars') || (typeof query[Object.keys(query)[0]] != 'undefined' && query[Object.keys(query)[0]] == 'cars')){
        $('.apps_menu ul li:first a').text('Module Settings');
        $('.apps_menu ul').prepend('<li><a href="'+oParams['sBaseURL']+'admincp/app/?id=CM_Cars#settings">Settings</a></li>');
    } else if (oCore['core.is_admincp'] == true && (typeof query.id != 'undefined' && query.id == 'CM_Cars') && oModules['cars'] == true) {
        $('.apps_menu li:not(.apps_menu li:first) a').addClass("no_ajax");
    }
});