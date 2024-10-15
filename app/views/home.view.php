<?php require "components/navbar.php"?>
<?php
$Root=ROOT;
// variables should pass to navbar component
$currentPage = "home";
$menuItems = [
    "Home" => "$Root/home",
    "About" => "$Root/home/about",
    "Services" => "$Root/home/services",
    "Contact" => "$Root/home/contact"
];

// Require the navbar and pass data
require 'components/navbar.php';
?>

<!DOCTYPE html>
<head>
    
    <title>MinuteMate</title>
    <link rel="favicon" href="<?= ROOT ?>/img.png" type="image/png">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/style.css">

</head>
<body>
<?php echo "$currentPath"; ?>
<h1>Hello! I'm Home page</h1>
<img src="<?= ROOT ?>/assets/images/img.png">
</body>