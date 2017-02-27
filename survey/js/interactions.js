function getAjaxData(_url,_div,_title){
   // alert('_url');
	if($("#totalrecord")){
		var totalrecord = "&totalrecord="+$("#totalrecord").val();
	}else{
		var totalrecord = "";
	}
	
    _url = _url.replace(new RegExp("#", 'g'), "");
    if(_url != 'javascript:void(0);'){
	$.ajaxQueue({
  	    url: _url+"?AJAX=Y",
		type: 'POST',
		data: 'pageName='+_url+totalrecord,
		beforeSend: function( xhr ) {
		xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
		$("#"+_div).html('<img src="/js/plugins/lightbox/themes/evolution-dark/images/loading.gif">');
            },
    	    success: function(responsedata,textStatus,jqXHR) {
		evalScript(responsedata);
            	$("#"+_div).html(responsedata);
                	//$("table").stickyTableHeaders();
            }
    	});
    }
    return false;
} 
function getParallelAjaxData(_url,_div,_title){
    //alert('_url');
    _url = _url.replace(new RegExp("#", 'g'), "");
    if(_url != 'javascript:void(0);'){
	$.ajax({			
            url: _url+"?AJAX=Y",
            type: 'POST',
            data: 'pageName='+_url,
            beforeSend: function( xhr ) {
                xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
                $("#"+_div).html('<img src="/js/plugins/lightbox/themes/evolution-dark/images/loading.gif">');
            },
            success: function(responsedata,textStatus,jqXHR) {
                evalScript(responsedata);
                $("#"+_div).html(responsedata);
                    //$("table").stickyTableHeaders();
            }
        });
    }
    return false;
} 
function showData(fname,csum,_div){
	if(fname.indexOf('?') >= 0){
	getAjaxData(fname+'&AJAX=Y&checksum='+csum+'&',_div,'');
    }else{
	getAjaxData(fname+'?AJAX=Y&checksum='+csum+'&',_div,'');
    }
    return false;
}
function showSecurePostData(_url,csum,form){
    //alert(_url);
    _div="htm2display";
    _url = _url.replace(new RegExp("#", 'g'), "");
    if(_url.length >0){
	$.ajaxQueue({
    	    url: _url+"?AJAX=Y",
            data: generatePostQuery(form),
            type: 'POST',
            beforeSend: function( xhr ) {
		xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
		$("#"+_div).html('<img src="/js/plugins/lightbox/themes/evolution-dark/images/loading.gif">');
            },
    	    success: function(responsedata,textStatus,jqXHR) {
	      // Write to #output
                evalScript(responsedata);
            	$("#"+_div).html(responsedata);
            	$("table").stickyTableHeaders();
            }
    	});
    }else{
            alert('Please Select an option First');
	}/**/
}

