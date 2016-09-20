'use strict';
/* Controllers */
angular.module('SupportEzzy.controllers', ['ngSanitize'])

.controller('mainNavCtrl', function($rootScope, $scope, $http, ngProgress, Ajax) {
    $rootScope.display_name = '';

    $scope.$on("$routeChangeSuccess", function($currentRoute, $previousRoute) {
        checkAuth();
        if(token != 'invalid'){
            $rootScope.updateTicketNumbers();
        }
        angular.element($('#main-nav-toggle:checked')).removeAttr('checked');
        angular.element($('#sidebar-toggle:checked')).removeAttr('checked');
        angular.element($('.sidebar-toggle i')).removeClass('fa-times-circle').addClass('fa-indent');
        angular.element($('.main-nav-toggle i')).removeClass('fa-times-circle').addClass('fa-bars');
    });

    angular.element($('.sidebar-toggle')).click(function(){
        $(this).find('i').toggleClass('fa-indent fa-times-circle');
        angular.element($('#main-nav-toggle:checked')).removeAttr('checked');
        angular.element($('.main-nav-toggle i')).removeClass('fa-times-circle').addClass('fa-bars');
    });

    angular.element($('.main-nav-toggle')).click(function(){
        $(this).find('i').toggleClass('fa-bars fa-times-circle');
        angular.element($('#sidebar-toggle:checked')).removeAttr('checked');
        angular.element($('.sidebar-toggle i')).removeClass('fa-times-circle').addClass('fa-indent');
    });

    var progressColor = angular.element($('body')).attr('data-progress-color');
    ngProgress.color(progressColor);

    $rootScope.user_id = null;
    $rootScope.mod_documentation = null;
    $rootScope.mod_faqs = null;
    $rootScope.mod_public_tickets = null;
    $rootScope.isClient = null;
    $rootScope.isAgent = null;
    $rootScope.isAdmin = null;
    $scope.ajaxData = {
        action: 'cjsupport_localize'
    };

    console.log($rootScope.isClient);

    if($rootScope.isAgent != 'null'){
        var tinymce_plugins = "link, image, inline_code, canned_responses";
        var tinymce_toolbar = "bold italic | bullist numlist | link image |  inline_code | canned_response";
    }

    if($rootScope.isAdmin != 'null'){
        var tinymce_plugins = "link, image, inline_code, canned_responses";
        var tinymce_toolbar = "bold italic | bullist numlist | link image |  inline_code | canned_response";
    }

    if($rootScope.isClient != 'null'){
        var tinymce_plugins = "link, image, inline_code";
        var tinymce_toolbar = "bold italic | bullist numlist | link image |  inline_code";
    }

    console.log(tinymce_plugins);



    $rootScope.tinymceOptions = {
        menubar: false,
        plugins: tinymce_plugins,
        height: 150,
        skin: 'light',
        browser_spellcheck : true,
        toolbar: tinymce_toolbar
    };



    $http.post(ajaxurl, $scope.ajaxData).success(function(result, status) {
        debugapp(result);
        $rootScope.lang = result;
        $rootScope.are_you_sure = result.are_you_sure;
        $rootScope.user_id = result.user_id;
        $rootScope.mod_documentation = (result.mod_documentation == 'enable') ? 1 : null;
        $rootScope.mod_faqs = (result.mod_faqs == 'enable') ? 1 : null;
        $rootScope.mod_public_tickets = (result.mod_public_tickets == 'enable') ? 1 : null;
        $rootScope.isClient = (result.user_type == 'client') ? 1 : 0;
        $rootScope.isAgent = (result.user_type == 'agent') ? 1 : 0;
        $rootScope.isAdmin = (result.user_type == 'admin') ? 1 : 0;
    });

    $rootScope.number_open_tickets = 0;
    $rootScope.updateTicketNumbers = function(){
        Ajax.post('update_numbers').success(function(result) {
            debugapp(result);
            $rootScope.number_open_tickets = result.number_open_tickets;
            $rootScope.number_closed_tickets = result.number_closed_tickets;
            $rootScope.number_public_tickets = result.number_public_tickets;
            $rootScope.number_starred_tickets = result.number_starred_tickets;
            $rootScope.number_response_required = result.number_response_required;
        });
    };

    $rootScope.ticketsearch = {}
    $rootScope.searchTickets = function(){
        var search = encodeURI($rootScope.ticketsearch.keywords);
        window.location = '#/search/'+search;
    };

})

