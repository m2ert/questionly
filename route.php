<?php
$route = @$_GET["route"];
include_once "core/functions.php";

if ($route == "") {
    include_once "pages/login.php";
} else {
	
	
    if (file_exists("pages/$route.php")) {
      include_once "pages/$route.php";  
    } else {
        include_once "pages/error.php";
    }
}


?>