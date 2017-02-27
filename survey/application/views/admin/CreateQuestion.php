<div class="page-content-wrapper">
    <div class="page-content">
        <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
        <!-- BEGIN PAGE HEADER-->
<!--        <h3 class="page-title"> &nbsp; </h3>-->
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
                        <div class="caption"> <i class="fa fa-gift"></i> Add Proposition</div>
                        <div class="tools hidden-xs"> <a href="javascript:;" class="collapse"> </a> <a href="#portlet-config" data-toggle="modal" class="config"> </a> <a href="javascript:;" class="reload"> </a> <a href="javascript:;" class="remove"> </a> </div>
                    </div>
                    <div class="portlet-body form">
                        <form action="" class="form-horizontal" id="submit_form" method="POST" onsubmit="return questionValidate();">
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
                                                <label class="col-md-3 control-label">Select Group:</label>
                                                <div class="col-md-4">
                                                    <select name="SurveyQuestionGroup_Id" id="SurveyQuestionGroup_Id" class="form-control">
                                                        <option value="">Select Group</option>
                                                        <?php foreach($questiongroup as $key=>$list){
                                                            if(@$SurveyQuestionGroup_Id==$key){$sel="selected";}else{$sel="selected";}
                                                            ?>
                                                            <option value="<?php echo $key;?>" <?php echo $sel;?> ><?php echo $list;?></option>
                                                        <?php }?>
                                                    </select>
                                                    <div class="error" style="display:none;" id="ERROR_Survey_Id"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Proposition Text:</label>
                                                <div class="col-md-4">
                                                    <textarea class="form-control" name="QuestionText" id="QuestionText" rows="5"><?php echo @$_REQUEST['QuestionText'];?></textarea>
                                                    <div class="error" style="display:none;" id="ERROR_QuestionText"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"></label>
                                                <div class="col-md-4">
<!--                                                    <input type="hidden" name="SurveyQuestionGroup_Id" id="SurveyQuestionGroup_Id" value="--><?php //echo $SurveyQuestionGroup_Id; ?><!--">-->
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