.controller('homeCtrl', function($rootScope, $scope, $http, Ajax, ngProgress) {
    ngProgress.start();
    Ajax.post('home').success(function(result) {
        debugapp(result);
        $scope.sidebar_content = result.sidebar_content;
        $scope.response = result.response;
        ngProgress.complete();
    });
})

.controller('ticketCreateCtrl', function($rootScope, $scope, $http, Ajax, $routeParams, ngProgress) {

    $scope.envato_verify = 0;
    $scope.envato_errors = null;
    $scope.main_wrap = 0;

    Ajax.post('home').success(function(result) {
        debugapp(result);
        $scope.response = result.response;
        $scope.hide_departments = result.hide_departments;
        $scope.hide_products = result.hide_products;
        $scope.default_department = result.default_department;
        $scope.default_product = result.default_product;
        $scope.main_wrap = 1;
    });

    $scope.ticket = {};
    $scope.custom_fields = {};
    $scope.submitTicket = function() {
        //ngProgress.start();
        $scope.errors = null;

        if($scope.hide_departments == 'yes'){
            angular.extend($scope.ticket, {
                department: $scope.default_department,
            });
        }
        if($scope.hide_products == 'yes'){
            angular.extend($scope.ticket, {
                product: $scope.default_product,
            });
        }

        angular.extend($scope.ticket, $scope.custom_fields);

        Ajax.post('create_ticket', $scope.ticket).success(function(result) {
            debugapp(result);
            if (result.status == 'errors') {
                $scope.errors = result.response;
            }
            if (result.status == 'success') {
                window.location = '#/tickets';
            }
            ngProgress.complete();
        });
    };

    // handle attachments
    $scope.ticket.attachments = [];
    // handle attachments
    $scope.uploadFile = function(files, el) {
        var uploadUrl = angular.element($('#cjsupport-upload-path')).val();
        var $file_list = angular.element($(el));
        for (var i = files.length - 1; i >= 0; i--) {
            ngProgress.start();
            angular.element($('button[type=submit]')).attr('disabled', 'true');
            var fd = new FormData();
            fd.append("file", files[i]);
            $http.post(uploadUrl, fd, {
                withCredentials: true,
                headers: {
                    'Content-Type': undefined
                },
                transformRequest: angular.identity
            }).success(function(result) {
                if (result.status == 'success') {
                    $scope.ticket.attachments.push(result.response.furl);
                    $file_list.append('<i class="fa fa-paperclip"></i> '+result.response.fname+'<br>');
                } else {
                    $scope.errors = result.response;
                }
                ngProgress.complete();
                angular.element($('button[type=submit]')).removeAttr('disabled');
            }).error(function(err) {
                ngProgress.complete();
                angular.element($('button[type=submit]')).removeAttr('disabled');
            });
        };

    };

    $scope.productChange = function(){
        var product = $scope.ticket.product;
        var verified_products = $rootScope.lang.verified_envato_products;
        debugapp(verified_products);
        $scope.envato = {
            item_id: product
        };

        if(verified_products == 0 && product.indexOf("envato-") >= 0){
            $scope.envato_verify = 1;
        }else if(verified_products != 0 && product.indexOf("envato-") >= 0){
            if(verified_products.indexOf(product) < 0){
                $scope.envato_verify = 1;
            }
        }else{
            $scope.envato_verify = 0;
        }
    };

    $scope.verifyEnvatoPurchase = function(){
        ngProgress.start();
        Ajax.post('verify_envato', $scope.envato).success(function(result) {
            debugapp(result);
            if(result.status == 'success'){
                $scope.envato_verify = 0;
                angular.extend($scope.ticket, {
                    envato_verified: 1
                });
            }else{
                $scope.envato_errors = result.response;
            }
            ngProgress.complete();
        });
    };

})

