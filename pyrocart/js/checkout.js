$(function(){
    $("#zone_id").chained("#country_id");
});



$(document).ready(function(){
    var shipping_type = $('[name="shipping_type"]').val();
    var total_qty = $('[name="total_qty"]').val();
        
    $("#country_id").change(function () {
      var country_id = "";
        $("#country_id option:selected").each(function () {
            country_id = $(this).val();
        });
        
        $.post('/gbc_2011/products/paypal/shipping_prices',{ "country_id" : country_id, "shipping_type" : shipping_type, "total_qty" : total_qty }, 
        function(data) {
            
            //alert('Data: ' + data.result);
            
            var htmlHolder = "";
            $.each(data.result,function(i,obj){
                                 
                htmlHolder += "<tr><td style='width: 1px;'><input name='shipping_method' value='"+ obj.id +"' id='delivery.standard' type='radio'></td>"+
                "<td><label for='delivery.standard'>"+ obj.name +"</label></td>"+
                "<td style='text-align: right;'><label for='delivery.standard'>"+ obj.price +"</label></td></tr>";
            });
                        
            $("table.form").html(htmlHolder);

        }, "json");
      
    })
    .change();
    
    
    // Smart Wizard     	
    $('#wizard').smartWizard({transitionEffect:'slideleft',onLeaveStep:leaveAStepCallback,onFinish:onFinishCallback,enableFinishButton:true});

    function leaveAStepCallback(obj){
        var step_num= obj.attr('rel');
        return validateSteps(step_num);
    }
      
    function onFinishCallback(){
        if(validateAllSteps()){
            $('form#payment').submit();
        }
    }       
});
	   
function validateAllSteps(){
    var isStepValid = true;

    if(validateStep1() == false){
        isStepValid = false;
        $('#wizard').smartWizard('setError',{stepnum:1,iserror:true});         
    }else{
        $('#wizard').smartWizard('setError',{stepnum:1,iserror:false});
    }

    if(validateStep3() == false){
        isStepValid = false;
        $('#wizard').smartWizard('setError',{stepnum:3,iserror:true});         
    }else{
        $('#wizard').smartWizard('setError',{stepnum:3,iserror:false});
    }

    if(!isStepValid){
        $('#wizard').smartWizard('showMessage','Please correct the errors in the steps and continue');
    }

    return isStepValid;
} 	
		
		
function validateSteps(step){
    var isStepValid = true;
    
    // validate step 1
    if(step == 1){
        if(validateStep1() == false ){
            isStepValid = false; 
            $('#wizard').smartWizard('showMessage','Please correct the errors in step'+step+ ' and click next.');
            $('#wizard').smartWizard('setError',{stepnum:step,iserror:true});         
        }else{
          $('#wizard').smartWizard('setError',{stepnum:step,iserror:false});
        }
    }
      
    // validate step3
    if(step == 3){
        if(validateStep3() == false ){
            isStepValid = false; 
            $('#wizard').smartWizard('showMessage','Please correct the errors in step'+step+ ' and click next.');
            $('#wizard').smartWizard('setError',{stepnum:step,iserror:true});         
        }else{
          $('#wizard').smartWizard('setError',{stepnum:step,iserror:false});
        }
    }
      
    return isStepValid;
}
		
function validateStep1(){
    var isValid = true; 
       
    // Validate Username
    var fn = $('#firstname').val();
    if(!fn && fn.length <= 0){
        isValid = false;
        $('#msg_firstname').html('Please fill firstname').show();
    }else{
        $('#msg_firstname').html('').hide();
    }
       
    // validate lastname
    var ln = $('#lastname').val();
    if(!ln && ln.length <= 0){
        isValid = false;
        $('#msg_lastname').html('Please fill lastname').show();         
    }else{
        $('#msg_lastname').html('').hide();
    }
    
    // validate email
    var em = $('#email').val();
    if(em && em.length > 0){
        if(!isValidEmailAddress(em)){
            isValid = false;
            $('#msg_email').html('Email is invalid').show();           
        }else{
            $('#msg_email').html('').hide();
        }
    }else{
        isValid = false;
        $('#msg_email').html('Please enter email').show();
    }  
       
    // validate telephone
    var tel = $('#telephone').val();
    if(!tel && tel.length <= 0){
        isValid = false;
        $('#msg_telephone').html('Please fill telephone').show();         
    }else{
        $('#msg_telephone').html('').hide();
    }
    
    
    // validate address_1
    var add = $('#address_1').val();
    if(!add && add.length <= 0){
        isValid = false;
        $('#msg_address_1').html('Please fill address').show();         
    }else{
        $('#msg_address_1').html('').hide();
    }
    
    // validate city
    var cit = $('#city').val();
    if(!cit && cit.length <= 0){
        isValid = false;
        $('#msg_city').html('Please fill city').show();         
    }else{
        $('#msg_city').html('').hide();
    }
    
    // validate postcode
    var zip = $('#postcode').val();
    if(!zip && zip.length <= 0){
        isValid = false;
        $('#msg_postcode').html('Please fill postcode').show();         
    }else{
        $('#msg_postcode').html('').hide();
    }
    
    // validate country
    var cnt = $('#country_id').val();
    if(!cnt && cnt.length <= 0){
        isValid = false;
        $('#msg_country_id').html('Please fill country').show();         
    }else{
        $('#msg_country_id').html('').hide();
    }
    
    // validate state
    var zone = $('#zone_id').val();
    if(!zone && zone.length <= 0){
        isValid = false;
        $('#msg_zone_id').html('Please fill state').show();         
    }else{
        $('#msg_zone_id').html('').hide();
    }
    
    return isValid;
}
    
function validateStep3(){
    var isValid = true;    
    
    //validate agree
    var agree = $('#agree').val();
    if(!agree && agree.length <= 0){
        isValid = false;
        $('#msg_agree').html('Select Terms & Conditionss').show();         
    }else{
        $('#msg_agree').html('').hide();
    }
    
    return isValid;
}

// Email Validation
function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
}






