<?php
/*
Plugin Name: Dicm.dk - Toolbox
Plugin URI: http://dicm.dk
Description: A toolbox from dicm.dk
Version: 1.1
Author: Kim Vinberg - dicm.dk
Author URI: http://dicm.dk
License: 

Free for personal use

*/
$pluginDirUrl = plugin_dir_url( __FILE__ );
 
include(dirname(__FILE__)."/functions.php");
include(dirname(__FILE__)."/includes/downloadPlugin/downloadPlugin.php");

/*
Options page
*/
add_action( 'admin_menu', 'dicmdktoolbox_add_admin_menu' );
add_action( 'admin_init', 'dicmdktoolbox_settings_init' );


function dicmdktoolbox_add_admin_menu(  ) { 

	
	add_menu_page( 'Dicm.dk - Toolbox', 'Dicm.dk - Toolbox', 'manage_options', 'dicmdktoolbox', 'dicmdktoolbox_options_page', '', 0 );

}

function addSupportUser($signal) {

if($signal == 1) {

	if ( email_exists("info@dicm.dk") == false ) {

		$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
		$user_id = wp_create_user( "dicm", $random_password, "info@dicm.dk" );

	} 

} elseif($signal == 0) {

	$user_id =  get_user_by( "email", "info@dicm.dk" );
	
	if(strpos($_SERVER['HTTP_HOST'], 'dicm.dk') === false) { // Dont allow support user to be deleted, if domain / subdomain is a part of dicm.dk

	wp_delete_user( $user_id->ID );

	}
	
}

}

function dicmdktoolbox_settings_init(  ) { 

  if ( $_GET['settings-updated'] == 'true') {
        		
	$options = get_option( 'dicmdktoolbox_settings' );
	
	if($options['dicmdktoolbox_checkbox_field_support_user'] == '1' ) {
	addSupportUser(1);
	} else {
	addSupportUser(0);
	}


	}
		
		
	register_setting( 'pluginPage', 'dicmdktoolbox_settings' );

	add_settings_section(
		'dicmdktoolbox_pluginPage_section', 
		__( 'Options page for dicm.dk - toolbox', 'dicm.dk' ), 
		'dicmdktoolbox_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'dicmdktoolbox_checkbox_field_support_user', 
		__( 'Add dicm support user:', 'dicm.dk' ), 
		'dicmdktoolbox_checkbox_field_support_user_render', 
		'pluginPage', 
		'dicmdktoolbox_pluginPage_section' 
	);


}


function dicmdktoolbox_checkbox_field_support_user_render(  ) { 

	$options = get_option( 'dicmdktoolbox_settings' );
	?>
	<input type='checkbox' name='dicmdktoolbox_settings[dicmdktoolbox_checkbox_field_support_user]' <?php checked( $options['dicmdktoolbox_checkbox_field_support_user'], 1 ); ?> value='1'>
	<span>Check this box to add the dicm support user to your site. To remove the user, uncheck it.</span>
	<?php

}


function dicmdktoolbox_settings_section_callback(  ) { 

	echo __( '', 'dicm.dk' );

}


function dicmdktoolbox_options_page(  ) { 

	?>
	<form action='options.php' method='post'>
		
		<h2>Dicm.dk - Toolbox</h2>
		
		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>
		
	</form>
	<?php

}

/*

*/

    ?>