.controller('ticketCreateClientCtrl', function($rootScope, $scope, $http, Ajax, $routeParams, ngProgress) {

    $scope.envato_verify = 0;
    $scope.envato_errors = null;
    $scope.main_wrap = 0;
    $scope.ticket = {};
    $scope.custom_fields = {};
    $scope.ticket.client_email = 0;

    $scope.newTicketForm = function(){
        var data = {
            act: 'find-client',
        };

        Ajax.post('create_ticket_client', data).success(function(result) {
            debugapp(result);
            $scope.response = result.response;
            $scope.hide_departments = result.hide_departments;
            $scope.hide_products = result.hide_products;
            $scope.default_department = result.default_department;
            $scope.default_product = result.default_product;
            $scope.main_wrap = 1;
        });
    };
    $scope.newTicketForm();

    $scope.client_found = 0;
    $scope.client_not_found = 0;
    $scope.new_user_response = 0;
    $scope.findClientEmail = function(){
        $scope.ticket.client_email = 0;
        if($scope.ticket.client_string.length > 4){
            var data = {
                client_string: $scope.ticket.client_string
            };
            Ajax.post('find_client', data).success(function(result){
                debugapp(result);
                if(result.status == 'success'){
                    $scope.client_found = result.response;
                }
                if(result.status == 'error'){
                    $scope.client_found = 0;
                    $scope.new_user_response = 0;
                    $scope.client_not_found = result.response;
                }
                if(result.status == 'warning'){
                    $scope.client_found = 0;
                    $scope.client_not_found = 0;
                    $scope.new_user_response = result.response;
                    $scope.ticket.client_email = $scope.ticket.client_string;
                }
            });
        }else{
            $scope.client_found = 0;
            $scope.client_not_found = 0;
            $scope.new_user_response = 0;
        }
    };

    $scope.setClientEmail = function(client){
        $scope.ticket.client_email = client.user_email;
        $scope.ticket.client_string = '';
        $scope.client_found = 0;
        $scope.client_not_found = 0;
        $scope.new_user_response = 0;
    };


    $scope.submitTicket = function() {
        //ngProgress.start();
        $scope.errors = null;

        angular.extend($scope.ticket, {
            act: 'create-client-ticket'
        });

        if($scope.hide_departments == 'yes'){
            angular.extend($scope.ticket, {
                department: $scope.default_department,
            });
        }
        if($scope.hide_products == 'yes'){
            angular.extend($scope.ticket, {
                product: $scope.default_product,
            });
        }

        angular.extend($scope.ticket, $scope.custom_fields);

        Ajax.post('create_ticket_client', $scope.ticket).success(function(result) {
            debugapp(result);
            if (result.status == 'errors') {
                $scope.errors = result.response;
            }
            if (result.status == 'success') {
                window.location = '#/tickets';
            }
            ngProgress.complete();
        });
    };

    // handle attachments
    $scope.ticket.attachments = [];
    // handle attachments
    $scope.uploadFile = function(files, el) {
        var uploadUrl = angular.element($('#cjsupport-upload-path')).val();
        var $file_list = angular.element($(el));
        for (var i = files.length - 1; i >= 0; i--) {
            ngProgress.start();
            angular.element($('button[type=submit]')).attr('disabled', 'true');
            var fd = new FormData();
            fd.append("file", files[i]);
            $http.post(uploadUrl, fd, {
                withCredentials: true,
                headers: {
                    'Content-Type': undefined
                },
                transformRequest: angular.identity
            }).success(function(result) {
                if (result.status == 'success') {
                    $scope.ticket.attachments.push(result.response.furl);
                    $file_list.append('<i class="fa fa-paperclip"></i> '+result.response.fname+'<br>');
                } else {
                    $scope.errors = result.response;
                }
                ngProgress.complete();
                angular.element($('button[type=submit]')).removeAttr('disabled');
            }).error(function(err) {
                ngProgress.complete();
                angular.element($('button[type=submit]')).removeAttr('disabled');
            });
        };

    };

})

