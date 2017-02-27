/*
$(function () {	
    $("table").stickyTableHeaders();

    $( "#fdate" ).datepicker({ dateFormat: 'dd-M-yy', maxDate: -1 });
    $( "#tdate" ).datepicker({ dateFormat: 'dd-M-yy', maxDate: -1 });
	
    $( "#fdatec" ).datepicker({ dateFormat: 'dd-M-yy', maxDate: -1 });
    $( "#tdatec" ).datepicker({ dateFormat: 'dd-M-yy', maxDate: -1 });
	
    $(".ui-lightbox").lightbox ();

    $("#Timeframe").change(function(){
        if ($(this).val() == "4" ) {
                $("#fdate").show("");
                $("#tdate").show("");
        } else {
                $("#fdate").hide("");
                $("#tdate").hide("");
        }
    });
});
/**/
function searchmethod(){
    
    var searchingIn=$("#searchingIn").val();
    var action='';
    if(searchingIn=='cpManagement'){
        action='/cpmanagement/cp/index';
    }else if(searchingIn=='spManagement'){
        action='/spmanagement/sp/index';
    }else if(searchingIn=='routingManagement'){
        action='/routingmanagement/routing/index';
    }else if(searchingIn=='Prefix'){
        action='/prefixmanagement/prefix/index';
    }
    //alert(searchingIn);
    //fullsearchid
     $("#fullsearchid").attr("action", action);
     $("#fullsearchid").submit();
}
function searchfilter(){
    var graphtype=$("#graphtype").val();
    var fdate=$("#fdate").val();
    var tdate=$("#tdate").val();
    var curr_date = new Date();
    var old_date = new Date();
    old_date.setMonth(old_date.getMonth() - 3);
    
    if(graphtype==""){
        alert('Please select report type');
        return false;
    }
    if(graphtype==6){
        if((fdate=="") && (tdate=="") ){   
            alert('Please select date.');
            return false;
        }
        if((fdate!="") && (tdate==""))          {
            alert('Please select To date.');
            return false;
        }
        if((fdate=="") && (tdate!="")){
            alert('Please Select From date.');
            return false;
        }

        fdate       = fdate.split("-");
        //here fdate[2] = year,fdate[1] = month and fadte[0]= day
        fdate[1] = convertMonthformat(fdate[1]);
        fdate       = new Date(fdate[2],  fdate[1]-1, fdate[0]);
        tdate       = tdate.split("-");
        tdate[1] = convertMonthformat(tdate[1]);
        tdate       = new Date(tdate[2],  tdate[1]-1, tdate[0]);
        if (fdate.getTime() > tdate.getTime()) {
            alert("'To' date cannot be before the 'From' date");
            return false;
        }
        
        if(tdate.getTime() > curr_date.getTime()){
            alert("Invalid date selection.");
            return false;
        }
        if(fdate.getTime() < old_date.getTime()){
            alert("Data can be viewed only for the last 2+current month.");
            return false;
        }
//        var date = new Date(2012, 02, 31); // 2012-03-31
//        date.setMonth(date.getMonth() - 1); // This gets 2012-03-02
        
    }
}

function convertMonthformat(dateval){
    var monthNum= "JanFebMarAprMayJunJulAugSepOctNovDec".indexOf(dateval) / 3 + 1; 
    return monthNum;
}


function searchfilterhistory()
{
    var fdate=$("#fdate").val();
    var tdate=$("#tdate").val();
    var timeframe=$("#timeframe").val();
    
    if((fdate=="") && (tdate=="") ){   
        alert('Please select date.');
        return false;
    }
    if((fdate!="") && (tdate==""))          {
        alert('Please select To date.');
        return false;
    }
    if((fdate=="") && (tdate!="")){
        alert('Please Select From date.');
        return false;
    }

    fdate       = fdate.split("-");
    //here fdate[2] = year,fdate[1] = month and fadte[0]= day
    fdate[1] = convertMonthformat(fdate[1]);
    fdate       = new Date(fdate[2],  fdate[1]-1, fdate[0]);
    tdate       = tdate.split("-");
    tdate[1] = convertMonthformat(tdate[1]);
    tdate       = new Date(tdate[2],  tdate[1]-1, tdate[0]);
    if (fdate.getTime() > tdate.getTime()) {
        alert("'To' date cannot be before the 'From' date");
        return false;
    }
    
    if(timeframe==""){
        alert('Please select -Search by-.');
        return false;
    }else{
        if(timeframe==4 && ($("#SENDER_ID").val()==""))
        {
            alert('Please fill Sender Id.');
            return false;
        }else if(timeframe==2 && ($("#MESSAGE_ID").val()=="")){
            alert('Please fill Message Id.');
            return false;
        }else if(timeframe==3 && ($("#MSISDN").val()=="")){
            alert('Please fill MSISDN.');
            return false;
        }else if(timeframe==5 && ($("#ACCOUNT").val()=="")){
            alert('Please fill Account Name.');
            return false;
        }
    }   
}

