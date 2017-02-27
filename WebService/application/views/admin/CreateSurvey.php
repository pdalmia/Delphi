<div class="page-content-wrapper">
    <div class="page-content">
        <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
        <!-- BEGIN PAGE HEADER-->
<!--        <h3 class="page-title"> Survey Management </h3>-->
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
                        <div class="caption"> <i class="fa fa-gift"></i> Create Survey</div>
                        <div class="tools hidden-xs"> <a href="javascript:;" class="collapse"> </a> <a href="#portlet-config" data-toggle="modal" class="config"> </a> <a href="javascript:;" class="reload"> </a> <a href="javascript:;" class="remove"> </a> </div>
                    </div>
                    <div class="portlet-body form">
                        <form action="" class="form-horizontal" id="submit_form" method="POST" onsubmit="return surveyValidate();">
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
                                                <label class="col-md-3 control-label">Heading:</label>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" placeholder="Heading" maxlength="150" value="<?php echo @$_REQUEST['Heading'];?>" id="Heading" name="Heading">
                                                    <div class="error" style="display:none;" id="ERROR_Heading"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Subheading:</label>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" placeholder="Subheading" maxlength="150" value="<?php echo @$_REQUEST['Subheading'];?>" id="Subheading" name="Subheading">
                                                    <div class="error" style="display:none;" id="ERROR_Subheading"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Explanatory Note:</label>
                                                <div class="col-md-4">
<!--                                                    <input type="text" class="form-control" name="Title" maxlength="150" id="Title" value="--><?php //echo @$_REQUEST['Explanatory Note'];?><!--" placeholder="Title">-->
                                                    <textarea class="form-control" name="ExplanatoryNote" id="ExplanatoryNote" rows="5"><?php echo @$_REQUEST['ExplanatoryNote'];?></textarea>
<!--                                                    <div class="error" style="display:none;" id="ERROR_Title"></div>-->
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Welcome Page Text</label>
                                                <div class="col-md-4">
                                                    <textarea class="form-control" name="WelcomeParagraph" id="WelcomeParagraph" rows="5"><?php echo @$_REQUEST['WelcomeParagraph'];?></textarea>
                                                    <div class="error" style="display:none;" id="ERROR_WelcomeParagraph"></div>
                                                </div>
                                            </div>
<!--                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Subtitle:</label>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" placeholder="Subtitle" maxlength="150" value="<?php echo @$_REQUEST['Subtitle'];?>" id="Subtitle" name="Subtitle">
                                                    <div class="error" style="display:none;" id="ERROR_Subtitle"></div>
                                                </div>
                                            </div>-->
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Summary Page Text</label>
                                                <div class="col-md-4">
                                                    <textarea class="form-control" name="SummaryNote" id="SummaryNote" rows="5"><?php echo @$_REQUEST['SummaryNote'];?></textarea>
                                                    <div class="error" style="display:none;" id="ERROR_SummaryNote"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"></label>
                                                <div class="col-md-4">
                                                    <button type="submit" name="submit" value="submit" class="btn green button-submit"> Submit <i class="m-icon-swapright m-icon-white"></i> </button>
                                                    <button type="button" name="Back" value="Back" class="btn green button-submit" onclick="return history.back();"> Back <i class="m-icon-swapright m-icon-white"></i> </button>
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