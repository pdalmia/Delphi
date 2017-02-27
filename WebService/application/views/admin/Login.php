<div class="content">
    <!-- BEGIN LOGIN FORM -->
    <form name="form" id="login" class="login-form" action="" method="post" autocomplete="off">
        <h3 class="form-title">Sign In</h3>
        <?php
        $message = $this->session->flashdata('message');
        if ($message!='') {?>
        <div class="alert alert-danger">
            <button class="close" data-close="alert"></button>
            <span><?php echo @$message['message'];?>. </span>
        </div>
        <?php }?>
        <div class="form-group">
            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            <label class="control-label visible-ie8 visible-ie9">Username</label>
            <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="Username" name="userName" id="userName" value="<?php echo @$_REQUEST['userName'] ?>" autocomplete="off" />
            <div class="error" style="display:none;" id="ERROR_user"><p class="error-block"><span></span></p></div>
        </div>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">Password</label>
            <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" id="Password" name="Password" />
            <div class="error" style="display:none;" id="ERROR_password"><p class="error-block"><span></span></p></div>
        </div>
        <div class="form-actions no-bottom-padding">
            <button type="submit" class="btn btn-success uppercase">Login</button>
            <div class="other-links"><a href="<?php echo base_url();?>Superadmin/ForgotPassword">Forgot Password</a>&nbsp;|&nbsp;<a href="<?php echo base_url();?>Registration">Registration</a></div>

        </div>
    </form>
    <!-- END LOGIN FORM -->
</div>