.controller('ticketsPublicCtrl', function($rootScope, $scope, $http, Ajax, $routeParams, ngProgress) {
    $scope.tickets = {};
    $scope.tickets_count = 0;
    $scope.main_wrap = 0;
    $scope.post_status = 'publish';

    $scope.getTickets = function() {
        $scope.main_wrap = 0;
        ngProgress.start();
        var params = {
            post_status: $scope.post_status,
            product_slug: $scope.product_slug,
            department_slug: $scope.department_slug,
            by_user: $scope.by_user
        };

        Ajax.post('tickets_public', params).success(function(result) {
            debugapp(result);
            $scope.title = result.page_title;
            $scope.tickets_count = result.tickets_count;
            $scope.result = result;
            $scope.tickets = result.tickets;
            ngProgress.complete();
            setTimeout(function() {
                $rootScope.updateTicketNumbers();
                $scope.main_wrap = 1;
            }, 1000);
        });
    };
    $scope.getTickets();

    $scope.reloadTickets = function() {
        ngProgress.start();
        var params = {
            post_status: 'publish'
        };
        Ajax.post('tickets_public', params).success(function(result) {
            debugapp(result);
            $scope.title = result.page_title;
            $scope.tickets_count = result.tickets_count;
            $scope.result = result;
            $scope.tickets = result.tickets;
            ngProgress.complete();
            $scope.main_wrap = 1;
        });
    };

})

.controller('ticketsResponseRequiredCtrl', function($rootScope, $scope, $http, Ajax, $routeParams, ngProgress) {
    $scope.tickets = {};
    $scope.tickets_count = 0;
    $scope.main_wrap = 0;
    $scope.post_status = 'publish';

    $scope.getTickets = function() {
        $scope.main_wrap = 0;
        ngProgress.start();
        var params = {
            post_status: $scope.post_status,
            product_slug: $scope.product_slug,
            department_slug: $scope.department_slug,
            by_user: $scope.by_user
        };

        Ajax.post('tickets_response_required', params).success(function(result) {
            debugapp(result);
            $scope.title = result.page_title;
            $scope.tickets_count = result.tickets_count;
            $scope.result = result;
            $scope.tickets = result.tickets;
            ngProgress.complete();
            setTimeout(function() {
                $rootScope.updateTicketNumbers();
                $scope.main_wrap = 1;
            }, 1000);
        });
    };
    $scope.getTickets();

    $scope.reloadTickets = function() {
        ngProgress.start();
        var params = {
            post_status: 'publish'
        };
        Ajax.post('tickets_response_required', params).success(function(result) {
            debugapp(result);
            $scope.title = result.page_title;
            $scope.tickets_count = result.tickets_count;
            $scope.result = result;
            $scope.tickets = result.tickets;
            $rootScope.updateTicketNumbers();
            ngProgress.complete();
            $scope.main_wrap = 1;
        });
    };

    $scope.deleteTicket = function(id){
        var ans = confirm($rootScope.are_you_sure);
        if(ans){
            ngProgress.start();
            var params = {
                ticket_id: id
            };
            Ajax.post('delete_ticket', params).success(function(result) {
                debugapp(result);
                $scope.reloadTickets();
            });
        }
    };


    $scope.markTicketResolved = function(ticket_id, status) {
        ngProgress.start();
        var data = {
            act: 'toggle_closed',
            ID: ticket_id,
            set_ticket_status: status,
        };
        Ajax.post('ticket_actions', data).success(function(result) {
            debugapp(result);
            ngProgress.complete();
            $scope.reloadTickets();
        });
    };

    $scope.markTicketStarred = function(ticket_id, status) {
        ngProgress.start();
        var data = {
            act: 'toggle_starred',
            ID: ticket_id,
            status: status
        };
        Ajax.post('ticket_actions', data).success(function(result) {
            debugapp(result);
            ngProgress.complete();
            $scope.reloadTickets();
        });
    };

})


