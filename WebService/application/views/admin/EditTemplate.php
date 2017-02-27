<script type="text/javascript" src="<?php echo base_url(); ?>/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
    tinymce.init({
        selector: "textarea#TemplateText",
        // theme: "modern",
        plugins: [
            "advlist autolink lists link image charmap hr ",
            "searchreplace ",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "paste textcolor colorpicker textpattern imagetools"
        ],
        toolbar1: "bold italic alignleft aligncenter alignright alignjustify bullist numlist outdent | indent forecolor backcolor",
        //toolbar2: "forecolor backcolor ",
        menubar: false
        //image_advtab: true                
    });
</script>
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
                        <div class="caption"> <i class="fa fa-gift"></i> Edit Email Template</div>
                        <div class="tools hidden-xs"> <a href="javascript:;" class="collapse"> </a> <a href="#portlet-config" data-toggle="modal" class="config"> </a> <a href="javascript:;" class="reload"> </a> <a href="javascript:;" class="remove"> </a> </div>
                    </div>
                    <div class="portlet-body form">
                        <form action="" class="form-horizontal" id="submit_form" method="POST" onsubmit="return templateverify();">
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
                                                <label class="col-md-3 control-label">Template Title:</label>
                                                <div class="col-md-4">
                                                    <input type="text" name="TemplateName" id="TemplateName" class="form-control" value="<?php echo @$data['TemplateName'];?>" placeholder="Template Name" />
                                                    <div class="error" style="display:none;" id="ERROR_TemplateName"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Template Text:</label>
                                                <div class="col-md-6">
                                                    <textarea class="form-control" name="TemplateText" id="TemplateText" rows="5"><?php echo @$data['TemplateText'];?></textarea>
                                                    <div class="error" style="display:none;" id="ERROR_TemplateText"></div>
                                                </div>
                                            </div>                                            
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"></label>
                                                <div class="col-md-4">
                                                    <input type="hidden" name="summary_text_editor" id="summary_text_editor" value="">
                                                    <input type="hidden" name="Template_Id" id="Template_Id" value="<?php echo $data['Template_Id']; ?>">
                                                    <button type="submit" name="submit" value="submit" class="btn green button-submit"> Submit <i class="m-icon-swapright m-icon-white"></i> </button>
                                                    <button type="button" name="Back" value="Back" class="btn green button-submit" onclick="return history.back();"> Back <i class="m-icon-swapright m-icon-white"></i> </button>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Change Keyword</label>
                                                <div class="col-md-6">
                                                    ##FNAME##, ##LNAME##, ##EMAIL##, ##URL##, ##COMPANY_NAME##, ##CURRENT_DATE##
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