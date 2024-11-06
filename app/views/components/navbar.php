<head>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/component-styles/navbar.style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<?php
if (isset($menuItems)) {
?>
<nav>
    <h1>Minute Mate</h1>
    <form action="<?=ROOT."/".$user?>/search" method="post">
        <input type="text" name="search" placeholder="Search here..." autocomplete="off" class="search-bar" required>
        <button type="submit" ><img src="<?=ROOT?>/assets/images/navbar-images/search.png" class="search-icon"></button>
    </form>
    <div class="nav-icon-list">
    <ul>
        <?php foreach ($menuItems as $name => $url) : ?>
            <li><a href="<?= $url ?>" class="<?= $currentPage == strtolower($name) ? 'active' : '' ?>">
                <img src="<?= ROOT."/assets/images/navbar-images/".$name.".png" ?>" alt="<?= $name ?>" class="nav-icon">
            </a></li>
            
        <?php endforeach; ?>
    </ul>
    </div>
</nav>
<?php
}
?>