.controller('ticketsCtrl', function($rootScope, $scope, $http, Ajax, $routeParams, ngProgress) {

    $scope.tickets = {};
    $scope.tickets_count = 0;
    $scope.main_wrap = 0;
    $scope.post_status = 'publish';

    if ($routeParams.post_status != undefined) {
        $scope.post_status = $routeParams.post_status;
    }
    if ($routeParams.product_slug != undefined) {
        $scope.product_slug = $routeParams.product_slug;
    }
    if ($routeParams.department_slug != undefined) {
        $scope.department_slug = $routeParams.department_slug;
    }
    if ($routeParams.user_id != undefined) {
        $scope.by_user = $routeParams.user_id;
    }

    $scope.getTickets = function() {
        $scope.main_wrap = 0;
        ngProgress.start();
        var params = {
            post_status: $scope.post_status,
            product_slug: $scope.product_slug,
            department_slug: $scope.department_slug,
            by_user: $scope.by_user
        };

        Ajax.post('tickets', params).success(function(result) {
            debugapp(result);
            $scope.title = result.page_title;
            $scope.tickets_count = result.tickets_count;
            $scope.result = result;
            $scope.tickets = result.tickets;
            ngProgress.complete();
            setTimeout(function() {
                $scope.main_wrap = 1;
                $rootScope.updateTicketNumbers();
            }, 1000);
        });
    };
    $scope.getTickets();


    $scope.deleteTicket = function(id){
        var ans = confirm($rootScope.are_you_sure);
        if(ans){
            ngProgress.start();
            var params = {
                ticket_id: id
            };
            Ajax.post('delete_ticket', params).success(function(result) {
                debugapp(result);
                $scope.reloadTickets();
            });
        }
    };

    $scope.reloadTickets = function() {
        ngProgress.start();
        var params = {
            post_status: $scope.post_status
        };
        Ajax.post('tickets', params).success(function(result) {
            debugapp(result);
            $rootScope.updateTicketNumbers();
            $scope.title = result.page_title;
            $scope.tickets_count = result.tickets_count;
            $scope.result = result;
            $scope.tickets = result.tickets;
            ngProgress.complete();
        });
    };


    $scope.markTicketResolved = function(ticket_id, status) {
        ngProgress.start();
        var data = {
            act: 'toggle_closed',
            ID: ticket_id,
            set_ticket_status: status,
        };
        Ajax.post('ticket_actions', data).success(function(result) {
            debugapp(result);
            ngProgress.complete();
            $scope.reloadTickets();
        });
    };

    $scope.markTicketStarred = function(ticket_id, status) {
        ngProgress.start();
        var data = {
            act: 'toggle_starred',
            ID: ticket_id,
            status: status
        };
        Ajax.post('ticket_actions', data).success(function(result) {
            debugapp(result);
            ngProgress.complete();
            $scope.reloadTickets();
        });
    };


})

