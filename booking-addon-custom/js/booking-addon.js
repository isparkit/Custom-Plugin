jQuery(document).ready(function() {


jQuery("#wc-appointments-appointment-form").insertBefore(".wc-appointment-product-id");


    // order thankyou page date title name change

    // 

    // my account page register form postion change start

    jQuery("table.woocommerce-grouped-product-list.group_table").insertAfter(".cart.grouped_form .table-wrapper");
    jQuery(".acf-fields.acf-form-fields.-top").prependTo(".woocommerce-form.woocommerce-form-register.register");
    // jQuery(".woocommerce-form.woocommerce-form-register.register .woocommerce-form-row--wide.form-row").insertAfter("#username-acf-rg");
    jQuery(".woocommerce-form-register .woocommerce-form-row.woocommerce-form-row--wide.form-row.form-row-wide").prependTo(".woocommerce-form-register");
    jQuery(".woocommerce-form-register .woocommerce-form-row.woocommerce-form-row--wide.form-row.form-row-wide:nth-child(2)").insertAfter("#reg_confirm_password_field");
    jQuery(".woocommerce-EditAccountForm label[for*='account_display_name']").text("Username");
    jQuery("#referral-source-rg").insertAfter("#billing_phone_field");
    setTimeout(function () {
        jQuery("#is-your-billing-rg").insertAfter("#billing_phone_field");
        jQuery("#residential-address-rg").insertAfter("#is-your-billing-rg");

    }, 1000);
    

    // my account page register form postion change end

    // my account page register form placehoder add start

    jQuery('#reg_username').attr( "placeholder", "Enter Username" );
    jQuery('#reg_email').attr( "placeholder", "email@address.com" );
    jQuery('#reg_password').attr( "placeholder", "Enter Password" );
    jQuery('#reg_confirm_password').attr( "placeholder", "Enter Password" );
    jQuery('.woocommerce-form-login #username').attr( "placeholder", "Enter Username" );
    jQuery('.woocommerce-form-login #password').attr( "placeholder", "Enter Password" );

    // my account page register form placehoder add end

    // single product page add some text start

    // jQuery(".single-product .quantity").append("<p>Campers</p>");
    jQuery(".tm-custom-prices").prepend("<h2>Camper Registration</h2>");

    // single product page add some text end
    
    // single prodcut page quantity position change
    
    jQuery(".tm-custom-prices h2").append(jQuery(".quantity"));
    
    // my-account page user login then hide register form
    
    if(jQuery('.woocommerce-MyAccount-navigation').length > 0){
        jQuery('#register-my-account-rm').hide();
    }
    
    jQuery('#students').change(function() {
        if(this.checked) {
            // var returnVal = confirm("Are you sure?");
            // jQuery(this).prop("checked", returnVal);
            jQuery('#quantity_61d6c70bbd6fa').val(2);        
        }
        else{
          jQuery('#quantity_61d6c70bbd6fa').val(3);   
      }
  });

});
// form validation add camper my account
function myValidation() {
  var allergiescheck = document.getElementById("allergiescheck").required;
  document.getElementById("allergiescheck").innerHTML = allergiescheck;

  var medidevicecheck = document.getElementById("medidevicecheck").required;
  document.getElementById("medidevicecheck").innerHTML = medidevicecheck;
}

// hide show form element 

//emergencycheck

jQuery('#emergencycheckdiv').hide();
jQuery('#emergencycheckval').val('no');
jQuery('#emergencycheck').change(function(){
  if(jQuery(this).prop("checked")) {
    jQuery('#emergencycheckdiv').hide();
    jQuery('#emergencycheckval').val('no');
} else {
    jQuery('#emergencycheckdiv').show();
    jQuery('#emergencycheckval').val('yes');
}
});

// allergiescheckdiv
jQuery('#allergiescheckdiv').hide();   
jQuery('input[type=radio][name=allergiescheck]').change(function() {
    if (this.value == 'yes') {
        jQuery('#allergiescheckdiv').show();
        jQuery("#allergies").prop('required',true);

    }
    else if (this.value == 'no') {
        jQuery('#allergiescheckdiv').hide();
        jQuery("#allergies").prop('required',false);
    }
});

// medidevicecheck
jQuery('#medidevicecheckdiv').hide();   
jQuery('input[type=radio][name=medidevicecheck]').change(function() {
    if (this.value == 'yes') {
        jQuery('#medidevicecheckdiv').show();
        jQuery("#medidevice").prop('required',true);
    }
    else if (this.value == 'no') {
        jQuery('#medidevicecheckdiv').hide();
        jQuery("#medidevice").prop('required',false);
    }
});


// additional emergency
jQuery('#addicheckdiv').hide();
jQuery('input[type=radio][name=addi]').change(function() {
    if (this.value == 'yes') {
        jQuery('#addicheckdiv').show();
        jQuery("#medidevice").prop('required',true);
    }
    else if (this.value == 'no') {
        jQuery('#addicheckdiv').hide();
        jQuery("#medidevice").prop('required',false);
    }
});


