<section ng-controller="cannedResponsesCtrl">
    <div id="canned-lightbox"></div>
    <div id="canned-responses">
        <div class="panel panel-default">
            <div class="panel-heading">
                <a ng-click="closeCannedResponsePanel()" class="pull-right close" class="pull-right"><i class="fa fa-times"></i></a>
                <b>Canned Response</b>
                <a class="margin-5-left btn btn-xs btn-danger" ng-show="showAddBox" ng-click="showAddBox = !showAddBox"><i class="fa fa-times-circle"></i></a>
                <a class="margin-5-left btn btn-xs btn-success" ng-show="!showAddBox" ng-click="showAddBox = !showAddBox"><i class="fa fa-plus"></i></a>
                <input type="search" ng-model="search_responses" class="margin-5-left" placeholder="<?php _e('Search..', 'cjsupport') ?>">
            </div>
            <div ng-show="showAddBox" class="panel-body sep-bottom">
                <div class="row">
                    <div class="col-md-16">
                        <form ng-submit="saveNewResponse()">
                            <div ng-if="errors" class="alert alert-danger">
                                {{errors}}
                            </div>
                            <div><strong><?php _e('Enter a name and content to save new canned response.', 'cjsupport'); ?></strong></div>
                            <p class="form-group">
                                <input class="form-control" type="text" placeholder="<?php _e('Unique Name', 'cjsupport') ?>" ng-model="cres.rname">
                            </p>
                            <p class="form-group">
                                <textarea class="form-control" placeholder="<?php _e('Content to add', 'cjsupport') ?>" ng-model="cres.rcontent" rows="5"></textarea>
                            </p>
                            <div>
                                <button type="submit" class="btn btn-success btn-sm"><?php _e('Add New Response', 'cjsupport') ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div ng-if="!showAddBox" class="panel-body">
                <ul ng-if="responses" class="canned-responses">
                    <li ng-repeat="res in responses | filter:search_responses">
                        <div class="row">
                            <div class="col-md-3" ng-bind-html="res.name | trusted_html"></div>
                            <div class="col-md-11" ng-bind-html="res.content | trusted_html"></div>
                            <div class="col-md-2">
                                <textarea class="hidden" ng-model="res.full_content"></textarea>
                                <a class="insert-canned-response btn btn-success btn-block btn-sm">
                                    <?php _e('Insert Response', 'cjsupport'); ?>
                                </a>
                            </div>
                        </div>
                    </li>
                </ul>
                <div ng-if="errors" class="alert alert-warning">
                    {{errors}}
                </div>
            </div>

        </div>
    </div>
</section>