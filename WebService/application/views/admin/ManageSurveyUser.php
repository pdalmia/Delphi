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
                <?php //include("common_tab.php");?>
                <div class="portlet box blue-hoki" id="form_wizard_1">
                    <div class="portlet-title">
                        <div class="caption"> <i class="fa fa-gift"></i> Manage Survey Respondents &nbsp;::&nbsp; <?php echo $survey_name;?></div>
                        <div class="tools hidden-xs"> <a href="javascript:;" class="collapse"> </a> <a href="#portlet-config" data-toggle="modal" class="config"> </a> <a href="javascript:;" class="reload"> </a> <a href="javascript:;" class="remove"> </a> </div>
                    </div>
                    <div class="portlet-body form">
                        <form action="" class="form-horizontal" id="submit_form" method="POST">
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
                                                <table class="table table-striped table-bordered dataTable no-footer" id="search-acitivity" role="grid">
                                                    <thead class="bg-grey">
                                                    <tr role="row">
                                                        <th style="width: 30px;"><input type="checkbox" id="selectAlluserrep"/></th>
                                                        <th>Respondent</th>
                                                        <th>Email</th>
                                                    </tr>
                                                    </thead>
                                                <?php
                                                $break=2;
                                                $st=0;
                                                $colorst=0;
                                                foreach ($userlist as $ulist) {
                                                    if(in_array($ulist['User_Id'],$data)){
                                                        $uselected="checked";
														$disabled = "disabled";
                                                    }else{
                                                        $uselected="";
														$disabled = "";
                                                    }?>
                                                    <tr role="row">
                                                        <td><input type="checkbox" class="checkbox1" name="surveyUserList[]" value="<?php echo $ulist['User_Id'];?>" <?php echo $uselected;?>   ></td>
													
                                                        <td><?php echo $ulist['FirstName'].' '.$ulist['LastName'];?></td>
                                                        <td><?php echo $ulist['Email'];?></td>
                                                    </tr>
                                                    <?php
//                                                    echo '<div class="col-md-3 col-sm-6  col-xs-12"><input type="checkbox" name="surveyuserlist[]" value="'.$ulist['user_id'].'" '.$uselected.'>&nbsp;'.$ulist['user_fname'].' '.$ulist['user_lname'].'</div>';
//                                                    if($break==$st){
//                                                        echo '</div><div class="col-md-12 clearfix" style="margin-bottom:5px;">';
//                                                        $st=0;
//                                                    }
//                                                    $colorst++;
                                                }?>
                                                </table>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label"></label>
                                                <div class="col-md-4">
                                                    <input type="hidden" name="Survey_Id" id="Survey_Id" value="<?php echo $survey_id;?>">
                                                    <button type="submit" name="submit" value="submit" class="btn green button-submit"> Submit <i class="m-icon-swapright m-icon-white"></i> </button>
                                                    <a href="#portlet-config" data-toggle="modal" class="config"> </a>
                                                    <a href="<?php echo base_url(); ?>Admin/ManageSurvey" class="btn green button-submit">Back</a>
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