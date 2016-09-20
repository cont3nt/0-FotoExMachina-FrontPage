<?php
    global $current_user;
    get_currentuserinfo();
    $cjsupport_path = cjsupport_item_path('item_url').'/assets';
    $cjsupport_link = get_permalink(cjsupport_get_option('page_cjsupport_app'));
    $app_layout = cjsupport_get_option('cjsupport_app_layout');
    $logo_type = (cjsupport_get_option('company_logo') == '') ? 'text' : 'image';
    // Date timezone
    $timezone_string = get_option('timezone_string');
    $timezone_string = ($timezone_string == '') ? 'UTC' : $timezone_string;
    date_default_timezone_set($timezone_string);
    $cjsupport_user_type = cjsupport_user_type();
    if($cjsupport_user_type == 'non-user'){
        $cjsupport_nav_menu = 'cjsupport_visitors_menu';
    }
    if($cjsupport_user_type == 'client'){
        $cjsupport_nav_menu = 'cjsupport_clients_menu';
    }
    if($cjsupport_user_type == 'agent' || cjsupport_user_role($current_user->ID) == 'administrator'){
        $cjsupport_nav_menu = 'cjsupport_agents_menu';
    }
    $cjsupport_main_menu_args = array(
        'theme_location' => $cjsupport_nav_menu,
        'menu' => false,
        'container' => false,
        'container_class' => false,
        'container_id' => false,
        'menu_class' => false,
        'menu_id' => false,
        'echo' => false,
        'fallback_cb' => false,
        'items_wrap' => '%3$s',
        'depth' => 2,
        'walker' => ''
    );
    $cjsupport_main_menu = wp_nav_menu( $cjsupport_main_menu_args );
