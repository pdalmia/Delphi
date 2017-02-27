<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
<!--        <h3 class="page-title"> Respondent Management </h3>-->
        <!-- END PAGE HEADER-->
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet box bg-sidebar-soft">
                    <div class="portlet-title">
                        <div class="caption"> <i class="fa fa-edit"></i>Respondent List</div>
                        <div class="actions">
                            <a href="<?php echo base_url(); ?>Admin/CreateUser" role="button" class="btn btn-success btn-sm" data-toggle="modal"> <i class="fa fa-plus"></i> Add Respondent</a>

                        </div>
                    </div>
                    <div class="portlet-body">
                        <!-- BEGIN ADD PREFIX MODEL-->
<!--                        <div class="widget-content" id="data2Display">-->
<!--                            --><?php //echo $data;?>
<!--                            <div class="pagination-box">--><?php //echo $resultSetCount; ?>
<!--                                <ul class="pagination fright">-->
<!--                                    --><?php //echo $pagingHTML = $this->basecontroller->pagingCode($TotalPages, '', 'data2Display', base_url().'Admin/ManageUser'); ?>
<!--                                </ul>-->
<!--                            </div>-->
<!--                        </div>-->

                        <table class="table table-striped table-bordered" id="search-user">
                            <thead class="bg-grey">
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($data as $doc) {?>
                                    <tr>
                                        <td><?php echo @$doc['FirstName']; ?></td>
                                        <td><?php echo @$doc['LastName']; ?></td>
                                        <td><?php echo @$doc['Email']; ?></td>
                                        <td><?php echo @$doc['Phone']; ?></td>
                                        <td>
                                            <a href="<?php echo base_url();?>Admin/EditUser/User_Id/<?php echo @$doc['User_Id']; ?>" role="button" data-toggle="modal"> Edit </a> |
                                            <a href="<?php echo base_url();?>Admin/DeleteUser/User_Id/<?php echo @$doc['User_Id']; ?>" role="button" data-toggle="modal" onclick="return confirm('Are you sure you want to delete this record?');"> Delete </a></td>
                                    </tr>
                                    <?php
                            }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>
</div>
<?php global $global_error; ?>