// jQuery('input[type=radio][name=medidevicecheck]').change(function() {
//   alert("yes");
//   jQuery('#medidevicecheckdiv').show();
//   jQuery('#allergiescheckdiv').show();
// });

 //jQuery('input[name="medidevicecheck"]').change(function(){
  jQuery(document).ready(function(){
    // hide button





    if(jQuery('#medidevicecheck').prop('checked')){
        jQuery('#medidevicecheckdiv').show();
    }else{
        jQuery('#medidevicecheckdiv').hide();
    }
    if(jQuery('#allergiescheck').prop('checked')){
        jQuery('#allergiescheckdiv').show();
    }else{
        jQuery('#allergiescheckdiv').hide();
    }
    if(jQuery('#emergencycheck').prop('checked')){
        jQuery('#emergencycheckdiv').hide();
    }else{
        jQuery('#emergencycheckdiv').show();
    }
});

  // update quantity of product
  jQuery( function(jQuery) {
    var theTotal = 0;
    jQuery('#q0').change(function() {
        var ischecked= jQuery(this).is(':checked');
        if(ischecked) {
            theTotal = Number(theTotal) + 1; 
            jQuery('#gqty').val(theTotal);
            jQuery('[name="camprtxt"]').val(theTotal);
        }
        else {
            theTotal = Number(theTotal) - 1; 
            jQuery('#gqty').val(theTotal);
            jQuery('[name="camprtxt"]').val(theTotal);
        }
    }); 
    jQuery('#q1').change(function() {
        var ischecked= jQuery(this).is(':checked');
        if(ischecked) {
            theTotal = Number(theTotal) + 1; 
            jQuery('#gqty').val(theTotal);
            jQuery('[name="camprtxt"]').val(theTotal);
        }
        else {
            theTotal = Number(theTotal) - 1; 
            jQuery('#gqty').val(theTotal);
            jQuery('[name="camprtxt"]').val(theTotal);
        }
    }); 
    jQuery('#q2').change(function() {
        var ischecked= jQuery(this).is(':checked');
        if(ischecked) {
            theTotal = Number(theTotal) + 1; 
            jQuery('#gqty').val(theTotal);
            jQuery('[name="camprtxt"]').val(theTotal);
        }
        else {
            theTotal = Number(theTotal) - 1; 
            jQuery('#gqty').val(theTotal);
            jQuery('[name="camprtxt"]').val(theTotal);
        }
    }); 
    jQuery('#q3').change(function() {
        var ischecked= jQuery(this).is(':checked');
        if(ischecked) {
            theTotal = Number(theTotal) + 1; 
            jQuery('#gqty').val(theTotal);
            jQuery('[name="camprtxt"]').val(theTotal);
        }
        else {
            theTotal = Number(theTotal) - 1; 
            jQuery('#gqty').val(theTotal);
            jQuery('[name="camprtxt"]').val(theTotal);
        }
    }); 
    jQuery('#q4').change(function() {
        var ischecked= jQuery(this).is(':checked');
        if(ischecked) {
            theTotal = Number(theTotal) + 1; 
            jQuery('#gqty').val(theTotal);
            jQuery('[name="camprtxt"]').val(theTotal);
        }
        else {
            theTotal = Number(theTotal) - 1; 
            jQuery('#gqty').val(theTotal);
            jQuery('[name="camprtxt"]').val(theTotal);
        }
    }); 
    jQuery('#q5').change(function() {
        var ischecked= jQuery(this).is(':checked');
        if(ischecked) {
            theTotal = Number(theTotal) + 1; 
            jQuery('#gqty').val(theTotal);
            jQuery('[name="camprtxt"]').val(theTotal);
        }
        else {
            theTotal = Number(theTotal) - 1; 
            jQuery('#gqty').val(theTotal);
            jQuery('[name="camprtxt"]').val(theTotal);
        }
    }); 
    jQuery('#q6').change(function() {
        var ischecked= jQuery(this).is(':checked');
        if(ischecked) {
            theTotal = Number(theTotal) + 1; 
            jQuery('#gqty').val(theTotal);
            jQuery('[name="camprtxt"]').val(theTotal);
        }
        else {
            theTotal = Number(theTotal) - 1; 
            jQuery('#gqty').val(theTotal);
            jQuery('[name="camprtxt"]').val(theTotal);
        }
    }); 
    jQuery('#q7').change(function() {
        var ischecked= jQuery(this).is(':checked');
        if(ischecked) {
            theTotal = Number(theTotal) + 1; 
            jQuery('#gqty').val(theTotal);
            jQuery('[name="camprtxt"]').val(theTotal);
        }
        else {
            theTotal = Number(theTotal) - 1; 
            jQuery('#gqty').val(theTotal);
            jQuery('[name="camprtxt"]').val(theTotal);
        }
    }); 
    jQuery('#q8').change(function() {
        var ischecked= jQuery(this).is(':checked');
        if(ischecked) {
            theTotal = Number(theTotal) + 1; 
            jQuery('#gqty').val(theTotal);
            jQuery('[name="camprtxt"]').val(theTotal);
        }
        else {
            theTotal = Number(theTotal) - 1; 
            jQuery('#gqty').val(theTotal);
            jQuery('[name="camprtxt"]').val(theTotal);
        }
    }); 
    jQuery('#q9').change(function() {
        var ischecked= jQuery(this).is(':checked');
        if(ischecked) {
            theTotal = Number(theTotal) + 1; 
            jQuery('#gqty').val(theTotal);
            jQuery('[name="camprtxt"]').val(theTotal);
        }
        else {
            theTotal = Number(theTotal) - 1; 
            jQuery('#gqty').val(theTotal);
            jQuery('[name="camprtxt"]').val(theTotal);
        }
    }); 

});