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
                <?php include("RoundTab.php");?>
                <div class="portlet box blue-hoki" id="form_wizard_1">
                    <div class="portlet-title">
                        <div class="caption"> <i class="fa fa-gift"></i> Manage Survey URL &nbsp;::&nbsp; <?php echo $survey_name;?></div>
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
                                                            <th>URL</th>
															<th>Link Emailed</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    //echopre($_SERVER);
													$strtoken='';
                                                    foreach ($data as $ulist) {?>
                                                    <tr role="row">
                                                        <td><input type="checkbox" class="checkbox1" name="surveyuserlist[]" value="<?php echo $ulist['User_Id'];?>" ></td>
                                                        <td><?php echo $ulist['FirstName'].' '.$ulist['LastName'];?></td>
                                                        <td><?php if(@$selecteduser[$ulist['User_Id']]!=""){echo BASE_URL_SURVEY.@$selecteduser[$ulist['User_Id']];
														$strtoken.=$ulist['User_Id'].',';
														}
                                                            //$surveyUrl="http://".$_SERVER['SERVER_NAME'].base_url()."admin/displaysurvey/survey/".rand(1000,9999)."$$".base64_encode($ulist['User_Id'])."$$".base64_encode($survey_id);
                                                            //echo $surveyUrl;?>
                                                        <!--input type="hidden" name="survey_url[]" value="<?php echo @$surveyUrl;?>" -->
                                                        </td>
														<td><?php if(@$mailSended[$ulist['User_Id']]!=""){echo @$mailSended[$ulist['User_Id']];}?></td>
                                                    </tr>
                                                    <?php }
													$strtoken=trim($strtoken,",");
													?>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-3 control-label"></label>
                                                <div class="col-md-5">
													<input type="hidden" name="strtoken" id="strtoken" value="<?php echo $strtoken;?>">
                                                    <input type="hidden" name="Survey_Id" id="Survey_Id" value="<?php echo $survey_id;?>">
                                                    <input type="hidden" name="roundid" id="roundid" value="<?php echo $roundid;?>">
													<button type="submit" name="submit" value="submit" class="btn green button-submit" onclick="return checkassignurl();"> Assign Link <i class="m-icon-swapright m-icon-white"></i> </button>
                                                    <button type="submit" name="sendurlmail" value="sendurlmail" class="btn green button-submit"> Send Email <i class="m-icon-swapright m-icon-white"></i> </button>
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