?><!DOCTYPE html>
<html lang="en" ng-app="SupportEzzy">
<head>
    <meta charset="utf-8">
    <title><?php echo the_title(); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="<?php echo $cjsupport_path; ?>/lib/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo $cjsupport_path; ?>/lib/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" href="<?php echo $cjsupport_path; ?>/lib/animate.min.css" />
    <link rel="stylesheet" href="<?php echo $cjsupport_path; ?>/lib/ngprogress/ngprogress.css" />
    <link rel="stylesheet" href="<?php echo $cjsupport_path; ?>/lib/prettyphoto/css/prettyPhoto.css" />
    <link rel="stylesheet" href="<?php echo $cjsupport_path; ?>/css/cjsupport.css" />
    <!--[if lt IE 9]>
    <script src="<?php echo $cjsupport_path; ?>/js/ie/respond.min.js"></script>
    <script src="<?php echo $cjsupport_path; ?>/js/ie/html5.js"></script>
    <script src="<?php echo $cjsupport_path; ?>/js/ie/excanvas.js"></script>
    <![endif]-->
    <style>
        <?php if($app_layout == 'default'): ?>
            header{
                background: <?php echo cjsupport_get_option('header_bg_color') ?>;
                color: <?php echo cjsupport_get_option('header_text_color') ?>;
            }
            header a, header a:hover{
                color: <?php echo cjsupport_get_option('header_text_color') ?>;
            }
            #logo{
                color: <?php echo cjsupport_get_option('logo_color') ?>;
            }
            .spinner > div, #main-nav {
                background-color: <?php echo cjsupport_get_option('header_bg_color') ?>;
            }

        <?php elseif($app_layout == 'no-header'): ?>
            header{
                background: <?php echo cjsupport_get_option('header_bg_color') ?>;
                color: <?php echo cjsupport_get_option('header_text_color') ?>;
            }
            header a, header a:hover{
                color: <?php echo cjsupport_get_option('header_text_color') ?>;
            }
            .sidebar-header{
                background: <?php echo cjsupport_get_option('header_bg_color') ?>;
                color: <?php echo cjsupport_get_option('header_text_color') ?>;
            }
            .sidebar-header #logo, .sidebar-header a, .sidebar-header a:hover{
                color: <?php echo cjsupport_get_option('logo_color') ?>;
            }
            .spinner > div {
                background-color: <?php echo cjsupport_get_option('header_bg_color') ?>;
            }
        <?php elseif($app_layout == 'no-logo'): ?>
            header a, header a:hover{
                color: <?php echo cjsupport_get_option('header_text_color') ?>;
            }
            header{
                background: <?php echo cjsupport_get_option('header_bg_color') ?>;
                color: <?php echo cjsupport_get_option('header_text_color') ?>;
            }
            header a, header a:hover{
                color: <?php echo cjsupport_get_option('header_text_color') ?>;
            }
            #logo{
                color: <?php echo cjsupport_get_option('logo_color') ?>;
            }
            .sidebar-no-header-no-logo{
                background: <?php echo cjsupport_get_option('header_bg_color') ?>;
            }
            #main-nav {
                background-color: <?php echo cjsupport_get_option('header_bg_color') ?>;
            }

            .spinner > div {
                background-color: <?php echo cjsupport_get_option('header_bg_color') ?>;
            }
        <?php endif; ?>
    </style>
    <?php echo cjsupport_get_option('custom_css'); ?>
    <script type="text/javascript">
        //<![CDATA[
        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
        var token = '<?php echo (is_user_logged_in()) ? strtoupper(sha1(md5(cjsupport_unique_string().$current_user->ID))) : 'invalid'; ?>';
        var debug = <?php echo (cjsupport_get_option('debugging') == 'yes') ? 1 : 0; ?>;
        //]]>
    </script>
    <script src="<?php echo $cjsupport_path; ?>/lib/jquery/jquery.min.js"></script>
    <script src="<?php echo $cjsupport_path; ?>/js/cjsupport.js"></script>
    <script src="<?php echo $cjsupport_path; ?>/lib/prettyphoto/js/jquery.prettyPhoto.js"></script>
    <script src="<?php echo $cjsupport_path; ?>/lib/wysiwyg/tinymce/tinymce.min.js?v=<?php echo time(); ?>"></script>
    <script src="<?php echo $cjsupport_path; ?>/lib/moments/moment.js"></script>
    <script src="<?php echo $cjsupport_path; ?>/lib/angular/angular.js"></script>
    <script src="<?php echo $cjsupport_path; ?>/lib/angular-bootstrap/ui-bootstrap.min.js"></script>
    <script src="<?php echo $cjsupport_path; ?>/lib/angular-bootstrap/ui-bootstrap-tpls.min.js"></script>
    <script src="<?php echo $cjsupport_path; ?>/lib/moments/angular-moment.min.js"></script>
    <script src="<?php echo $cjsupport_path; ?>/lib/wysiwyg/ng-tinymce.js"></script>
    <script src="<?php echo $cjsupport_path; ?>/lib/ngprogress/ngprogress.min.js"></script>
    <script src="<?php echo $cjsupport_path; ?>/lib/angular-route/angular-route.js"></script>
    <script src="<?php echo $cjsupport_path; ?>/lib/angular-sanitize/angular-sanitize.min.js"></script>
    <script src="<?php echo $cjsupport_path; ?>/js/app.js"></script>
    <script src="<?php echo $cjsupport_path; ?>/js/services.js"></script>
    <script src="<?php echo $cjsupport_path; ?>/js/controllers.js?v=<?php echo time(); ?>"></script>



    <script src="<?php echo $cjsupport_path; ?>/js/filters.js"></script>
    <script src="<?php echo $cjsupport_path; ?>/js/directives.js"></script>
    <base target="_parent">
