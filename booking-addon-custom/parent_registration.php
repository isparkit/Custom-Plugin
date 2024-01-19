<?php
// Registeration page -- adds billing to top.
	
	
// Function to check starting char of a string
function startsWith($haystack, $needle){
    return $needle === '' || strpos($haystack, $needle) === 0;
}

// Custom function to display the Billing Address form to registration page
function camper_add_billing_form_to_registration(){
    global $woocommerce;
    
    echo '<p class="billing_address-p"><label for="billing_address" class="billing_address">Billing Address&nbsp;<abbr class="required" title="required">*</abbr></label></p>';
    $checkout = $woocommerce->checkout();
    ?>
    
    <?php foreach ( $checkout->get_checkout_fields( 'billing' ) as $key => $field ) : ?>
        
        <?php if($key!='billing_email'){ 
            woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
        } ?>

    <?php endforeach;
}
add_action('woocommerce_register_form_start','camper_add_billing_form_to_registration');

// Custom function to save Usermeta or Billing Address of registered user
function camper_save_billing_address($user_id){
    global $woocommerce;
    $address = $_POST;
    foreach ($address as $key => $field){
        if(startsWith($key,'billing_')){
            // Condition to add firstname and last name to user meta table
            if($key == 'billing_first_name' || $key == 'billing_last_name'){
                $new_key = explode('billing_',$key);
                update_user_meta( $user_id, $new_key[1], $_POST[$key] );
            }
            update_user_meta( $user_id, $key, $_POST[$key] );
        }
    }

}
add_action('woocommerce_created_customer','camper_save_billing_address');


// Registration page billing address form Validation
function camper_register_page_validation_billing_address( $errors ) {
    $address = $_POST;

    foreach ($address as $key => $field) :
        if(startsWith($key,'billing_')){
            if($key == 'billing_country' && $field == ''){
                add_the_error($errors, $key, 'Country');
            }
            if($key == 'billing_first_name' && $field == ''){
                add_the_error($errors, $key, 'First Name');
            }
            if($key == 'billing_last_name' && $field == ''){
                add_the_error($errors, $key, 'Last Name');
            }
            if($key == 'billing_address_1' && $field == ''){
                add_the_error($errors, $key, 'Address');
            }
            if($key == 'billing_city' && $field == ''){
                add_the_error($errors, $key, 'City');
            }
            if($key == 'billing_state' && $field == ''){
                add_the_error($errors, $key, 'State');
            }
            if($key == 'billing_postcode' && $field == ''){
                add_the_error($errors, $key, 'Post Code');
            }
            if($key == 'billing_phone' && $field == ''){
                add_the_error($errors, $key, 'Phone Number');
            }

        }
    endforeach;

    return $errors;
}
add_filter( 'woocommerce_registration_errors', 'camper_register_page_validation_billing_address', 25 );

function add_the_error( $errors, $key, $field_name ) {
    $message = sprintf( __( '%s is a required field.', 'iconic' ), '<strong>' . $field_name . '</strong>' );
    $errors->add( $key, $message );
}

// my account page register form add billing all forms fields end


add_filter( 'gettext', 'rename_shipping_address_text', 10, 3 );
function rename_shipping_address_text( $translated, $text, $domain ) {

    if ( $text === 'Shipping address' ) {
        $translated = __('Residential address', $domain );
    }

    return $translated;
}

// my account page billing form placehoder text add

add_filter( 'woocommerce_form_field_args', 'camper_register_billing_placehoder_name_change', 10, 3 );
function camper_register_billing_placehoder_name_change( $args, $key, $value ) { 
    if ( $args['id'] == 'billing_first_name' ) {
        $args['placeholder'] = 'First Name';
    } elseif ( $args['id'] == 'billing_last_name' ) {
        $args['placeholder'] = 'Last Name';
    } elseif ( $args['id'] == 'billing_company' ) {
        $args['placeholder'] = 'Company name';
    } elseif ( $args['id'] == 'billing_city' ) {
        $args['placeholder'] = 'Town / City';
    } elseif ( $args['id'] == 'billing_postcode' ) {
        $args['placeholder'] = 'ZIP Code';
    } elseif ( $args['id'] == 'billing_phone' ) {
        $args['placeholder'] = 'Phone Number';  
    } 

    return $args;
};

// prodcut single page change in stock name start

add_filter( 'woocommerce_get_availability', 'custom_get_availability', 1, 2);

function custom_get_availability( $availability, $_product ) {
  global $product;
  $stock = $product->get_total_stock();

  if ( $_product->is_in_stock() ) $availability['availability'] = __($stock . ' left', 'woocommerce');
  // if ( !$_product->is_in_stock() ) $availability['availability'] = __('SOLD OUT', 'woocommerce');

  return $availability;
}

// prodcut single page change in stock name end


