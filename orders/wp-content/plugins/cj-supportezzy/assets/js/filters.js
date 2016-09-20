'use strict';

/* Filters */

angular.module('SupportEzzy.filters', [])
.filter('interpolate', ['version',
        function(version) {
            return function(text) {
                return String(text).replace(/\%VERSION\%/mg, version);
            };
        }
])
.filter('trusted_html', ['$sce', function($sce) {
    return function(text) {
        return $sce.trustAsHtml(text);
    };
}]);