function networkreturn(){
    var clist='';
     $("#Country option:selected").each(function(){
            clist=clist+$(this).val()+',';
        });
   
    var countryvalue;
    var networkvalue;
    countryvalue=clist; 
  
    _url='/accountmanagement/account/searchnetwork/';
    _url = _url.replace(new RegExp("#", 'g'), "");
    if(_url != 'javascript:void(0);'){
	$.ajaxQueue({
  	    url: _url+"?AJAX=Y",
            type: 'POST',
            data: 'pageName='+_url+"&countryvalue="+countryvalue,
            beforeSend: function( xhr ) {
            xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
		//$("#"+_div).html('<img src="/js/plugins/lightbox/themes/evolution-dark/images/loading.gif">');
            },
    	    success: function(responsedata,textStatus,jqXHR) {
                //alert(responsedata);
                $("#Network").html(responsedata);
                //$("table").stickyTableHeaders();                
            }
    	});
    }
    return false;
}/**/
function accountnetworkreturn(strvalue){
    // alert('_url');
    //alert(strvalue);
    var countryvalue;
    var networkvalue;
    countryvalue=$("#Country").val();     
    _url='/accountmanagement/account/searchnetwork/';
    _url = _url.replace(new RegExp("#", 'g'), "");
    if(_url != 'javascript:void(0);'){
	$.ajaxQueue({
  	    url: _url+"?AJAX=Y",
            type: 'POST',
            data: 'pageName='+_url+"&countryvalue="+countryvalue+"&strvalue="+strvalue,
            beforeSend: function( xhr ) {
            xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
		//$("#"+_div).html('<img src="/js/plugins/lightbox/themes/evolution-dark/images/loading.gif">');
            },
    	    success: function(responsedata,textStatus,jqXHR) {
                //alert(responsedata);
                var result = responsedata.split("###");
                evalScript(result[1]);   
                if(result[0]!=''){
                    //$("#CountryCode").val(result[0]);
                }   
                $("#"+strvalue).html(result[1]);
                $("table").stickyTableHeaders();                
            }
    	});
    }
    return false;
}/**/
function submitAccount(){
   //  alert('_url');
    //var countryvalue;
   // var networkvalue;
    //countryvalue=$("#country").val(); 
    var formData = $("#profile-form3").serializeArray();
    var URL = $("#profile-form3").attr("action");
   // alert(URL);
   $.post(URL, formData,
    function(data, textStatus, jqXHR){
        if(data=='success'){
            window.location.reload();
        }
       //data: Data from server.   
    }).fail(function(jqXHR, textStatus, errorThrown)
    {
 
    });
    return false;
}   
function commonreturnfun(strvalue){
   // alert('_url');
   var countryvalue;
   var networkvalue;
   var mcckvalue;
   var mnckvalue;
   var accountkvalue;
   if(strvalue=='Network'){       
        $('#CountryCode').val('');
        $('#select2-chosen-6').html('select network');
        $('#select2-chosen-7').html('select MCC');
        $('#select2-chosen-8').html('select MNC');
        $('#MCC').html('<option value="" selected> select </option>');
        $('#MNC').html('<option value="" selected> select </option>');
        $('#prefix').val('');
       
       countryvalue=$("#Country").val(); 
   }else if(strvalue=='MCC'){
       countryvalue=$("#Country").val();
       networkvalue=$("#Network").val();
       mcckvalue=$("#MCC").val();
       
        $('#select2-chosen-7').html('select MCC');
        $('#select2-chosen-8').html('select MNC');
        $('#MNC').html('<option value="" selected> select </option>');
        $('#prefix').val('');
       
   }else if(strvalue=='MNC'){
       countryvalue=$("#Country").val();
       networkvalue=$("#Network").val();
       mcckvalue=$("#MCC").val();
       mnckvalue=$("#MNC").val();
       
       
        $('#select2-chosen-8').html('select MNC');
        $('#prefix').val('');
   }else{
       countryvalue=$("#Country").val();
       networkvalue=$("#Network").val();
       mcckvalue=$("#MCC").val();
       mnckvalue=$("#MNC").val();
       accountkvalue=$("#account").val();
   }
  
    _url='/prefixmanagement/prefix/searchnetwork/';
    _url = _url.replace(new RegExp("#", 'g'), "");
    if(_url != 'javascript:void(0);'){
	$.ajaxQueue({
  	    url: _url+"?AJAX=Y",
            type: 'POST',
            data: 'pageName='+_url+"&countryvalue="+countryvalue+"&networkvalue="+networkvalue+"&mcckvalue="+mcckvalue+"&mnckvalue="+mnckvalue+"&accountkvalue="+accountkvalue+"&strvalue="+strvalue,
            beforeSend: function( xhr ) {
            xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
		//$("#"+_div).html('<img src="/js/plugins/lightbox/themes/evolution-dark/images/loading.gif">');
            },
    	    success: function(responsedata,textStatus,jqXHR) {
                //alert(responsedata);
                var result = responsedata.split("###");
                evalScript(result[1]);   
                if(result[0]!=''){
                    $("#CountryCode").val(result[0]);
                }   
                if(strvalue=='oldprefixes'){
                    $("#"+strvalue).val(result[1]);
                }else{    
                    $("#"+strvalue).html(result[1]);
                    //$("table").stickyTableHeaders();
                }
            }
    	});
    }
    return false;
}/**/
function limitText(limitField, limitCount, limitNum) {
	if (limitField.value.length > limitNum) {
		limitField.value = limitField.value.substring(0, limitNum);
	} else {
		limitCount.value = limitNum - limitField.value.length;
	}
}
function accountvalue(val){
    if(val==0){
        $("#mainaccount").show()
    }else{
        $("#mainaccount").hide()
    }
}
function tapopen(tab1,tab2,l1,l2,vl){
    if(vl==1){
        var returnvalue=customfun1();
    }else{
        var returnvalue=customfun2();
    }
    
    if(returnvalue==true){
        $("#"+tab1).removeClass('active');
        $("#"+tab2).addClass('active');
        $("#"+l1).removeClass('active');
        $("#"+l2).addClass('active');  
    }
}
function tapopenback(tab1,tab2,l1,l2){
    $("#"+tab1).removeClass('active');
    $("#"+tab2).addClass('active');
    $("#"+l1).removeClass('active');
    $("#"+l2).addClass('active');    
}

