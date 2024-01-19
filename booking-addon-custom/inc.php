<?php
// custom css and js for front
function student_widget_enqueue_script() {
wp_enqueue_script( 'product-booking', plugin_dir_url( __FILE__ ) . 'js/booking-addon.js','','',true );
wp_enqueue_style( 'product-booking', plugin_dir_url( __FILE__ ) . 'css/booking-addon2.css' );
}
add_action('wp_enqueue_scripts', 'student_widget_enqueue_script');

// custom css and js for admin
add_action('admin_enqueue_scripts', 'camper_css_and_js'); 
function camper_css_and_js($hook) {
    wp_enqueue_style('camper_css', plugins_url('css/camper-admin1.css',__FILE__ ));
}
