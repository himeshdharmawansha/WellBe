<?php
ob_start();

function show($stuff)
{
	echo "<pre>";
	print_r($stuff);
	echo "</pre>";
}

function esc($str)
{
	return htmlspecialchars($str);
}

function redirect($path)
{
	header("Location: " . ROOT . "/" . $path . "/");
	die;
}

/** return URL variables **/
function URL($key): mixed
{
	$URL = $_GET['url'] ?? 'home';
	$URL = explode("/", trim($URL, "/"));

	switch ($key) {
		case 'page':
		case 0:
			return $URL[0] ?? null;
		case 'section':
		case 'slug':
		case 1:
			return $URL[1] ?? null;
		case 'action':
		case 2:
			return $URL[2] ?? null;
		case 'id':
		case 3:
			return $URL[3] ?? null;
		default:
			return null;
	}
}

function old_value(string $key, $default = "", string $mode = 'post'): mixed
{
	$POST = ($mode === 'post') ? $_POST : $_GET;
	return isset($POST[$key]) ? $POST[$key] : $default;
}
