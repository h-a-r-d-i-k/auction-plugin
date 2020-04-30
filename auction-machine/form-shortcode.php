<?php
// Add Shortcode
function form_shortcode() {
echo "display form";
}
add_shortcode( 'action_machine_forms', 'form_shortcode' );
?>