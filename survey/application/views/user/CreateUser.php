<div class="page-content-wrapper">
    <div class="page-content">
        <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
        <!-- BEGIN PAGE HEADER-->
        <h3 class="page-title"> Admin Registration Request Form </h3>
        <?php
        $mess = $this->session->flashdata('message');
        if ($mess!='') { ?>
            <div class="alert-success2 alert">
                <?php echo $mess['message']; ?>
            </div>
        <?php }?>
        <!-- BEGIN PAGE CONTENT-->
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box blue-hoki" id="form_wizard_1">
                    <div class="portlet-title">
                        <div class="caption"> <i class="fa fa-gift"></i> Admin Wizard</div>
                        <div class="tools hidden-xs"> <a href="javascript:;" class="collapse"> </a> <a href="#portlet-config" data-toggle="modal" class="config"> </a> <a href="javascript:;" class="reload"> </a> <a href="javascript:;" class="remove"> </a> </div>
                    </div>
                    <div class="portlet-body form">
                        <form action="" class="form-horizontal" id="submit_form" method="POST" onsubmit="return userValidate();">
                            <div class="form-wizard">
                                <div class="form-body">
                                     <div class="tab-content">
                                        <div class="alert alert-danger display-none">
                                            <button class="close" data-dismiss="alert"></button>
                                            You have some form errors. Please check below. </div>
                                        <div class="alert alert-success display-none">
                                            <button class="close" data-dismiss="alert"></button>
                                            Your form validation is successful! </div>
                                        <div class="tab-pane active" id="tab1">
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">First Name:<em>*</em></label>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" placeholder="First Name" maxlength="150" value="<?php echo @$_REQUEST['FirstName'];?>" id="FirstName" name="FirstName">
                                                    <div class="error" style="display:none;" id="ERROR_fname"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Last Name:<em>*</em></label>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" placeholder="Last Name" maxlength="150" value="<?php echo @$_REQUEST['LastName'];?>" id="LastName" name="LastName">
                                                    <div class="error" style="display:none;" id="ERROR_lname"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Organisation:</label>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" name="Company" maxlength="150" id="Company" value="<?php echo @$_REQUEST['Company'];?>" placeholder="Organisation">
                                                    <div class="error" style="display:none;" id="ERROR_company"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Address1:</label>
                                                <div class="col-md-4">
                                                    <textarea class="form-control" name="Address1" id="Address1" maxlength="500" rows="3"><?php echo @$_REQUEST['Address1'];?></textarea>
                                                    <div class="error" style="display:none;" id="ERROR_address1"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Address2:</label>
                                                <div class="col-md-4">
                                                    <textarea class="form-control" name="Address2" id="Address2" maxlength="500" rows="3"><?php echo @$_REQUEST['Address2'];?></textarea>
                                                    <div class="error" style="display:none;" id="ERROR_address2"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Postcode / Zip:</label>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" placeholder="Postcode / Zip" maxlength="150" value="<?php echo @$_REQUEST['ZipCode'];?>" id="ZipCode" name="ZipCode">
                                                    <div class="error" style="display:none;" id="ERROR_zipcode"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Email:<em>*</em></label>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" name="Email" id="Email" value="<?php echo @$_REQUEST['Email'];?>" placeholder="Email Address">
                                                    <div class="error" style="display:none;" id="ERROR_email"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Phone:</label>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control numeric_value" name="Phone" id="Phone" value="<?php echo @$_REQUEST['Phone'];?>" maxlength="19" placeholder="Phone No">
                                                    <div class="error" style="display:none;" id="ERROR_phone"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label"></label>
                                                <div class="col-md-4">
                                                    <button type="submit" name="submit" value="submit" class="btn green button-submit"> Submit <i class="m-icon-swapright m-icon-white"></i> </button>
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