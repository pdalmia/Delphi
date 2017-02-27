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
<script src="<?php echo base_url();?>js/callFunction.js"></script>
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
    /*
    setTimeout(function() {
        $('.alert-success').fadeOut('slow');
    }, 4000); // <-- time in milliseconds

    //var currenttime = '<!--#config timefmt="%B %d, %Y %H:%M:%S"--><!--#echo var="DATE_LOCAL" -->' //SSI method of getting server date
    var currenttime = '<?php print date("F d, Y H:i:s", time())?>' //PHP method of getting server date

    ///////////Stop editting here/////////////////////////////////

    var montharray=new Array("Jan","Feb","Mar","Apr","May","June","July","Aug","Sep","Oct","Nov","Dec")
    var serverdate=new Date(currenttime)

    function padlength(what){
        var output=(what.toString().length==1)? "0"+what : what
        return output
    }

    function displaytime(){
        serverdate.setSeconds(serverdate.getSeconds()+1)
        var datestring=montharray[serverdate.getMonth()]+" "+padlength(serverdate.getDate())+", "+serverdate.getFullYear()
        var timestring=padlength(serverdate.getHours())+":"+padlength(serverdate.getMinutes())+":"+padlength(serverdate.getSeconds())
        document.getElementById("servertimeid").innerHTML=datestring+" "+timestring
    }

    window.onload=function(){
        setInterval("displaytime()", 1000)
    }/**/

</script>

<script src="<?php echo base_url();?>js/interactions.js"></script>
<script src="<?php echo base_url();?>js/Application.js"></script>
<script src="<?php echo base_url();?>js/validate.js"></script>
<!-- BEGIN PAGE LEVEL PLUGINS -->
</body>
</html>