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
                        <div class="caption"> <i class="fa fa-gift"></i> Edit Range Descriptions</div>
                        <div class="tools hidden-xs"> <a href="javascript:;" class="collapse"> </a> <a href="#portlet-config" data-toggle="modal" class="config"> </a> <a href="javascript:;" class="reload"> </a> <a href="javascript:;" class="remove"> </a> </div>
                    </div>
                    <div class="portlet-body form">
                        <form action="" class="form-horizontal" id="submit_form" method="POST" onsubmit="return propositionValidate();">
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
                                                <label class="col-md-3 control-label">Select Survey:</label>
                                                <div class="col-md-4">
                                                    <select name="Survey_Id" id="Survey_Id" class="form-control" disabled>
                                                        <option value="">Select Survey</option>
                                                        <?php foreach($surveylist as $list){
                                                            if($list['Survey_Id']==$data['Survey_Id']){
                                                                $sel="selected";
                                                            }else{
                                                                $sel="";
                                                            }?>
                                                            <option value="<?php echo $list['Survey_Id'];?>" <?php echo $sel;?> ><?php echo $list['Heading'];?></option>
                                                        <?php }?>
                                                    </select>
                                                    <div class="error" style="display:none;" id="ERROR_Survey_Id"></div>
                                                </div>
                                            </div>

                                            <h4 class="bold">Likelihood:</h4>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Left Text:</label>
                                                    <div class="col-md-4">
                                                        <textarea class="form-control" name="likehood_LeftText" id="likehood_LeftText" rows="5"><?php echo @$data['LeftText'];?></textarea>
                                                        <div class="error" style="display:none;" id="ERROR_likehood_LeftText"></div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Right Text:</label>
                                                    <div class="col-md-4">
                                                        <textarea class="form-control" name="likehood_RightText" id="likehood_RightText" rows="5"><?php echo @$data['RightText'];?></textarea>
                                                        <div class="error" style="display:none;" id="ERROR_likehood_RightText"></div>
                                                    </div>
                                                </div>

                                                <h4 class="bold">Desirability:</h4>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Left Text:</label>
                                                    <div class="col-md-4">
                                                        <textarea class="form-control" name="desirable_LeftText" id="desirable_LeftText" rows="5"><?php echo @$data['LeftText2'];?></textarea>
                                                        <div class="error" style="display:none;" id="ERROR_desirable_LeftText"></div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Right Text:</label>
                                                    <div class="col-md-4">
                                                        <textarea class="form-control" name="desirable_RightText" id="desirable_RightText" rows="5"><?php echo @$data['RightText2'];?></textarea>
                                                        <div class="error" style="display:none;" id="ERROR_desirable_RightText"></div>
                                                    </div>
                                                </div>
                                            <div style="clear:both;">&nbsp;</div>
											<h4 class="bold">Conditional Comment Texts:</h4>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">First Pass Comment:</label>
                                                <div class="col-md-4">
                                                    <textarea class="form-control" name="firstpass_CommentText" id="firstpass_CommentText" rows="5"><?php echo @$data['firstpass_CommentText'];?></textarea>
                                                    <div class="error" style="display:none;" id="ERROR_firstpass_CommentText"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Second Pass Comment:</label>
                                                <div class="col-md-4">
                                                    <textarea class="form-control" name="secondpass_CommentText" id="secondpass_CommentText" rows="5"><?php echo @$data['secondpass_CommentText'];?></textarea>
                                                    <div class="error" style="display:none;" id="ERROR_secondpass_CommentText"></div>
                                                </div>
                                            </div>
											<div class="form-group">
                                                <label class="col-md-3 control-label"></label>
                                                <div class="col-md-4">
                                                    <input type="hidden" name="Survey_Id" id="Survey_Id" value="<?php echo $data['Survey_Id']; ?>">
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