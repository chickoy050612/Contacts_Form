<?php
function displayErrors($errs){
	echo "<div>\n";
	echo "<h3> This form contains the following errors</h3>\n";
	echo "<ul class='err_msgs'>\n";
	foreach ($errs as $err){
		echo "<li>".$err."</li>\n";
	}
	echo "</ul>\n";
	echo "</div>\n";
}
?>
