<div class="page-content-wrapper">
	<div class="page-content">
		<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
		<!-- BEGIN PAGE HEADER-->
		<h3 class="page-title"> Account Verification</h3>
		<?php
		$mess = $this->session->flashdata('message');
		if ($mess!='') { ?>
			<div class="alert-success2 alert">
				<?php echo $mess['message']; ?>
			</div>
		<?php }?>

	</div>
</div>