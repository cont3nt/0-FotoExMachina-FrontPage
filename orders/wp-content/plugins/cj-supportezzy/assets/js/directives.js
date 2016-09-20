'use strict';

/* Directives */

angular.module('SupportEzzy.directives', [])
    .directive('appVersion', ['version',
        function(version) {
            return function(scope, elm, attrs) {
                elm.text(version);
            };
        }
    ])
    .directive('prettyp', function() {
        return function(scope, element, attrs) {
            $("[rel^='prettyPhoto']").prettyPhoto({
                deeplinking: false,
                social_tools: false
            });
        }
    });


/*
<a prettyp class="image" ng-href="{{b.img}}" rel="prettyPhoto[main]" target="_blank" title="{{b.title}}">
  <img ng-src="{{b.img}}" height="100px"/>
</a>
*/