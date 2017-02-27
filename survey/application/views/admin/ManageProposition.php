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
                        <div class="caption"> <i class="fa fa-edit"></i>Range Descriptions List</div>
                        <div class="actions">
                            <a href="<?php echo base_url(); ?>Admin/CreateProposition" role="button" class="btn btn-success btn-sm" data-toggle="modal"> <i class="fa fa-plus"></i> Add Range Description</a>

                        </div>
                    </div>
                    <div class="portlet-body">
                        <!-- BEGIN ADD PREFIX MODEL-->
                        <div class="widget-content" id="data2Display">
							<table class="table table-striped table-bordered" id="search-user">
								<thead class="bg-grey">
									<tr>
										<th>Survey</th>
										<th>Left Text</th>
										<th>Right Text</th>
										<th>D. Left Text</th>
										<th>D. Right Text</th>
										<th>Action</th>
									</tr>								
									</thead>								
									<tbody>								
									<?php foreach ($data as $doc) {?>										
									<tr>											
									<td><?php echo @$doc['Heading']; ?></td>											
									<td><?php echo @$doc['LeftText']; ?></td>											
									<td><?php echo @$doc['RightText']; ?></td>											
									<td><?php echo @$doc['LeftText2']; ?></td>											
									<td><?php echo @$doc['RightText2']; ?></td>											
									<td><?php echo @$doc['Action']; ?></td>										
									</tr>										
									<?php }?>								
									</tbody>							
									</table>
                            <?php //echo $data;?>
                            <div class="pagination-box"><?php //echo $resultSetCount; ?>
                                <ul class="pagination fright">
                                    <?php //echo $pagingHTML = $this->basecontroller->pagingCode($TotalPages, '', 'data2Display', base_url().'admin/manageproposition'); ?>
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