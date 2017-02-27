</div>
<div class="page-footer">
    <div class="page-footer-inner"> 2016 &copy;. </div>
    <div class="scroll-to-top"> <i class="icon-arrow-up"></i> </div>
</div>
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="<?php echo base_url();?>plugins/respond.min.js"></script>
<script src="<?php echo base_url();?>plugins/excanvas.min.js"></script>
<![endif]-->
<script src="<?php echo base_url();?>plugins/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>plugins/jquery-migrate.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url();?>plugins/select2/select2.min.js"></script>
<script src="<?php echo base_url();?>plugins/dropzone/dropzone.js"></script>
<script src="<?php echo base_url();?>js/metronic.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>js/layout.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>js/form-validation.js"></script>
<script src="<?php echo base_url();?>js/components-dropdowns.js"></script>
<script src="<?php echo base_url();?>js/form-dropzone.js"></script>
<script src="<?php echo base_url();?>js/callFunction.js?v=<?php echo time();?>"></script>
<script type="text/javascript" src="<?php echo base_url();?>plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>plugins/datatables/extensions/TableTools/js/dataTables.tableTools.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/excellentexport.js"></script>
<script>
    jQuery(document).ready(function() {
        Metronic.init(); // init metronic core components
        Layout.init(); // init current layout
        FormDropzone.init();
        ComponentsDropdowns.init();
    });

</script>
<script>
    $("#selectAlluserrep").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });
</script>
<script src="<?php echo base_url();?>js/interactions.js"></script>
<script src="<?php echo base_url();?>js/Application.js?v=2"></script>
<script src="<?php echo base_url();?>js/validate.js?v=2"></script>
<!-- BEGIN PAGE LEVEL PLUGINS -->
</body>
</html>