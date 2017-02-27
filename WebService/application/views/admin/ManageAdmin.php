<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
    <div class="page-content">
        <?php
        $mess = $this->session->flashdata('message');
        if ($mess!='') { ?>
            <div class="alert-success2 alert">
                <?php echo $mess['message']; ?>
            </div>
        <?php }?>
        <!-- BEGIN PAGE HEADER-->
        <h3 class="page-title"> Admins Management </h3>
        <!-- END PAGE HEADER-->
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet box bg-sidebar-soft">
                    <div class="portlet-title">
                        <div class="caption"> <i class="fa fa-edit"></i>Listing Admin </div>
                        <div class="actions">
                            <a href="<?php echo base_url(); ?>Admin/CreateAdmin" role="button" class="btn btn-success btn-sm no-right-margin" data-toggle="modal"> <i class="fa fa-plus"></i> Add </a>

                        </div>
                    </div>
                    <div class="portlet-body">
                        <!-- BEGIN ADD PREFIX MODEL-->
                        <div class="widget-content" id="data2Display">
                            <?php echo $data;?>
                            <div class="pagination-box"><?php echo $resultSetCount; ?>
                                <ul class="pagination fright">
                                    <?php echo $pagingHTML = $this->Basecontroller->pagingCode($TotalPages, '', 'data2Display', base_url().'Admin/ManageAdmin'); ?>
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