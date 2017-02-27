<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
    <meta charset="utf-8"/>
    <title><?php echo $header_title;?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url();?>plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url();?>plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url();?>plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url();?>plugins/select2/select2.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url();?>plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>
    <link type="text/css" href="<?php echo base_url();?>plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet"/>
    <link href="<?php echo base_url();?>plugins/dropzone/css/dropzone.css" rel="stylesheet"/>
    <link href="<?php echo base_url();?>css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url();?>css/plugins.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url();?>css/layout.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url();?>css/custom.css" rel="stylesheet" type="text/css"/>
    <link id="style_color" href="<?php echo base_url();?>css/themes/grey.css" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="<?php echo base_url();?>img/faviconsss.ico"/>
    <style type="text/css">
        svg {
            font-family: "Helvetica Neue", Helvetica;
        }
        .line {
            fill: none;
            stroke: #000;
            stroke-width: 2px;
        }
    </style>
</head>
<body class="page-header-fixed page-quick-sidebar-over-content">
<div class="page-header navbar">
    <div class="page-header-inner">
        <div class="page-logo"> <a href="#"> <img class="logo-default" src="<?php echo base_url();?>img/logo.png" alt="logo" /> </a> </div>
        <div class="page-top">
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                    <!--li class="dropdown dropdown-user">
                        <a href="<?php echo base_url()."Superadmin/Index";?>" class="dropdown-toggle"  data-close-others="true">
                            <span class="username"><?php echo "Login"; ?> </span>
                        </a>
                    </li -->
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"> </div>
<?php
$controller = $this->router->fetch_class(); // for controller
$method = $this->router->fetch_method(); // for method
?>
<div class="page-container">
    <div class="page-sidebar-wrapper"></div>