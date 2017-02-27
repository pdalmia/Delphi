<div class="page-content-wrapper">
	<div class="page-content">
		<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
		<!-- BEGIN PAGE HEADER-->
		<h3 class="page-title"> Thank you Message</h3>
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
						<div class="tools hidden-xs"> <a href="javascript:;" class="collapse"> </a> <a href="#portlet-config" data-toggle="modal" class="config"> </a> <a href="javascript:;" class="reload"> </a> <a href="javascript:;" class="remove"> </a> </div>
					</div>
					<div class="portlet-body form">
						<form action="" class="form-horizontal" id="submit_form" method="POST" onsubmit="return userValidate();">
							<div class="form-wizard">
								<div class="form-body">
									<div class="tab-content">
										<p>Thank you for registration.</p>
										<p>You will receive an email with account activation link shortly. You can create survey after that.</p>
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