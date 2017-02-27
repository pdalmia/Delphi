<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
    <meta charset="utf-8"/>
    <title><?php echo $header_title;?></title>
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
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
    <link href="<?php echo base_url();?>css/layout.css?v=1" rel="stylesheet" type="text/css"/>
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
                    <li class="dropdown dropdown-user">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <span class="username username-hide-on-mobile"><?php echo @$_SESSION['FirstName']; ?> </span> <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                            <li> <a href="<?php echo base_url()."Admin/ChangePassword";?>"> <i class="icon-key"></i> Change Password </a> </li>
                            <li> <a href="<?php echo base_url()."Admin/Logout";?>"> <i class="icon-key"></i> Log Out </a> </li>
                        </ul>
                    </li>
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
    <div class="page-sidebar-wrapper">
        <div class="page-sidebar navbar-collapse collapse">
            <ul class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
                <li class="sidebar-toggler-wrapper">
                    <div class="sidebar-toggler"> </div>
                </li>
                <?php
                $firstcp_cls = 'start active';
                $selecedcss = '<span class="selected"></span>';
                if ($method == 'index') {
                    $firstcp_cls = 'start active';
                    $selecedcss = '<span class="selected"></span>';
                } else {
                     $firstcp_cls = '';
                     $selecedcss = '';
                }
                ?>
                <li class="<?php echo $firstcp_cls; ?>"> <a href="<?php echo base_url()."Admin/Index";?>"> <i class="fa fa-dashboard"></i> <span class="title"> Support Centre </span> <?php echo $selecedcss; ?></a> </li>
                <!-- END DASHBOARD LINK -->
                <?php
                $firstcp_cls = '';
                $selecedcss = '';
                $arrow = 'arrow';
                if($method=='CreateAdmin' || $method=='ManageAdmin' || $method=='EditAdmin'
                        || $method=='ManageUser' || $method=='CreateUser' || $method=='EditUser'
                        || $method=='ManageProposition' || $method=='CreateProposition' || $method=='EditProposition'
                        || $method=='ManageSurvey' || $method=='CreateSurvey' || $method=='EditSurvey'
                        || $method=='ManageQuestionGroup' || $method=='CreateQuestionGroup' || $method=='EditQuestionGroup'
                        || $method=='ManageQuestion' || $method=='CreateQuestion' || $method=='EditQuestion'
                        || $method=='ManageTemplate' || $method=='CreateTemplate' || $method=='EditTemplate' || $method=='ManageDefaultQuestion'
                        || $method=='ManageSurveyUser' || $method=='ManageSurveyUrl' || $method=='ManageeQuestion' || $method=='CreateeQuestion' || $method=='EditeQuestion'
						|| $method=='ManageSurveyStatus'
                        ){
                    $firstcp_cls = 'active open';
                    $selecedcss = '<span class="selected"></span>';
                    $arrow = 'arrow open';
                } else {
                    $firstcp_cls = '';
                    $selecedcss = '';
                    $arrow = 'arrow';
                }?>
                <li class="<?php echo $firstcp_cls; ?>"> <a href="javascript:;"> <i class="fa fa-user"></i> <span class="title">Administrator</span> <?php echo $selecedcss; ?><span class="<?php echo $arrow; ?>"></span> </a>
                    <ul class="sub-menu">
                        <?php
                        if($_SESSION['Admin_Id']==1) {
                            if ($method == 'createadmin' || $method == 'manageadmin' || $method == 'editadmin' || $method == 'index') {
                                $prefixAcitve = "active";
                            } else {
                                $prefixAcitve = "";
                            } ?>
                            <li class="<?php echo $prefixAcitve; ?>"><a href="<?php echo base_url() . "Admin/ManageAdmin"; ?>"> <i class="fa fa-cubes"></i> Admins Management</a></li>
                            <?php
                        }else{
                            if ($method == 'manageuser' || $method=='createuser' || $method=='edituser') {
                                $accountAcitve = "active";
                            } else {
                                $accountAcitve = "";
                            }?>
                            <li class="<?php echo $accountAcitve; ?>"> <a href="<?php echo base_url()."Admin/ManageUser";?>"> <i class="fa fa-wrench"></i> Respondent Management</a> </li>
                            
                            <?php
                            if ($method == 'managesurvey' || $method=='createsurvey' || $method=='editsurvey'|| $method=='managesurveyuser' || $method=='managesurveyurl') {
                                $accountAcitve = "active";
                            } else {
                                $accountAcitve = "";
                            }?>
                            <li class="<?php echo $accountAcitve; ?>"> <a href="<?php echo base_url()."Admin/ManageSurvey";?>"> <i class="fa fa-wrench"></i> Survey Management</a> </li>
                            <?php
                            if($method == 'managequestiongroup' || $method=='createquestiongroup' || $method=='editquestiongroup' ||
                                    $method == 'managequestion' || $method=='createquestion' || $method=='editquestion' || $method=='managedefaultquestion' || $method=='manageequestion' || $method=='createequestion' || $method=='editequestion') {
                                $accountAcitve = "active";
                            } else {
                                $accountAcitve = "";
                            }?>
                            <li class="<?php echo $accountAcitve; ?>"> <a href="<?php echo base_url()."Admin/ManageQuestionGroup";?>"> <i class="fa fa-wrench"></i> Survey Propositions</a> </li>
                            <?php 
                            if ($method == 'manageproposition' || $method=='createproposition' || $method=='editproposition') {
                                $accountAcitve = "active";
                            } else {
                                $accountAcitve = "";
                            }?>
                            <li class="<?php echo $accountAcitve; ?>"> <a href="<?php echo base_url()."Admin/ManageProposition";?>"> <i class="fa fa-wrench"></i> Survey Range Descriptions</a> </li>
                            <?php
                            if($method == 'managetemplate' || $method=='createtemplate' || $method=='edittemplate') {
                                $accountAcitve = "active";
                            } else {
                                $accountAcitve = "";
                            }?>
                            <li class="<?php echo $accountAcitve; ?>"> <a href="<?php echo base_url()."Admin/ManageTemplate";?>"> <i class="fa fa-wrench"></i> Survey Email Templates</a> </li>
                        <?php /**/
                            
                            }?>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
