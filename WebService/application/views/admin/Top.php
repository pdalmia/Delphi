<nav class="navbar navbar-inverse" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="nav bar-header"><span class="logo" style="float:left;"><img src="<?php echo base_url();?>img/prismatics_logo.jpg" style="width: 160px" height="70px;" />&nbsp;<b>Insight Products</b></span> 
            <div style="float:right;">
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown"> <a href="javascript: void(0);" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-user"></i> &nbsp; <?php echo $this->session->userdata('FirstName');?> <b class="caret" style="border-top-color:#26aae1; border-bottom-color:#26aae1;"></b> </a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo base_url();?>Admin/Logout">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>
<?php
$controller = $this->router->fetch_class(); // for controller
$method = $this->router->fetch_method(); // for method
?>
<div class="subnavbar">
    <div class="subnavbar-inner">
        <div class="container"> <a data-target=".subnav-collapse" data-toggle="collapse" class="subnav-toggle" href="javascript: void(0);"> <span class="sr-only">Toggle navigation</span> <i class="icon-reorder"></i> </a>
            <div class="collapse subnav-collapse">
                <ul class="mainnav">
                    <li class="<?php if($method=='index'){echo "active";}?>"><a href="<?php echo base_url();?>admin/index/"><i class="icon-th"></i><span>Support Centre</span>  </a></li>
                    <li class="<?php if($method=='manageuser' ||  $method=='createuser' || $method=='edituser'){echo "active";}?>"><a href="<?php echo base_url();?>Admin/ManageUser/"><i class="icon-male"></i><span>Manage & Create BCG Admin Users</span>  </a></li>
                    <li class="<?php if($method=='managecompany' || $method=='leadershipformedit' || $method=='editleadership' || $method=='createcompany' || $method=='editcompany' || $method=='formtype' || $method=='formedit' || $method=='editcontact' || $method=='managecuser' || $method=='createcuser' || $method=='editcuser'){echo "active";}?>"><a href="<?php echo base_url();?>Admin/manageCompany/"><i class="icon-male"></i><span>Manage & Create Client Databases</span>  </a></li>
                    <li class="<?php if($method=='reports' || $method=='createform' || $method=='editform' || $method=='managerepuser' || $method=='viewreportdata' || $method=='showreport' || $method=='useraccessaudit'){echo "active";}?>"><a href="<?php echo base_url();?>Admin/reports/"><i class="icon-male"></i><span>Reports</span>  </a></li >
                </ul>
            </div>
            <!-- /.subnav-collapse -->
        </div>
        <!-- /container -->
    </div>
    <!-- /subnavbar-inner -->
</div>