function checkinput(){
    var fullsearchquery=$("#fullsearchquery").val();
    if(fullsearchquery!=''){
        $("#searchbuttonid").removeAttr('disabled');
    }else{
        $("#searchbuttonid").attr('disabled','disabled');
    }
}
function searchvalidation(vstr){
    //alert(vstr);
    if(vstr=='cpManagement'){
        $("#vendorName").removeAttr('disabled');
        $("#accountName").attr('disabled','disabled');
        
        var totalsubaccount=$("#totalsubaccount1").val();
        for(x=1;x<=totalsubaccount;x++){
           $(" option[id='hide_sub"+x+"']").show();
        }
    }else if(vstr=='spManagement'){
        $("#accountName").removeAttr('disabled');
        $("#vendorName").attr('disabled','disabled');
        
        var totalsubaccount=$("#totalsubaccount1").val();
        for(x=1;x<=totalsubaccount;x++){
           $(" option[id='hide_sub"+x+"']").show();
        }
    }else if(vstr=='routingManagement'){
        $("#accountName").removeAttr('disabled');
        $("#vendorName").removeAttr('disabled');
        
        var totalsubaccount=$("#totalsubaccount1").val();
        for(x=1;x<=totalsubaccount;x++){
           $(" option[id='hide_sub"+x+"']").hide();
        }
       // $("#vendorName").attr('disabled','disabled');
       // $("#accountName").attr('disabled','disabled');
    }else if(vstr=='Prefix'){
        //$("#accountName").removeAttr('disabled');
        $("#vendorName").attr('disabled','disabled');
        $("#accountName").attr('disabled','disabled');
        
        var totalsubaccount=$("#totalsubaccount1").val();
        for(x=1;x<=totalsubaccount;x++){
           $(" option[id='hide_sub"+x+"']").show();
        }
    }
}
function selectanyoneoption(){
    accountName=$("#accountName").val();
    vendorName=$("#vendorName").val();
    country=$("#country").val();
    mccVal=$("#mccVal").val();
    networkName=$("#networkName").val();
    mncVal=$("#mncVal").val();
    
    if(accountName!='' || vendorName!='' || country!='' || mccVal!='' || networkName!='' || mncVal!=''){
        $("#secondsearchbutton").removeAttr('disabled');
    }else{
        $("#secondsearchbutton").attr('disabled','disabled');
    }
}
function openreporttypr(vl){
    $('#ERROR_countryedit').html('').hide();
    $('#ERROR_NETWORK').html('').hide();            
    $('#ERROR_MCC').html('').hide();            
    $('#ERROR_MNC').html('').hide();            
    $('#ERROR_vendorsedit').html('').hide();            
    if(vl==1){
        $("#displaycountry").show();
        $("#displaynetwork").hide();
        $("#displaymcc").hide();
        $("#displaymnc").hide();
        $('#select2-chosen-10').html('Select');
        $('#vendorsedit').html('<option value="" selected>Select...</option>');
        $('#select2-chosen-5').html('Select');
        $('#select2-chosen-6').html('Select..');
        $('#select2-chosen-7').html('Select..');
        $('#select2-chosen-8').html('Select..');
        $('#select2-chosen-9').html('ALL');
        $("#countryedit").attr('selectedIndex', '-1').find("option:selected").removeAttr("selected");
        $("#Network").attr('selectedIndex', '-1').find("option:selected").removeAttr("selected");
        $("#MCC").attr('selectedIndex', '-1').find("option:selected").removeAttr("selected");
        $("#MNC").attr('selectedIndex', '-1').find("option:selected").removeAttr("selected");
        $("#accounts").attr('selectedIndex', '-1').find("option:selected").removeAttr("selected");
    }
    if(vl==2){
        $("#displaycountry").hide();
        $("#displaynetwork").show();
        $("#displaymcc").hide();
        $("#displaymnc").hide();
        $('#select2-chosen-10').html('Select');
        $('#vendorsedit').html('<option value="" selected>Select...</option>');
        $('#select2-chosen-5').html('Select');
        $('#select2-chosen-6').html('Select..');
        $('#select2-chosen-7').html('Select..');
        $('#select2-chosen-8').html('Select..');
        $('#select2-chosen-9').html('ALL');
        $("#countryedit").attr('selectedIndex', '-1').find("option:selected").removeAttr("selected");
        $("#Network").attr('selectedIndex', '-1').find("option:selected").removeAttr("selected");
        $("#MCC").attr('selectedIndex', '-1').find("option:selected").removeAttr("selected");
        $("#MNC").attr('selectedIndex', '-1').find("option:selected").removeAttr("selected");
        $("#accounts").attr('selectedIndex', '-1').find("option:selected").removeAttr("selected");
    }
    if(vl==3){
        $("#displaycountry").hide();
        $("#displaynetwork").hide();
        $("#displaymcc").show();
        $("#displaymnc").show();
        $('#select2-chosen-10').html('Select');
        $('#vendorsedit').html('<option value="" selected>Select...</option>');
        $('#select2-chosen-5').html('Select');
        $('#select2-chosen-6').html('Select..');
        $('#select2-chosen-7').html('Select..');
        $('#select2-chosen-8').html('Select..');
        $('#select2-chosen-9').html('ALL');
        $("#countryedit").attr('selectedIndex', '-1').find("option:selected").removeAttr("selected");
        $("#Network").attr('selectedIndex', '-1').find("option:selected").removeAttr("selected");
        $("#MCC").attr('selectedIndex', '-1').find("option:selected").removeAttr("selected");
        $("#MNC").attr('selectedIndex', '-1').find("option:selected").removeAttr("selected");
        $("#accounts").attr('selectedIndex', '-1').find("option:selected").removeAttr("selected");
    }
}
function vendorblank(){
    $('#select2-chosen-10').html('Select..');
    $('#vendorsedit').html('<option value="" selected>Select...</option>');
}
function commonreturnfun21(){
    var loadingimage='<img src="/img/loading-spinner-grey.gif" />';
    $("#select2-chosen-10").html(loadingimage);
    
    var form = $('#editroutingform');
    var checkedValue = form.find('input[name=reporttype]:checked').val();
    var strset='';
    if(checkedValue==1){
        var countryvalue=$("#countryedit").val();
        var networkvalue='';
        var mccval='';
        var mccval='';
        strset='country';
        if (countryvalue == '') {
            $('#ERROR_countryedit').html('<p class="error-block"><span>Select Country</span></p>').show();
            $("#select2-chosen-10").html('Select');
            return false;
        } else {
            $('#ERROR_countryedit').html('').hide();
        }
    }else if(checkedValue==2){
        var countryvalue='';
        var networkvalue=$("#Network").val();
        var mccval='';
        var mccval='';
        strset='network';
        if (networkvalue == '') {
            $('#ERROR_NETWORK').html('<p class="error-block"><span>Select Network</span></p>').show();
            $("#select2-chosen-10").html('Select');
            return false;
        } else {
            $('#ERROR_NETWORK').html('').hide();            
        }
    }else if(checkedValue==3){
        var countryvalue='';
        strset='mccmnc';
        var networkvalue=$("#Network").val();
        var mccval=$("#MCC").val();
        var mncval=$("#MNC").val();
        if (mccval == '') {
            $('#ERROR_MCC').html('<p class="error-block"><span>Select MCC</span></p>').show();
            $("#select2-chosen-10").html('Select');
            return false;
        } else {
            $('#ERROR_MCC').html('').hide();            
        }
        if (mncval == '') {
            $('#ERROR_MNC').html('<p class="error-block"><span>Select MNC</span></p>').show();
            $("#select2-chosen-10").html('Select');
            return false;
        } else {
            $('#ERROR_MNC').html('').hide();            
        }
    }
    _url = '/routingmanagement/routing/searchvendorcountrywise/';
    _url = _url.replace(new RegExp("#", 'g'), "");
    if (_url != 'javascript:void(0);') {
        $.ajaxQueue({
            url: _url + "?AJAX=Y",
            type: 'POST',
            data: 'pageName=' + _url + "&countryvalue=" + countryvalue + "&networkvalue=" + networkvalue + "&mcckvalue=" + mccval + "&mnckvalue=" + mncval+"&strset="+strset,
            beforeSend: function(xhr) {
                xhr.overrideMimeType("text/plain; charset=x-user-defined");
            },
            success: function(responsedata, textStatus, jqXHR) {
                // alert(responsedata);
                //var result = responsedata.split("###");
                evalScript(responsedata);
                //if(result[0]!=''){
                $('#select2-chosen-10').html('Select..');
                $("#vendorsedit").html(responsedata);
                //}   
                //$("#" + strvalue).html(result[1]);
                //$("table").stickyTableHeaders();                
            }
        });
    }   
}
function mnclist(val){
    $('#select2-chosen-10').html('');
    $('#select2-chosen-8').html('select');
    $("#MNC").html('');
    $('#vendorsedit').html('<option value="">Select...</option>');
    if(val!=''){
        _url = '/routingmanagement/routing/getmnclist/';
        _url = _url.replace(new RegExp("#", 'g'), "");
        if (_url != 'javascript:void(0);') {
            $.ajaxQueue({
                url: _url + "?AJAX=Y",
                type: 'POST',
                data: 'pageName=' + _url + "&mcc=" + val,
                beforeSend: function(xhr) {
                    xhr.overrideMimeType("text/plain; charset=x-user-defined");
                },
                success: function(responsedata, textStatus, jqXHR) {
                    // alert(responsedata);
                    //var result = responsedata.split("###");
                    evalScript(responsedata);
                    //if(result[0]!=''){
                    $("#MNC").html(responsedata);
                    //}   
                    //$("#" + strvalue).html(result[1]);
                    //$("table").stickyTableHeaders();                
                }
            });
        }
    }
}
