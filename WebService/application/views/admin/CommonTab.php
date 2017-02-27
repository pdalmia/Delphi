<?php
if($body=='editsurvey'){
    $surveycust='active';$questcust='';$preposcust='';$templatecust='';$usercust='';
}elseif($body=='managequestiongroup' || $body=='createquestiongroup' || $body=='editquestiongroup' || $body=='managequestion' || $body=='createquestion' || $body=='editquestion') {
    $surveycust='';$questcust='active';$preposcust='';$templatecust='';$usercust='';
}elseif($body=='manageproposition' || $body=='createproposition' || $body=='editproposition') {
    $surveycust='';$questcust='';$preposcust='active';$templatecust='';$usercust='';
}elseif($body=='managetemplate' || $body=='createtemplate' || $body=='edittemplate') {
    $surveycust='';$questcust='';$preposcust='';$templatecust='active';$usercust='';
}else{
    $surveycust='';$questcust='';$preposcust='';$templatecust='';$usercust='active';
}
//$survey_id=base64_encode($survey_id);
?>
<ul class="nav nav-tabs">
    <li class="<?php echo $surveycust;?>"><a href="<?php echo base_url(); ?>Admin/EditSurvey/Survey_Id/<?php echo $Survey_Id; ?>">Survey</a></li>
    <li class="<?php echo $questcust;?>"><a href="<?php echo base_url(); ?>Admin/ManageQuestionGroup/Survey_Id/<?php echo $Survey_Id; ?>">Proposition</a></li>
    <li class="<?php echo $preposcust;?>"><a href="<?php echo base_url(); ?>Admin/ManageProposition/Survey_Id/<?php echo $Survey_Id; ?>">Manage Range Description</a></li>
    <li class="<?php echo $templatecust;?>"><a href="<?php echo base_url(); ?>Admin/ManageTemplate/Survey_Id/<?php echo $Survey_Id; ?>">Templates</a></li>
    <li class="<?php echo $usercust;?>"><a href="<?php echo base_url(); ?>Admin/ManageSurveyUser/Survey_Id/<?php echo $Survey_Id; ?>">Users</a></li>
</ul>