<?php
/*
downloadPlugin tool is used to add a link on  the "plugins" list page, called "Download"
When the download link is cliked, a zip file is sent to the user containing the plugin files.
*/

// Add link for all plugins
function my_plugin_action_links( $links, $plugin ) {
global $pluginDirUrl;

$exp = explode("/", plugin_basename($plugin), 2);
   $links[] = "<a href=\"".$pluginDirUrl."includes/downloadPlugin/download.php?ABSPATH=".ABSPATH."&plugin=".$exp[0]."\" target=\"_blank\">Download</a>";
   return $links;
}

if ( ! function_exists( 'get_plugins' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

foreach(get_plugins() AS $plugin => $data) {
add_filter( "plugin_action_links_$plugin", my_plugin_action_links, $plugin, 2);
}


?>