.controller('ticketSingleCtrl', function($rootScope, $scope, $http, Ajax, $routeParams, ngProgress, $modal) {

    $scope.showCreateFaq = 0;
    $scope.show_ticket_info = 1;

    $scope.getTicket = function() {
        ngProgress.start();
        var params = {
            ID: $routeParams.ID
        };
        Ajax.post('ticket', params).success(function(result) {
            debugapp(result);
            if(result.response.view_only == 1){
                window.location = '#/tickets';
            }
            if(result.response == 'not-allowed'){
                window.location = '#/tickets';
            }
            $scope.result = result;
            $scope.ticket = result.response;
            $scope.comments = result.comments;
            $scope.uncollapse_button = result.uncollapse_button;
            $scope.collapse_count = result.collapse_count;
            $scope.agents = result.agents;
            $scope.departments = result.departments;
            $scope.products = result.products;
            $rootScope.updateTicketNumbers();
            ngProgress.complete();
            setTimeout(function() {
                $scope.main_wrap = 1;
            }, 1000);
        });
    };
    $scope.getTicket();

    $scope.comment = {};
    $scope.submitComment = function(ticket_id) {
        $scope.errors = null;
        $scope.ticket_errors = null;
        ngProgress.start();
        var params = {
            ticket_id: ticket_id,
            user_id: $rootScope.user_id
        }
        angular.extend($scope.comment, params);
        Ajax.post('ticket_comment', $scope.comment).success(function(result) {
            debugapp(result);
            if (result.status == 'errors') {
                ngProgress.complete();
                $scope.ticket_errors = result.response;
            } else {
                ngProgress.complete();
                if (result.response == 'closed') {
                    $scope.comment = {};
                    window.location = '#/tickets/closed';
                } else {
                    $scope.getTicket();
                    $scope.comment = {};
                }
            }
            $rootScope.updateTicketNumbers();
        });
    };

    $scope.uncollapseComments = function() {
        $scope.collapse_count = 0;
        $scope.uncollapse_button = 0;
    }

    $scope.markTicketResolved = function(ticket_id, status) {
        ngProgress.start();
        var data = {
            act: 'toggle_closed',
            ID: ticket_id,
            set_ticket_status: status,
        };
        Ajax.post('ticket_actions', data).success(function(result) {
            debugapp(result);
            ngProgress.complete();
            $scope.getTicket();
        });
    };

    $scope.markTicketStarred = function(ticket_id, status) {
        ngProgress.start();
        var data = {
            act: 'toggle_starred',
            ID: ticket_id,
            status: status
        };
        Ajax.post('ticket_actions', data).success(function(result) {
            debugapp(result);
            ngProgress.complete();
            $scope.getTicket();
        });
    };


    $scope.agent_departments_products = function(){
        for (var i = $scope.agents.length - 1; i >= 0; i--) {
            if($scope.agents[i].id == $scope.transfer.agent){
                $scope.departments = [];
                $scope.departments = $scope.agents[i].departments;
                $scope.products = [];
                $scope.products = $scope.agents[i].products;
            }
        };
    };


    $scope.transfer = {};
    $scope.transferTicket = function(ticket_id) {
        ngProgress.start();
        $scope.transfer_errors = null;
        angular.extend($scope.transfer, {
            ticket_id: ticket_id,
            user_id: $rootScope.user_id
        });
        Ajax.post('ticket_transfer', $scope.transfer).success(function(result) {
            debugapp(result);
            if (result.status == 'errors') {
                ngProgress.complete();
                $scope.transfer_errors = result.response;
            } else {
                ngProgress.complete();
                $scope.getTicket();
                $scope.transfer = {};
                $rootScope.updateTicketNumbers();
            }
        });
    };

    $scope.markTicketPublic = function(ticket_id) {
        ngProgress.start();
        var data = {
            act: 'toggle_visibility',
            ID: ticket_id
        };
        Ajax.post('ticket_actions', data).success(function(result) {
            debugapp(result);
            ngProgress.complete();
            $scope.getTicket();
            $rootScope.updateTicketNumbers();
        });
    };

    $scope.tp = {};
    $scope.ticketUpdatePriority = function(ticket_id){
        ngProgress.start();
        angular.extend($scope.tp, {
            act: 'toggle_priority',
            ticket_id: ticket_id
        });
        Ajax.post('ticket_actions', $scope.tp).success(function(result){
            $scope.getTicket();
        });
    };


    $scope.comment.attachments = [];
    // handle attachments
    $scope.uploadFile = function(files, el) {
        $scope.errors = null;
        var uploadUrl = angular.element($('#cjsupport-upload-path')).val();
        var $file_list = angular.element($(el));
        for (var i = files.length - 1; i >= 0; i--) {
            ngProgress.start();
            angular.element($('button[type=submit]')).attr('disabled', 'true');
            var fd = new FormData();
            fd.append("file", files[i]);
            $http.post(uploadUrl, fd, {
                withCredentials: true,
                headers: {
                    'Content-Type': undefined
                },
                transformRequest: angular.identity
            }).success(function(result) {
                if (result.status == 'success') {
                    $scope.comment.attachments.push(result.response.furl);
                    $file_list.append('<i class="fa fa-paperclip"></i> '+result.response.fname+'<br>');
                } else {
                    $scope.errors = result.response;
                }
                ngProgress.complete();
                angular.element($('button[type=submit]')).removeAttr('disabled');
            }).error(function(err) {
                ngProgress.complete();
                angular.element($('button[type=submit]')).removeAttr('disabled');
            });
        };

    };



    $scope.editComment = function(comment_ID, comment_Content) {
        $scope.editCommentID = comment_ID;
        $scope.editCommentContent = comment_Content;
        var modalInstance = $modal.open({
            templateUrl: 'editComment.html',
            controller: ModalInstanceCtrl,
            resolve: {
                commentInfo: function() {
                    return {
                        comment_ID: $scope.editCommentID,
                        comment_Content: $scope.editCommentContent
                    };
                }
            }
        });
        modalInstance.result.then(function(result) {
            $scope.getTicket();
        });
    };

    var ModalInstanceCtrl = function($rootScope, $scope, $modalInstance, commentInfo, ngProgress) {
        $scope.edit_comment = {
            comment_ID: commentInfo.comment_ID,
            content: commentInfo.comment_Content
        }
        $scope.editcommenterrors = 0;
        $scope.saveComment = function() {
            $scope.editcommenterrors = 0;
            ngProgress.start();
            Ajax.post('save_comment', $scope.edit_comment).success(function(result) {
                debugapp(result);
                if (result.status == 'success') {
                    $modalInstance.close(result);
                    ngProgress.complete();
                } else {
                    $scope.editcommenterrors = result.response;
                    ngProgress.complete();
                }
            });
        };
        $scope.cancel = function() {
            $modalInstance.dismiss('cancel');
            ngProgress.complete();
        };
    };

    $scope.faq = {};
    $scope.createCommentFaq = function(title, content, product, ticket_id) {
        $scope.faq = {
            ticket_id : ticket_id,
            title : title,
            content : content,
            product : product
        };
        $scope.showCreateFaq = 1;
    };

    $scope.faq_success = 0;
    $scope.faq_error = 0;
    $scope.submitFAQ = function(){
        $scope.faq_success = 0;
        $scope.faq_error = 0;
        ngProgress.start();
        Ajax.post('create_faq', $scope.faq).success(function(result) {
            debugapp(result);
            if(result.status == 'success'){
                $scope.faq_success = 1;
                $scope.faq_success_msg = result.response;
                $scope.faq = {};
                setTimeout(function(){
                    $scope.showCreateFaq = 0;
                    ngProgress.complete();
                }, 3000);
            }else{
                $scope.faq_error = 1;
                $scope.faq_error_msg = result.response;
                ngProgress.complete();
            }
        });
    };

    $scope.cancelCreateFaq = function() {
        $scope.faq = {};
        $scope.showCreateFaq = 0;
    };

    $scope.rating_message = '';
    $scope.comment_ratings = {};
    $scope.test = function(t,c,r){
        angular.extend($scope.comment_ratings, {
            ticket_id: t,
            comment_id: c,
            rating: r
        })
        Ajax.post('ticket_ratings', $scope.comment_ratings).success(function(result){
            $scope.rating_message = result.response;
            $scope.getTicket();

        });
    };


})

