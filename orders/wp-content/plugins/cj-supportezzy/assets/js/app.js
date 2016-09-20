'use strict';
// Declare app level module which depends on filters, and services
angular.module('SupportEzzy', [
    'ngRoute',
    'ngProgress',
    'ngSanitize',
    'ui.tinymce',
    'ui.bootstrap',
    'angularMoment',
    'SupportEzzy.filters',
    'SupportEzzy.services',
    'SupportEzzy.directives',
    'SupportEzzy.controllers'
])
.config(['$routeProvider',
    function($routeProvider) {
        var path = $('#cjsupport-partials-path').val();
        $routeProvider.when('/home', {
            templateUrl: path +'/home.html',
            controller: 'homeCtrl'
        });
        $routeProvider.when('/login', {
            templateUrl: path +'/home.html',
            controller: 'homeCtrl'
        });
        $routeProvider.when('/search/:keywords', {
            templateUrl: path +'/search.html',
            controller: 'searchCtrl'
        });
        $routeProvider.when('/new-ticket', {
            templateUrl: path +'/ticket-create.html',
            controller: 'ticketCreateCtrl'
        });
        $routeProvider.when('/new-ticket-client', {
            templateUrl: path +'/ticket-create-client.html',
            controller: 'ticketCreateClientCtrl'
        });
        $routeProvider.when('/tickets', {
            templateUrl: path +'/tickets.html',
            controller: 'ticketsCtrl'
        });
        $routeProvider.when('/response-required', {
            templateUrl: path +'/response-required.html',
            controller: 'ticketsResponseRequiredCtrl'
        });
        $routeProvider.when('/public-tickets', {
            templateUrl: path +'/tickets-public.html',
            controller: 'ticketsPublicCtrl'
        });
        $routeProvider.when('/public-tickets/:ID', {
            templateUrl: path +'/ticket-public.html',
            controller: 'ticketSingleCtrl'
        });
        $routeProvider.when('/tickets/:post_status', {
            templateUrl: path +'/tickets.html',
            controller: 'ticketsCtrl'
        });
        $routeProvider.when('/ticket/:ID', {
            templateUrl: path +'/ticket.html',
            controller: 'ticketSingleCtrl'
        });
        $routeProvider.when('/tickets/products/:product_slug', {
            templateUrl: path +'/tickets.html',
            controller: 'ticketsCtrl'
        });
        $routeProvider.when('/tickets/user/:user_id', {
            templateUrl: path +'/tickets.html',
            controller: 'ticketsCtrl'
        });
        $routeProvider.when('/tickets/departments/:department_slug', {
            templateUrl: path +'/tickets.html',
            controller: 'ticketsCtrl'
        });
        $routeProvider.when('/documentation', {
            templateUrl: path +'/documentation.html',
            controller: 'documentationCtrl'
        });
        $routeProvider.when('/faqs', {
            templateUrl: path +'/faqs.html',
            controller: 'faqsCtrl'
        });
        $routeProvider.when('/faqs/:product_slug', {
            templateUrl: path +'/faq-products.html',
            controller: 'faqsByProductCtrl'
        });
        $routeProvider.when('/settings', {
            templateUrl: path +'/settings.html',
            controller: 'settingsCtrl'
        });
        $routeProvider.when('/profile', {
            templateUrl: path +'/profile.html',
            controller: 'profileCtrl'
        });
        $routeProvider.otherwise({
            redirectTo: '/home'
        });
    }
]).config(function($httpProvider) {
    $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';
    $httpProvider.defaults.transformRequest = function(data) {
        return serialize(data);
    }
});

// serialize data for laravel input
function serialize(obj) {
    var str = [];
    for (var p in obj)
        if (obj.hasOwnProperty(p)) {
            if(typeof(obj[p]) === 'object'){
                var rval = obj2array(obj[p]);
            }else{
                var rval = obj[p];
            }
            str.push(encodeURIComponent(p) + "=" + encodeURIComponent(rval));
        }
    return str.join("&");
}


function obj2array(obj){
    // var arr = Object.keys(obj).map(function (key) {return key});
    // return arr;
    return obj;
}