function showPostData(_url,_div,form){
	//alert(_url.length);
	//_div="htm2display";
	//alert(form);
	_url = _url.replace(new RegExp("#", 'g'), "");
        if(_url.length > 0){
          $.ajaxQueue({			
            url: _url+"?AJAX=Y",
            data: generatePostQuery(form),
            type: 'POST',
            beforeSend: function( xhr ) {
              xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
              $("#"+_div).html('<img src="/js/plugins/lightbox/themes/evolution-dark/images/loading.gif">');
            },
            success: function(responsedata,textStatus,jqXHR) {
               // Write to #output
             // alert(responsedata);
              evalScript(responsedata);
              $("#"+_div).html(responsedata);
              $("table").stickyTableHeaders();
           }
        });
    }else{
          alert('Please Select an option First');
    }/**/
}
function showParallelPostData(_url,_div,form){
    //alert(_url.length);
    //_div="htm2display";
    _url = _url.replace(new RegExp("#", 'g'), "");
    if(_url.length > 0){
        $.ajax({
            url: _url+"?AJAX=Y",
	    data: generatePostQuery(form),
            type: 'POST',
            beforeSend: function( xhr ) {
                xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
		$("#"+_div).html('<img src="/js/plugins/lightbox/themes/evolution-dark/images/loading.gif">');
            },
    	    success: function(responsedata,textStatus,jqXHR) {
                // Write to #output
		//alert(responsedata);
            	evalScript(responsedata);
                    $("#"+_div).html(responsedata);
                    $("table").stickyTableHeaders();
            }
    	});
	//alert("sdkjjsdhdfddddddddddddddddhfd");
    }else{
            alert('Please Select an option First');
    }/**/
}
function showInDivPostData(_url,csum,form,_div){
    //alert(_url);
    //_div="htm2display";
    _url = _url.replace(new RegExp("#", 'g'), "");
    if(_url.length >0){
	$.ajaxQueue({
    	    url: _url+"?AJAX=Y",
            data: generatePostQuery(form),
            type: 'POST',
            beforeSend: function( xhr ) {
                xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
		$("#"+_div).html('<img src="/js/plugins/lightbox/themes/evolution-dark/images/loading.gif">');
            },
    	    success: function(responsedata,textStatus,jqXHR) {
		//alert(responsedata);
	       // Write to #output
                evalScript(responsedata);
            	$("#"+_div).html(responsedata);
                $("table").stickyTableHeaders();
            }
    	});
    }else{
            alert('Please Select an option First');
    }/**/
}

(function($) {
    // jQuery on an empty object, we are going to use this as our Queue
    var ajaxQueue = $({});
    $.ajaxQueue = function( ajaxOpts ) {
        var jqXHR,
        dfd = $.Deferred(),
        promise = dfd.promise();
        // queue our ajax request
        ajaxQueue.queue( doRequest );
        // add the abort method
        promise.abort = function( statusText ) {
        // proxy abort to the jqXHR if it is active
        if ( jqXHR ) {
            return jqXHR.abort( statusText );
        }
        // if there wasn't already a jqXHR we need to remove from queue
        var queue = ajaxQueue.queue(),
            index = $.inArray( doRequest, queue );
        if ( index > -1 ) {
            queue.splice( index, 1 );
        }
        // and then reject the deferred
        dfd.rejectWith( ajaxOpts.context || ajaxOpts,
            [ promise, statusText, "" ] );
        return promise;
    };
    // run the actual query
    function doRequest( next ) {
        jqXHR = $.ajax( ajaxOpts )
            .done( dfd.resolve )
            .fail( dfd.reject )
            .then( next, next );
    }
    return promise;
  };
})(jQuery);


