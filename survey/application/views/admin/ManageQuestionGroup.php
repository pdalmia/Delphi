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
                        <div class="caption"> <i class="fa fa-edit"></i>Proposition Group List</div>
                        <div class="actions">
                            <a href="<?php echo base_url(); ?>Admin/CreateQuestionGroup" role="button" class="btn btn-success btn-sm" data-toggle="modal"> <i class="fa fa-plus"></i> Add Proposition Group</a>
						    <a href="<?php echo base_url(); ?>Admin/ManageeQuestion" role="button" class="btn btn-success btn-sm" data-toggle="modal"> <i class="fa fa-plus"></i> Manage Propositions</a>
<!--                            <a href="--><?php //echo base_url(); ?><!--admin/managedefaultquestion" role="button" class="btn btn-success btn-sm" data-toggle="modal"> <i class="fa fa-plus"></i> Manage Default Proposition</a>-->
                        </div>
                    </div>
                    <div class="portlet-body">
                        <!-- BEGIN ADD PREFIX MODEL-->
                        <div class="widget-content" id="data2Display">
                            <?php //echo $data;?>
                            <table class="table table-striped table-bordered" id="search-questiongroup">
                                <thead class="bg-grey">
                                <tr>
                                    <th>Proposition Group</th>
                                    <th>Survey</th>
                                    <!--th>Order Number</th -->
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($data as $doc) {?>
                                    <tr>
                                        <td><?php echo @$doc['Title']; ?></td>
                                        <td><?php echo @$doc['Heading']; ?></td>
                                        <!--td><?php echo @$doc['QGroupOrder']; ?></td-->
                                        <td>
                                            <a href="<?php echo base_url();?>Admin/EditQuestionGroup/SurveyQuestionGroup_Id/<?php echo @$doc['SurveyQuestionGroup_Id']; ?>" role="button" data-toggle="modal"> Edit </a> |
                                            <a href="<?php echo base_url();?>Admin/DeleteQuestionGroup/SurveyQuestionGroup_Id/<?php echo @$doc['SurveyQuestionGroup_Id']; ?>" role="button" data-toggle="modal" onclick="return confirm('Are you sure you want to delete this record?');"> Delete </a></td>
                                    </tr>
                                    <?php
                                }?>
                                </tbody>
                            </table>
<!--                            <div class="pagination-box">--><?php //echo $resultSetCount; ?>
<!--                                <ul class="pagination fright">-->
<!--                                    --><?php //echo $pagingHTML = $this->basecontroller->pagingCode($TotalPages, '', 'data2Display', base_url().'admin/createquestiongroup'); ?>
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