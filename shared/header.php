<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<base href="https://<?=$_SERVER['SERVER_NAME']?>" />
	<title>
		<?php echo setTitleFromRoute($route) ?>
	</title>
	<link rel="shortcut icon" href="/images/favicon.png" type="image/png" />
	<?php
	$resources = getAllResources($route);
	if (is_array($resources['css'])) {
		foreach ($resources['css'] as $css) {
			echo $css;
		}
	} else {
		echo $resources['css'];
	}
	if (is_array($resources['js'])) {
		foreach ($resources['js'] as $js) {
			echo $js;
		}
	} else {
		echo $resources['js'];
	}
	?>

</head>

<body>