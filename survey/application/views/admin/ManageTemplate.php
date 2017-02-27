<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
<!--        <h3 class="page-title"> &nbsp; </h3>-->
        <!-- END PAGE HEADER-->
        <?php
        $mess = $this->session->flashdata('message');
        if ($mess!='') { ?>
            <div class="alert-success2 alert">
                <?php echo $mess['message']; ?>
            </div>
        <?php }?>
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet box bg-sidebar-soft">
                    <div class="portlet-title">
                        <div class="caption"> <i class="fa fa-edit"></i>Email Template List</div>
                        <div class="actions">
                            <a href="<?php echo base_url(); ?>Admin/CreateTemplate/" role="button" class="btn btn-success btn-sm" data-toggle="modal"> <i class="fa fa-plus"></i> Add Template</a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <!-- BEGIN ADD PREFIX MODEL-->
                        <div class="widget-content" id="data2Display">
                            <?php echo $data;?>
                            <div class="pagination-box"><?php echo $resultSetCount; ?>
                                <ul class="pagination fright" style="float: right;margin-top: -8px">
                                    <?php echo $pagingHTML = $this->Basecontroller->pagingCode($TotalPages, '', 'data2Display', base_url().'Admin/ManageTemplate/'); ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>
</div>
<?php global $global_error; ?>