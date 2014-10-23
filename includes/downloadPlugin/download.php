<?php
require("../../functions.php");
$pluginName = $_GET['plugin'];
$ABSPATH = $_GET['ABSPATH'];
$source = "".$ABSPATH."wp-content/plugins/$pluginName";
$destination = "".$ABSPATH."wp-content/plugins/dicmdk-toolbox/tmp/$pluginName.zip";

Zip($source, $destination);

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.$pluginName.'.zip"'); //<<< Note the " " surrounding the file name
header('Content-Transfer-Encoding: binary');
header('Connection: Keep-Alive');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . filesize("../../tmp/$pluginName.zip"));
readfile("../../tmp/$pluginName.zip");
unlink("../../tmp/$pluginName.zip");
?>