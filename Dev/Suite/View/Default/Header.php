<!DOCTYPE html>
<head>
<meta charset="UTF-8" />
<title><?php echo $this->pageTitle; ?></title>
<?php foreach ($this->styles as $style): ?>
	<link rel="stylesheet" type="text/css" href="<?php echo $style; ?>"/>
<?php endforeach; ?>
<?php foreach ($this->scripts as $script): ?>
	<script type='text/javascript' src='<?php echo $script; ?>'></script>
<?php endforeach; ?>
<link rel="shortcut icon" href="Assets/Images/favicon.ico" type="image/x-icon" />
</head>
<body id="top">