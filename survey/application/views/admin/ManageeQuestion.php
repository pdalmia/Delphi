<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
<!--        <h3 class="page-title"> &nbsp; </h3>-->
        <!-- END PAGE HEADER-->
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet box bg-sidebar-soft">
                    <div class="portlet-title">
                        <div class="caption"> <i class="fa fa-edit"></i> Proposition List</div>
                        <div class="actions">
                            <a href="<?php echo base_url(); ?>Admin/ManageQuestionGroup" role="button" class="btn btn-success btn-sm" data-toggle="modal"> <i class="fa fa-plus"></i> Back</a>
                            <a href="<?php echo base_url(); ?>Admin/CreateeQuestion/" role="button" class="btn btn-success btn-sm" data-toggle="modal"> <i class="fa fa-plus"></i> Add Proposition</a>							<!--a href="<?php echo base_url(); ?>admin/createquestion/SurveyQuestionGroup_Id/<?php echo $SurveyQuestionGroup_Id;?>" role="button" class="btn btn-success btn-sm" data-toggle="modal"> <i class="fa fa-plus"></i> Manage Proposition</a -->

                        </div>
                    </div>
                    <div class="portlet-body">
                        <!-- BEGIN ADD PREFIX MODEL-->
                        <div class="widget-content" id="data2Display">
                            <?php //echo $data;?>
                            <table class="table table-striped table-bordered" id="search-questiongroup">
                                <thead class="bg-grey">
                                <tr>
                                    <th>Proposition List</th>
                                    <th>Proposition Group</th>
                                    <!--th>Order Number</th-->
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($data as $doc) {?>
                                    <tr>
                                        <td><?php echo @$doc['QuestionText']; ?></td>
                                        <td><?php echo @$doc['Title']; ?></td>
                                        <!--td><?php echo @$doc['QuestionOrder']; ?></td-->
                                        <td>
                                            <a href="<?php echo base_url();?>Admin/EditeQuestion/SurveyQuestion_Id/<?php echo @$doc['SurveyQuestion_Id']; ?>" role="button" data-toggle="modal"> Edit </a> |
                                            <a href="<?php echo base_url();?>Admin/DeleteeQuestion/SurveyQuestion_Id/<?php echo @$doc['SurveyQuestion_Id']; ?>" role="button" data-toggle="modal" onclick="return confirm('Are you sure you want to delete this record?');"> Delete </a></td>
                                    </tr>
                                    <?php
                                }?>
                                </tbody>
                            </table>
<!--                            <div class="pagination-box">--><?php //echo $resultSetCount; ?>
<!--                                <ul class="pagination fright" style="float: right;margin-top: -8px">-->
<!--                                    --><?php //echo $pagingHTML = $this->basecontroller->pagingCode($TotalPages, '', 'data2Display', base_url().'admin/manageeQuestion/'); ?>
<!--                                </ul>-->
<!--                            </div>-->
                        </div>
                    </div>
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>
</div>
<?php global $global_error; ?>