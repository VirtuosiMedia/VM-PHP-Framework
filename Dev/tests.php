<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Builds a unit testing suite by scanning a given directory
*/

ini_set('display_errors', 1); 
error_reporting(E_ALL);
require_once('Assets/Autoload.php');

$testSuite = new Tests_Test_Render_Suite();

echo '<?xml version="1.0" encoding="UTF-8" ?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Test Suite</title>
<link rel="stylesheet" type="text/css" href="Assets/Css/default.css"/>
<link rel="stylesheet" type="text/css" href="Assets/Css/tests.css"/>
<link rel="shortcut icon" href="Assets/Images/favicon.ico" type="image/x-icon" />
<script type='text/javascript' src='Assets/JavaScript/mootools.js'></script>
<script type='text/javascript' src='Assets/JavaScript/mootools-more.js'></script>
<script type='text/javascript' src='Assets/JavaScript/SimpleTabs.js'></script>
<script type='text/javascript' src='Assets/JavaScript/MultiSelect.js'></script>
<script type='text/javascript' src='Assets/JavaScript/StyleSelect.js'></script>
<script type='text/javascript' src='Assets/JavaScript/MilkChart.js'></script>
<script type='text/javascript' src='Assets/JavaScript/InputMask.js'></script>
<script type='text/javascript' src='Assets/JavaScript/CheckboxReplace.js'></script>
<script type='text/javascript' src='Assets/JavaScript/tests.js'></script>

</head>
<body id="top">
<div id="navContainer">
	<ul id="topNav">
		<li><a href="index.html" id="logo"></a></li>
		<li><a href="about.php">About</a></li>
		<li><a href="Docs/index.html">Docs</a></li>
		<li><a href="install.php">Install</a></li>
		<li><a href="security.php">Security</a></li>
		<li><a href="tests.php" class="active">Tests</a></li>
		<li><a href="tools.php">Tools</a></li>
	</ul>
</div>
<noscript>
	<div class="notification">JavaScript must be enabled to use all features of the test suite.</div>
</noscript>

<?php echo $testSuite->render(); ?>

</body>
</html>