</head>
<body id="supportezzy" class="<?php echo $app_layout; ?>" data-progress-color="<?php echo cjsupport_get_option('header_text_color') ?>">
    <?php if($app_layout == 'no-logo'): ?>
        <div class="sidebar-no-header-no-logo"></div>
    <?php endif; ?>
    <header>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-10 col-xs-12 logo">
                    <a id="logo" href="<?php echo $cjsupport_link; ?>" ng-bind-html="lang.logo | trusted_html"></a>
                </div>
                <div class="col-lg-13 col-md-12 col-sm-6 col-xs-4" ng-controller="mainNavCtrl">
                    <label class="main-nav-toggle" for="main-nav-toggle"><i class="fa fa-bars"></i></label>
                    <input id="main-nav-toggle" type="checkbox" class="hidden">
                    <ul id="main-nav">
                        <?php if(is_user_logged_in()): ?>
                        <li class="alignright"><a href="{{ lang.logout_url }}"><i class="fa fa-sign-out"></i><?php _e('Logout', 'cjsupport') ?></a></li>
                        <li class="alignright"><a href="{{ lang.edit_profile_url }}"><i class="fa fa-user"></i>{{ lang.welcome_user }}</a></li>
                        <?php else: ?>
                        <li class="alignright"><a href="{{ lang.edit_profile_url }}"><i class="fa fa-user"></i>{{ lang.welcome_user }}</a></li>
                        <?php endif; ?>

                        <li><a href="{{ lang.home_url }}"><i class="fa fa-home"></i>{{ lang.home }}</a></li>
                        <li ng-if="user_id"><a href="#/tickets"><i class="fa fa-tags"></i>{{ lang.tickets }}</a></li>
                        <li ng-if="mod_public_tickets"><a href="#/public-tickets"><i class="fa fa-globe"></i>{{ lang.public_tickets }}</a></li>
                        <li ng-if="mod_faqs"><a href="#/faqs"><i class="fa fa-question-circle"></i>{{ lang.faqs }}</a></li>
                        <?php echo $cjsupport_main_menu; ?>


                    </ul>
                </div>
            </div>
        </div>
    </header>

    <section id="main-content">
        <div class="container-fluid">
            <div class="row">
                <label class="sidebar-toggle" for="sidebar-toggle"><i class="fa fa-indent"></i></label>
                <input id="sidebar-toggle" type="checkbox" class="hidden">

                <div class="col-lg-3 col-md-4 sidebar select-none">

                    <?php if($app_layout == 'no-header'): ?>
                        <div ng-if="lang" class="sidebar-header logo-<?php echo $logo_type; ?>">
                            <div id="logo">
                                <a href="<?php echo $cjsupport_link; ?>" ng-bind-html="lang.logo | trusted_html"></a>
                            </div>
                            <div class="logo-nav-bar">
                                <?php if(is_user_logged_in()): ?>
                                    <a href="{{ lang.edit_profile_url }}">{{ lang.welcome_user }}</a> &nbsp;
                                <?php else: ?>
                                    <a href="{{ lang.edit_profile_url }}">{{ lang.welcome_user }}</a>
                                <?php endif; ?>
                            </div>
                            <div class="main-links">
                                <span ng-if="user_id"><a href="#/tickets"><i class="fa fa-tags"></i>{{ lang.tickets }}</a></span>
                                <span ng-if="!user_id"><a href="#/public-tickets"><i class="fa fa-tags"></i>{{ lang.public_tickets }}</a></span>
                                <span ng-if="mod_faqs"><a href="#/faqs"><i class="fa fa-question-circle"></i>{{ lang.faqs }}</a></span>
                                <span ng-if="user_id"><a href="{lang.logout_url}"><i class="fa fa-sign-out"></i><?php _e('Logout', 'cjsupport') ?></a></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="padding-15 sidebar-contents logo-<?php echo $logo_type ?>">
                        <!-- Sidebar Contents -->
                        <div ng-if="user_id" class="search">
                            <div class="form-group has-feedback">
                                <form ng-submit="searchTickets()">
                                    <label class="control-label sr-only" for="search"> </label>
                                    <input type="text" ng-model="ticketsearch.keywords" class="form-control" id="search" placeholder="{{ lang.search_placeholder }}">
                                    <span class="glyphicon glyphicon-search form-control-feedback"></span>
                                    <input type="submit" class="hidden" value="">
                              </form>
                            </div>
                        </div>

                        <!-- Login message -->
                        <section ng-if="!user_id" id="login" ng-bind-html="lang.login_message | trusted_html"></section>
                        <!-- Login message -->

                        <section id="tickets-nav" ng-if="user_id">
                            <h2>{{ lang.quick_links }}</h2>
                            <nav class="nav-tickets">
                                <ul>
                                    <li ng-class="{active:post_status == 'publish'}">
                                        <i class="fa fa-mail-reply-all"></i>&nbsp;
                                        <a href="#/response-required">{{ lang.response_required }}</a>
                                        <span ng-if="number_response_required" class="badge pull-right">{{ number_response_required }}</span>
                                        <i class="active-icon fa fa-chevron-right pull-right"></i>
                                    </li>
                                    <li ng-class="{active:post_status == 'publish'}">
                                        <i class="fa fa-folder-open"></i>&nbsp;
                                        <a href="#/tickets">{{ lang.all_open_tickets }}</a>
                                        <span ng-if="number_open_tickets" class="badge pull-right">{{ number_open_tickets }}</span>
                                        <i class="active-icon fa fa-chevron-right pull-right"></i>
                                    </li>
                                    <li ng-class="{active:post_status == 'starred'}">
                                        <i class="fa fa-star"></i>&nbsp;
                                        <a href="#/tickets/starred">{{ lang.starred_tickets }}</a>
                                        <span ng-if="number_starred_tickets" class="badge pull-right">{{ number_starred_tickets }}</span>
                                        <i class="active-icon fa fa-chevron-right pull-right"></i>
                                    </li>
                                    <li ng-class="{active:post_status == 'public'}">
                                        <i class="fa fa-users"></i>&nbsp;
                                        <a href="#/tickets/public">{{ lang.public_tickets }}</a>
                                        <span ng-if="number_public_tickets" class="badge pull-right">{{ number_public_tickets }}</span>
                                        <i class="active-icon fa fa-chevron-right pull-right"></i>
                                    </li>
                                    <li ng-class="{active:post_status == 'closed'}">
                                        <i class="fa fa-archive"></i>&nbsp;
                                        <a href="#/tickets/closed">{{ lang.closed_tickets }}</a>
                                        <span ng-if="number_closed_tickets" class="badge pull-right">{{ number_closed_tickets }}</span>
                                        <i class="active-icon fa fa-chevron-right pull-right"></i>
                                    </li>
                                </ul>
                            </nav>

                            <div ng-if="isClient">
                                <div class="sep"></div>
                                <a href="#/new-ticket" class="btn btn-block btn-success">{{ lang.submit_new_ticket }}</a>
                            </div>
                            <div ng-if="!isClient">
                                <div class="sep"></div>
                                <a href="#/new-ticket-client" class="btn btn-block btn-success">{{ lang.create_new_ticket }}</a>
                            </div>

                            <div ng-if="isAdmin">
                                <div class="sep"></div>
                                <h2 class="margin-30-top">{{ lang.employees }}</h2>
                                <nav class="nav-tickets scroll">
                                    <ul>
                                        <li ng-repeat="employee in lang.employees_array">
                                            <i class="fa fa-user"></i>&nbsp;
                                            <a href="#/tickets/user/{{ employee.id }}">{{ employee.name }}</a>
                                            <i class="active-icon fa fa-chevron-right pull-right"></i>
                                        </li>
                                    </ul>
                                </nav>

                                <div class="sep"></div>
                                <h2 class="margin-30-top">{{ lang.products }}</h2>
                                <nav class="nav-tickets scroll">
                                    <ul>
                                        <li ng-class="{active:product_slug == product.id}" ng-repeat="product in lang.products_array">
                                            <i class="fa fa-tag"></i>&nbsp;
                                            <a href="#/tickets/products/{{ product.id }}">{{ product.name }}</a>
                                            <i class="active-icon fa fa-chevron-right pull-right"></i>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                            <div ng-if="isAgent">
                                <div class="sep"></div>
                                <h2 class="margin-30-top">{{ lang.products }}</h2>
                                <nav class="nav-tickets">
                                    <ul>
                                        <li ng-class="{active:product_slug == product.id}" ng-repeat="product in lang.products_array">
                                            <i class="fa fa-tag"></i>&nbsp;
                                            <a href="#/tickets/products/{{ product.id }}">{{ product.name }}</a>
                                            <i class="active-icon fa fa-chevron-right pull-right"></i>
                                        </li>
                                    </ul>
                                </nav>
                            </div>

                            <div ng-bind-html="lang.sidebar_message | trusted_html"></div>

                        </section>
                        <!-- Sidebar Contents -->
                    </div>
                </div>
                <!-- load partials -->
                <div class="ngview" ng-view></div>
                <!-- load partials -->
            </div>
        </div>
    </section>

    <input type="hidden" value="<?php echo cjsupport_item_path('item_url').'/templates/partials' ?>" id="cjsupport-partials-path" />
    <input type="hidden" value="<?php echo site_url().'?upload_files=1' ?>" id="cjsupport-upload-path" />
    <div class="loading angular-load"></div>
    <div class="spinner angular-load">
        <div class="rect1"></div>
        <div class="rect2"></div>
        <div class="rect3"></div>
        <div class="rect4"></div>
        <div class="rect5"></div>
    </div>

    <div ng-include="'canned-responses.html'"></div>
    <script type="text/ng-template" id="canned-responses.html">
        <?php require_once(sprintf('%s/templates/partials/includes/canned-responses.php', cjsupport_item_path('item_dir'))); ?>
    </script>

</body>
</html>