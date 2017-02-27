<div class="page-content-wrapper">
    <div class="page-content">
        <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
        <!-- BEGIN PAGE HEADER-->
        <h3 class="page-title"> Change Password</h3>
        <?php
        $mess = $this->session->flashdata('message');
        if ($mess!='') { ?>
            <div class="alert alert-danger">
                <button class="close" data-close="alert"></button>
                <span><?php echo $mess;?>. </span>
            </div>
        <?php }?>
        <!-- BEGIN PAGE CONTENT-->
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box blue-hoki" id="form_wizard_1">
                    <div class="portlet-title">
                        <div class="caption"> <i class="fa fa-gift"></i> Password Wizard</div>
                        <div class="tools hidden-xs"> <a href="javascript:;" class="collapse"> </a> <a href="#portlet-config" data-toggle="modal" class="config"> </a> <a href="javascript:;" class="reload"> </a> <a href="javascript:;" class="remove"> </a> </div>
                    </div>
                    <div class="portlet-body form">
                        <form action="" class="form-horizontal" id="submit_form" method="POST" onsubmit="return userValidate();">
                            <div class="form-wizard">
                                <div class="form-body">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab1">
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Old Password:</label>
                                                <div class="col-md-4">
                                                    <input type="password" class="form-control" placeholder="Old Password" maxlength="150" value="" id="old_password" name="old_password">
                                                    <div class="error" style="display:none;" id="ERROR_old_password"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">New Password:</label>
                                                <div class="col-md-4">
                                                    <input type="password" class="form-control" name="new_password" maxlength="150" id="new_password" value="" placeholder="New Password">
                                                    <div class="error" style="display:none;" id="ERROR_new_password"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Confirm New Password:</label>
                                                <div class="col-md-4">
                                                    <input type="password" class="form-control" name="cnew_password" id="cnew_password" value="" placeholder="Confirm Password">
                                                    <div class="error" style="display:none;" id="ERROR_cnew_password"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label"></label>
                                                <div class="col-md-4">
                                                    <button type="submit" name="update" value="Update" class="btn green button-submit"> Update <i class="m-icon-swapright m-icon-white"></i> </button>
                                                    <button type="button" name="Back" value="Back" class="btn button-submit" onclick="return history.back();"> Cancel <i class="m-icon-swapright m-icon-white"></i> </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT-->
    </div>
</div>