function searchData(checksum,formEle){
    showData(newRequestPage+'?Search='+document.getElementById('sDB').value,"");
}
function searchDataBar(checksum,formEle){
    showData(newRequestPage+'?Search='+document.getElementById('sDB').value,"");
}
function evalScript(scripts){
    try{
	if(scripts != ''){
            var script = "";
            scripts = scripts.replace(/<script[^>]*>([\s\S]*?)<\/script>/gi, 
            function(){ if (scripts !== null) script += arguments[1] + '\n'; return ''; });
 	    // alert(script);
 	    eval(script);
            if(script) (window.execScript) ? window.execScript(script) : window.setTimeout(script, 0);
	}
	return false;
    }
    catch(e){
	alert(e)
    }
}
function generatePostQuery(form){
    doc = form;
    var alertString = "";
    var postString = "";
    var form = {};
    for(i=0; i<doc.elements.length; i++){
        alertString = "";		
        if(doc.elements[i].disabled ==false){
            if(doc.elements[i].type=="button"){
        	continue;
            }else if(doc.elements[i].type=="checkbox"){
                 if(doc.elements[i].checked==true){alertString += doc.elements[i].value;}
                else{ continue;}
            }else if(doc.elements[i].type=="radio"){
                if(doc.elements[i].checked==true){alertString += doc.elements[i].value;}
                else{continue;}
            }else if(doc.elements[i].type=="select-one"){
                alertString += doc.elements[i].value;
            }else if(doc.elements[i].type=="checkbox"){
                if(doc.elements[i].checked==true){alertString += doc.elements[i].value;}
                else{continue;}
            }
            else {alertString += doc.elements[i].value;}
            if(alertString == ""){alertString="NULL";}
            if (form[doc.elements[i].name]) {
        	    form[doc.elements[i].name] = form[doc.elements[i].name] + ',' + encodeURIComponent(alertString);
    	    }
	    else {
            	form[doc.elements[i].name] = (alertString);
            }
            //postString += doc.elements[i].name+":"+encodeURIComponent(alertString)+",";
        }
    }
  //  alert(alertString);
    return form;
}
function loadAjaxElementDiv(){
    //document.getElementById('htm2display').innerHTML = "";
    document.getElementById('loadAjaxElementDiv').style.display = "Block";
    document.getElementById('loadAjaxElementDiv').style.visibility = "visible";
}
function hideAjaxElementDiv(){
    document.getElementById('loadAjaxElementDiv').style.display = "none";
    document.getElementById('loadAjaxElementDiv').style.visibility = "hidden";
}
function validateForm(form){
    //alert(form);
    var errorInForm = false;
    doc = form;
    var form = {};var checkFor="";
    for(i=0; i<doc.elements.length; i++){
	var isErrorInElement = false;
	var checkFor = 'ERROR_REQUIRED';
	//alert(doc.elements[i].name);
	try{
        if(doc.elements[i].type){
            //
        }
		if($(doc.elements[i]).attr(checkFor)!='' && typeof $(doc.elements[i]).attr(checkFor) !== "undefined" ){
			//alert(doc.elements[i].value.length+"======="+'ERROR_'+doc.elements[i].name);
			if(doc.elements[i].value.length == 0 ){
                errorInForm = setErrorMessage('ERROR_'+doc.elements[i].name,$(doc.elements[i]).attr(checkFor),isErrorInElement );
                isErrorInElement =  true;
                continue;
			}else{
                unsetErrorMessage('ERROR_'+doc.elements[i].name,isErrorInElement);
			}
		}else{
			unsetErrorMessage('ERROR_'+doc.elements[i].name,isErrorInElement);
        }
        var checkFor = 'MINLENGTH';		
        if($(doc.elements[i]).attr(checkFor)!='' && typeof $(doc.elements[i]).attr(checkFor) !== "undefined" && doc.elements[i].value.length < $(doc.elements[i]).attr(checkFor) ){
            //alert(doc.elements[i].name+'_ERROR_'+checkFor); die;
            if(doc.elements[i].name+'_ERROR_'+checkFor !== "undefined"){
                if(doc.elements[i].name+'_ERROR_'+checkFor!='PASSWORD_ERROR_MINLENGTH'){
					errorInForm = setErrorMessage('ERROR_'+doc.elements[i].name,eval(doc.elements[i].name+'_ERROR_'+checkFor),isErrorInElement);
                }
			}else{
                errorInForm = setErrorMessage('ERROR_'+doc.elements[i].name,'Min length for '+$(doc.elements[i]).attr('DISPLAY-LABEL')+' is '+ $(doc.elements[i]).attr(checkFor),isErrorInElement);
			}
            isErrorInElement =  true;
			continue;
        }else{
			unsetErrorMessage('ERROR_'+doc.elements[i].name,isErrorInElement);
        }
		
        var checkFor = 'MAXLENGTH';
        if($(doc.elements[i]).attr(checkFor)!='' && typeof $(doc.elements[i]).attr(checkFor) !== "undefined" && doc.elements[i].value.length > $(doc.elements[i]).attr(checkFor)){
         	if(doc.elements[i].name+'_ERROR_'+checkFor !== "undefined"){
                errorInForm = setErrorMessage('ERROR_'+doc.elements[i].name,eval(doc.elements[i].name+'_ERROR_'+checkFor),isErrorInElement);
			}else{
                errorInForm = setErrorMessage('ERROR_'+doc.elements[i].name,'Max length for '+$(doc.elements[i]).attr('DISPLAY-LABEL')+' is '+ $(doc.elements[i]).attr(checkFor),isErrorInElement);
			}
            isErrorInElement =  true;
			continue;
        }else{
			unsetErrorMessage('ERROR_'+doc.elements[i].name,isErrorInElement);
        }
        if($(doc.elements[i]).attr('VALUE_TYPE') == 'NUMBER' || $(doc.elements[i]).attr('VALUE_TYPE') == 'INTEGER'){			
			var checkFor = 'MINVALUE';
			if($(doc.elements[i]).attr(checkFor)!='' && typeof $(doc.elements[i]).attr(checkFor) !== "undefined" && (doc.elements[i].value*1) < $(doc.elements[i]).attr(checkFor)){
                if(doc.elements[i].name+'_ERROR_'+checkFor !== "undefined"){
					errorInForm = setErrorMessage('ERROR_'+doc.elements[i].name,eval(doc.elements[i].name+'_ERROR_'+checkFor),isErrorInElement);
                }else{
					errorInForm = setErrorMessage('ERROR_'+doc.elements[i].name,'Min value for '+$(doc.elements[i]).attr('DISPLAY-LABEL')+' is '+ $(doc.elements[i]).attr(checkFor),isErrorInElement);
                    }
                    isErrorInElement =  true;
                    continue;
		}else{
                    unsetErrorMessage('ERROR_'+doc.elements[i].name,isErrorInElement);
		}
		var checkFor = 'MAXVALUE';
		if($(doc.elements[i]).attr(checkFor)!='' && typeof $(doc.elements[i]).attr(checkFor) !== "undefined" && (doc.elements[i].value*1) > 	$(doc.elements[i]).attr(checkFor)){
                    if(doc.elements[i].name+'_ERROR_'+checkFor !== "undefined"){
			errorInForm = setErrorMessage('ERROR_'+doc.elements[i].name,eval(doc.elements[i].name+'_ERROR_'+checkFor),isErrorInElement);
                    }else{
			errorInForm = setErrorMessage('ERROR_'+doc.elements[i].name,'Max value for '+$(doc.elements[i]).attr('DISPLAY-LABEL')+' is '+ $(doc.elements[i]).attr(checkFor),isErrorInElement);
                    }
                    isErrorInElement =  true;
                    continue;
		}else{
                    unsetErrorMessage('ERROR_'+doc.elements[i].name,isErrorInElement);
		}
	}
   // alert($(doc.elements[i]).attr('VALUE_TYPE'));
	var checkFor = 'VALUE_TYPE';
	if($(doc.elements[i]).attr('VALUE_TYPE') == 'EMAIL' ){
		
		if(isEmail(doc.elements[i].value)){
			unsetErrorMessage('ERROR_'+doc.elements[i].name,isErrorInElement);
		}else{
			errorInForm = inErrorEvent(doc.elements[i].name,isErrorInElement,'Value of '+$(doc.elements[i]).attr('DISPLAY-LABEL')+' is not a valid '+$(doc.elements[i]).attr('VALUE_TYPE'),checkFor);
			isErrorInElement =  true;
			continue;
		}
    }else if($(doc.elements[i]).attr('VALUE_TYPE') == 'INTEGER'){
		if(isInt(doc.elements[i].value)){
                    unsetErrorMessage('ERROR_'+doc.elements[i].name,isErrorInElement);
		}else{
                    errorInForm = inErrorEvent(doc.elements[i].name,isErrorInElement,'Value of '+$(doc.elements[i]).attr('DISPLAY-LABEL')+' is not a valid '+$(doc.elements[i]).attr('VALUE_TYPE'),checkFor);
                    isErrorInElement =  true;
                    continue;
		}
    }else if($(doc.elements[i]).attr('VALUE_TYPE') == 'NUMBER'){
        if(isNum(doc.elements[i].value)){
            unsetErrorMessage('ERROR_'+doc.elements[i].name,isErrorInElement);
		}else{
			errorInForm = inErrorEvent(doc.elements[i].name,isErrorInElement,'Value of '+$(doc.elements[i]).attr('DISPLAY-LABEL')+' is not a valid '+$(doc.elements[i]).attr('VALUE_TYPE'),checkFor);
			isErrorInElement =  true;
			continue;
		}
    }else if($(doc.elements[i]).attr('VALUE_TYPE') == 'ALPHANUM'){
		if(isAlphanum(doc.elements[i].value)){
			unsetErrorMessage('ERROR_'+doc.elements[i].name,isErrorInElement);
		}else{
			errorInForm = inErrorEvent(doc.elements[i].name,isErrorInElement,'Value of '+$(doc.elements[i]).attr('DISPLAY-LABEL')+' is not a valid '+$(doc.elements[i]).attr('VALUE_TYPE'),checkFor);
			isErrorInElement =  true;
			continue;
		}
    }
	else if($(doc.elements[i]).attr('VALUE_TYPE') == 'URL'){
		if(isCompany_Url(doc.elements[i].value)){
			unsetErrorMessage('ERROR_'+doc.elements[i].name,isErrorInElement);
		}else{
			errorInForm = inErrorEvent(doc.elements[i].name,isErrorInElement,'Value of '+$(doc.elements[i]).attr('DISPLAY-LABEL')+' is not a valid '+$(doc.elements[i]).attr('VALUE_TYPE'),checkFor);
			isErrorInElement =  true;
			continue;
		}
    }
	else if($(doc.elements[i]).attr('VALUE_TYPE') == 'PASSWORD'){
		//alert(password(doc.elements[i].value));
		if(password(doc.elements[i].value)){
			unsetErrorMessage('ERROR_'+doc.elements[i].name,isErrorInElement);
		}else{
			errorInForm = inErrorEvent(doc.elements[i].name,isErrorInElement,'Value of '+$(doc.elements[i]).attr('DISPLAY-LABEL')+' is not a valid '+$(doc.elements[i]).attr('VALUE_TYPE'),checkFor);
			isErrorInElement =  true;
			continue;
		}
    }	
	  }catch(err){
            console.log(err.message);
	  }
	}
	if(!errorInForm){
            if( customValidate && typeof customValidate == 'function'){
		errorInForm = customValidate();
            }
	}
	return !errorInForm ;
}
function inErrorEvent(elementName,isErrorInElement,DefaultMessage,checkFor){
    if(elementName+'_ERROR_'+checkFor !== "undefined"){
	return errorInForm = setErrorMessage('ERROR_'+elementName,eval(elementName+'_ERROR_'+checkFor),isErrorInElement);
    }else{
	return errorInForm = setErrorMessage('ERROR_'+elementName,DefaultMessage,isErrorInElement);
    }
}
function setErrorMessage(errorElementId,errorMessage,isErrorInElement){
    errorElementId=errorElementId.replace("[]", ''); 
    //alert(errorElementId);
    if(isErrorInElement){
    	$('#'+errorElementId).html('<p class="error-block"><span>'+errorMessage+'</span></p>').show();
    }else{
	$('#'+errorElementId).html('<p class="error-block"><span>'+errorMessage+'</span></p>').show();
    }
    return true;
}
function unsetErrorMessage(errorElementId,isErrorInElement){
    errorElementId=errorElementId.replace("[]", ''); 
    if(isErrorInElement){
    }
    else{
	$('#'+errorElementId).html('').hide();
    }	
}