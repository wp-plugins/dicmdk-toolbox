<?php
/*
Plugin Name: Dicm.dk - Toolbox
Plugin URI: http://dicm.dk
Description: A toolbox from dicm.dk
Version: 1.2
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


function dicmdktoolbox_settings_init(  ) { 
		
		
	register_setting( 'pluginPage', 'dicmdktoolbox_settings' );

	add_settings_section(
		'dicmdktoolbox_pluginPage_section', 
		__( 'Options page for dicm.dk - toolbox', 'dicm.dk' ), 
		'dicmdktoolbox_settings_section_callback', 
		'pluginPage'
	);

	


}



function dicmdktoolbox_settings_section_callback(  ) { 
}


if(!function_exists('dicmdktoolbox_options_page')) {
function dicmdktoolbox_options_page(  ) { 

	?>
		<h2>Dicm.dk - Toolbox</h2>
		

	<?php

}
}

/*

*/

    ?>