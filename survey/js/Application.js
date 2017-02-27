function adminuserValidate(){
    var isError = true;
    var fname = $("#FirstName").val();
    var lname = $("#LastName").val();
    var company = $("#Company").val();
    var address1 = $("#Address1").val();
    var address2 = $("#Address2").val();
    var zipcode = $("#ZipCode").val();
    var email = $("#Email").val();
    var password = $("#Password").val();
    var phone = $("#Phone").val();
    if (fname=='') {
        $('#ERROR_fname').html('<p class="error-block"><span>Enter first name.</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_fname').html('').show();
    }
    if (lname=='') {
        $('#ERROR_lname').html('<p class="error-block"><span>Enter last name.</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_lname').html('').show();
    }
    if (company=='') {
        $('#ERROR_company').html('<p class="error-block"><span>Enter organisation name.</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_company').html('').show();
    }
    if (address1=='') {
        $('#ERROR_address1').html('<p class="error-block"><span>Enter address1.</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_address1').html('').show();
    }
    if (zipcode=='') {
        $('#ERROR_zipcode').html('<p class="error-block"><span>Enter Postcode/Zip.</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_zipcode').html('').show();
    }
    if (email=='') {
       $('#ERROR_email').html('<p class="error-block"><span>Enter email address.</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_email').html('').show();
    }
    if (checkemail($("#Email").val()) == false) {
        $('#ERROR_email').html('<p class="error-block"><span>Enter email valid address</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_email').html('').hide();
    }
    if (password=='') {
        $('#ERROR_password').html('<p class="error-block"><span>Enter password.</span></p>').show();
        isError = false;
    }else{
        if (passwordcheck($("#Password").val()) == false) {
            $('#ERROR_password').html('<p class="error-block"><span>At least one letter and one number <br>At least one special character <br> Passwords are case sensitive.</span></p>').show();
            isError = false;
        }else{
            $('#ERROR_password').html('').hide();
        }
    }
    if (phone=='') {
        $('#ERROR_phone').html('<p class="error-block"><span>Enter phone.</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_phone').html('').show();
    }
    return isError;
}