.controller('faqsCtrl', function($rootScope, $scope, $http, Ajax, $routeParams, ngProgress) {

    $scope.faqs = null;
    $scope.main_wrap = 0;
    $scope.getFaqs = function(){
        ngProgress.start();
        $scope.main_wrap = 0;
        var params = {};
        ngProgress.start();
        Ajax.post('get_faqs_by_product', params).success(function(result) {
            debugapp(result);
            if(result.status == 'success'){
                $scope.faqs = result.response;
            }else{
                $scope.faqs = 0;
                $scope.faq_errors = result.response;
            }
            setTimeout(function(){
                ngProgress.complete();
                $scope.main_wrap = 1;
            }, 1500);
        });
    };

    $scope.getFaqs();

})

.controller('faqsByProductCtrl', function($rootScope, $scope, $http, Ajax, $routeParams, ngProgress) {

    $scope.main_wrap = 0;
    $scope.faqs = null;
    $scope.faq_errors = 0;

    setTimeout(function(){
        $scope.getFaqs();
    }, 1000);
    $scope.isCollapsed = true;


    $scope.getFaqs = function(){
        $scope.main_wrap = 0;
        $scope.product_slug = $routeParams.product_slug;
        var params = {
            product_slug: $scope.product_slug
        };
        ngProgress.start();
        Ajax.post('get_faqs', params).success(function(result) {
            debugapp(result);
            if(result.status == 'success'){
                $scope.faqs = result.response;
                $scope.page_title = result.page_title;
                ngProgress.complete();
            }else{
                $scope.faq_errors = result.response;
                $scope.page_title = result.page_title;
                ngProgress.complete();
            }
            setTimeout(function(){
                $scope.main_wrap = 1;
            }, 1500);
        });
    };
})
.controller('searchCtrl', function($rootScope, $scope, $http, Ajax, $routeParams, ngProgress) {


    $scope.main_wrap = 0;
    $scope.search_errors = 0;
    $scope.tickets = 0;
    $scope.searchTickets = function(){
        ngProgress.start();
        var params = {
            user_id: $scope.user_id,
            keywords: decodeURI($routeParams.keywords)
        }
        Ajax.post('search_tickets', params).success(function(result) {
            debugapp(result);
            $scope.result = result;
            $scope.page_title = result.page_title;

            if(result.status == 'errors'){
                $scope.search_errors = result.response;
            }else{
                $scope.tickets = result.response;
                $scope.search_errors = 0;
            }

            ngProgress.complete();
            $scope.main_wrap = 1;
        });
    }

    $scope.searchTickets();

})
.controller('cannedResponsesCtrl', function($rootScope, $scope, $http, Ajax, $routeParams, ngProgress) {

    $scope.errors = null;

    $scope.closeCannedResponsePanel = function(){
        angular.element($('#canned-lightbox, #canned-responses')).fadeOut();
    };

    $scope.responses = {};
    $scope.getCannedResponses = function(){
        Ajax.post('canned_responses').success(function(result) {
            debugapp(result);
            if(result.status == 'success'){
                $scope.showAddBox = 0;
                $scope.responses = result.response;
            }
            if(result.status == 'error'){
                $scope.errors = result.response;
            }

        });
    };
    $scope.getCannedResponses();


    $scope.cres = {};
    $scope.saveNewResponse = function(){
        var params = {
            user_id: $scope.user_id,
            rname: $scope.cres.rname,
            rcontent: $scope.cres.rcontent,
        }
        Ajax.post('canned_responses_add_new', params).success(function(result) {
            debugapp(result);
            if(result.status == 'success'){
                $scope.showAddBox = 0;
                $scope.getCannedResponses();
            }
            if(result.status == 'error'){
                $scope.errors = result.response;
            }


        });
    };

});











