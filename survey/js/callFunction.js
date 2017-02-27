/*
Table searchable data - Feature enable / disable

$(document).ready(function() {
    $('#search-records').dataTable( {
        "paging":   false,
        "ordering": false,
        "info":     false
    } );
} );*/

$(document).ready(function() {
    var oTable = $('#search-user').dataTable( {
        "paging":   true,
        "ordering": true,
        "info":     false
    } );
    
   // var oTable = $('#myTable').dataTable();
    // Get the length
    $("#search-user_length").html("Total Records: "+oTable.fnGetData().length);
    //alert(oTable.fnGetData().length);
} );
$(document).ready(function() {
    var oTable = $('#search-accounts').dataTable( {
        "paging":   true,
        "ordering": true,
        "info":     false
    } );
    
   // var oTable = $('#myTable').dataTable();
    // Get the length
    $("#search-accounts_length").html("Total Records: "+oTable.fnGetData().length);
    //alert(oTable.fnGetData().length);
} );
$(document).ready(function() {
    var oTable = $('#listvendortable').dataTable( {
        "paging":   true,
        "ordering": true,
        "info":     false
    } );
    
   // var oTable = $('#myTable').dataTable();
    // Get the length
    $("#listvendortable_length").html("Total Records: "+oTable.fnGetData().length);
    //alert(oTable.fnGetData().length);
} );
$(document).ready(function() {
    var oTable = $('#search-acitivity').dataTable( {
        "paging":   false,
        "ordering": true,
        "info":     false,
        "order": [[ 1, "desc" ]]
    } );
    
   // var oTable = $('#myTable').dataTable();
   
    // Get the length
    $("#search-acitivity_length").html("Total Records: "+oTable.fnGetData().length);
    //alert(oTable.fnGetData().length);
} );
$(document).ready(function() {
    var oTable = $('#search-sno').dataTable( {
        "paging":   false,
        "ordering": true,
        "info":     false,
        "order": [[ 0, "asc" ]]
    } );
    $("#search-sno_length").html("Total Records: "+oTable.fnGetData().length);
} );

$(document).ready(function() {
    var oTable = $('#search-questiongroup').dataTable( {
        "paging":   true,
        "ordering": false,
        "info":     false
        //,"order": [[ 1, "asc" ]]
    } );
    // var oTable = $('#myTable').dataTable();
    // Get the length
    $("#search-questiongroup_length").html("Total Records: "+oTable.fnGetData().length);
    //alert(oTable.fnGetData().length);
} );
$(document).ready(function() {
    var oTable = $('#search-compare').dataTable( {
        "paging":   true,
        "ordering": false,
        "info":     false
    } );
    
   // var oTable = $('#myTable').dataTable();
   
    // Get the length
    $("#search-compare_length").html("Total Records: "+oTable.fnGetData().length);
    //alert(oTable.fnGetData().length);
    
    $('.date-picker').datepicker({
            rtl: Metronic.isRTL(),
            autoclose: true
    });
    $("#period").change(function(){
        $( "select option:selected").each(function(){
            if($(this).attr("value")=="custom"){
                $("#customDate").show();
            }
            if($(this).attr("value")!=="custom"){
                $("#customDate").hide();
                $("#order_date_from").val('');
                $("#order_date_to").val('');
            }
        });
    });
    
} );
/*
Table searchable data - Feature enable / disable
*/
