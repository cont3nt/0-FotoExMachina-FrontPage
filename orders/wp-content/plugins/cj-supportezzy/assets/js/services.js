'use strict';

/* Services */

// Demonstrate how to register services
// In this case it is a simple value service.
angular.module('SupportEzzy.services', [])
.factory('Ajax', function($http, $rootScope) {
    return {
        post: function(callback, data) {
            var pdata = {
                action: 'cjsupport_ajax'
            };
            pdata.callback = callback;
            //pdata.user_id = $rootScope.user_id;
            if(data != undefined){
                angular.extend(pdata, data);
            }
            return $http.post(ajaxurl, pdata);
        }
    };
});