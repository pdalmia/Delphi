<?php
if($roundid==2){
    $round2='active';$round1='';
}else{
    $round2='';$round1='active';
}
//$survey_id=base64_encode($survey_id);
?>
<ul class="nav nav-tabs">
    <li class="<?php echo $round1;?>"><a href="<?php echo base_url(); ?>Admin/ManageSurveyUrl/Survey_Id/<?php echo $survey_id; ?>/round/1">Round1</a></li>
    <li class="<?php echo $round2;?>"><a href="<?php echo base_url(); ?>Admin/ManageSurveyUrl/Survey_Id/<?php echo $survey_id; ?>/round/2">Round2</a></li>
</ul>