function userValidate(){
    var isError = true;
    var fname = $("#FirstName").val();
    var lname = $("#LastName").val();
    var company = $("#Company").val();
    var address1 = $("#Address1").val();
    var address2 = $("#Address2").val();
    var zipcode = $("#ZipCode").val();
    var email = $("#Email").val();
    var phone = $("#Phone").val();
    if (fname=='') {
        $('#ERROR_fname').html('<p class="error-block"><span>Enter first name.</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_fname').html('').show();
    }
    if (lname=='') {
        $('#ERROR_lname').html('<p class="error-block"><span>Enter last name.</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_lname').html('').show();
    }
    if (email=='') {
        $('#ERROR_email').html('<p class="error-block"><span>Enter email address.</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_email').html('').show();
    }
    if (checkemail($("#Email").val()) == false) {
        $('#ERROR_email').html('<p class="error-block"><span>Enter email valid address</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_email').html('').hide();
    }/*
    if (company=='') {
        $('#ERROR_company').html('<p class="error-block"><span>Enter organisation name.</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_company').html('').show();
    }
    if (address1=='') {
        $('#ERROR_address1').html('<p class="error-block"><span>Enter address1.</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_address1').html('').show();
    }
    
    if (address2=='') {
        $('#ERROR_address2').html('<p class="error-block"><span>Enter address2.</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_address2').html('').show();
    }

    if (zipcode=='') {
        $('#ERROR_zipcode').html('<p class="error-block"><span>Enter Postcode/Zip.</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_zipcode').html('').show();
    }
    if (phone=='') {
        $('#ERROR_phone').html('<p class="error-block"><span>Enter phone.</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_phone').html('').show();
    }/**/
    return isError;
}
function surveyValidate(){
    var isError = true;
    var Heading = $("#Heading").val();
    var Subheading = $("#Subheading").val();
    var Title = $("#Title").val();
    var WelcomeParagraph = $("#WelcomeParagraph").val();
    var Subtitle = $("#Subtitle").val();
    var SummaryNote = $("#SummaryNote").val();  
    
    if (Heading=='') {
        $('#ERROR_Heading').html('<p class="error-block"><span>Enter Heading.</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_Heading').html('').show();
    }
	if (Heading.length >= 500) {
        $('#ERROR_Heading').html('<p class="error-block"><span>Maximun Limit is 500 Characters.</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_Heading').html('').show();
    }
    if (Subheading.length >= 500) {
        $('#ERROR_Subheading').html('<p class="error-block"><span>Maximun Limit is 500 Characters.</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_Subheading').html('').show();
    }
//    if (Title=='') {
//        $('#ERROR_Title').html('<p class="error-block"><span>Enter Title.</span></p>').show();
//        isError = false;
//    }else{
//        $('#ERROR_Title').html('').show();
//    }
    if (WelcomeParagraph=='') {
        $('#ERROR_WelcomeParagraph').html('<p class="error-block"><span>Enter Welcome Paragraph.</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_WelcomeParagraph').html('').show();
    }
    /*if (Subtitle=='') {
        $('#ERROR_Subtitle').html('<p class="error-block"><span>Enter Subtitle.</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_Subtitle').html('').show();
    }/**/
    if (SummaryNote=='') {
        $('#ERROR_SummaryNote').html('<p class="error-block"><span>Enter Summary Note.</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_SummaryNote').html('').show();
    }
    return isError;
}
function surveygroupValidate(){
    var isError = true;
    var Survey_Id = $("#Survey_Id").val();
    var Title = $("#Title").val();
    if (Survey_Id=='') {
        $('#ERROR_Survey_Id').html('<p class="error-block"><span>Select Survey.</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_Survey_Id').html('').show();
    }
    if (Title=='') {
        $('#ERROR_Title').html('<p class="error-block"><span>Enter Group Title.</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_Title').html('').show();
    }
    return isError;
}
function questionValidate(){
    var isError = true;
    var QuestionText = $("#QuestionText").val();
	var SurveyId = $("#Survey_Id").val();
	if (SurveyId=='') {
        $('#ERROR_Survey_Id').html('<p class="error-block"><span>Select Survey.</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_Survey_Id').html('').show();
    }
    if (QuestionText=='') {
        $('#ERROR_QuestionText').html('<p class="error-block"><span>Enter Question Text.</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_QuestionText').html('').show();
    }
    return isError;
}
function propositionValidate(){
    var isError = true;
    var Survey_Id = $("#Survey_Id").val();
    var LeftText = $("#LeftText").val();
    var RightText = $("#RightText").val();
    var CommentText = $("#CommentText").val();
    if (Survey_Id=='') {
        $('#ERROR_Survey_Id').html('<p class="error-block"><span>Select Survey.</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_Survey_Id').html('').show();
    }
    if (LeftText=='') {
        $('#ERROR_LeftText').html('<p class="error-block"><span>Enter Left Text.</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_LeftText').html('').show();
    }
    if (RightText=='') {
        $('#ERROR_RightText').html('<p class="error-block"><span>Enter Right Text.</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_RightText').html('').show();
    }
    if (CommentText=='') {
        $('#ERROR_CommentText').html('<p class="error-block"><span>Enter Comment Text.</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_CommentText').html('').show();
    }
    return isError;
}
function templateverify(){
    var isError = true;
    var TemplateName = $("#TemplateName").val();
    //var TemplateText = $("#TemplateText").val();
    var TemplateText = tinymce.get('TemplateText').getContent();
    $("#summary_text_editor").val(TemplateText);
    var summary_text_editor = $("#summary_text_editor").val();
    if (TemplateName=='') {
        $('#ERROR_TemplateName').html('<p class="error-block"><span>Enter Template Name.</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_TemplateName').html('').show();
    }
    if (summary_text_editor=='') {
        $('#ERROR_TemplateText').html('<p class="error-block"><span>Enter Template Text.</span></p>').show();
        isError = false;
    }else{
        $('#ERROR_TemplateText').html('').show();
    }
    return isError;
}
function surveygroup(surveyid) {
	//alert(surveyid);
	base_url=$("#base_url").val();
	$.ajax({
		type: "POST",
		url: base_url+"admin/getSurveyGroup",
		//dataType: "json",
		data: 'Survey_Id='+surveyid,
		contentType: "application/x-www-form-urlencoded",
		success: function (data) {
			//alert(data);
			$("#SurveyQuestionGroup_Id").html(data);
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(thrownError);
		}
	});

    return false;
}
function checkassignurl(){
	var myarray = [];
	var chks = document.getElementsByName('surveyuserlist[]');	
	var getSelectedURL=$("#strtoken").val();
	if(getSelectedURL!=""){
		splitids=getSelectedURL.split(",");	
		var myarray = [];
		for(x=0;x<splitids.length;x++){
			myarray.push(parseInt(splitids[x]));
		}
		var checkCount = 0;
		var ty = [];
		var n=0;
		for (var i = 0; i < chks.length; i++){
			if (chks[i].checked == true){           
				
				if(jQuery.inArray(parseInt(chks[i].value), myarray) != -1) {
					//alert("is in array");
					n++;
				} else {
					//alert("is NOT in array");
				} 
				checkCount++;
				//alert(chks[i].value);
				//ty[n]=chks[i].value;
				
			}
		}
	}
	if(n>0){
		alert("Url(s) are already associated with some of the selected respondents(s).");
		return false;
	}
	
}