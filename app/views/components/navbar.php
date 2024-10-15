<?php
if (isset($menuItems)) {
?>
<nav>
    <ul>
        <?php foreach ($menuItems as $name => $url) : ?>
            <li><a href="<?= $url ?>" class="<?= $currentPage == strtolower($name) ? 'active' : '' ?>">
                <?= $name ?>
            </a></li>
        <?php endforeach; ?>
    </ul>
</nav>
<?php
}
?>

<style>
    nav {
        background-color: #333;
        padding: 10px;
    }
    nav ul {
        list-style: none;
        display: flex;
        justify-content: space-around;
    }
    nav ul li {
        margin: 0 15px;
    }
    nav ul li a {
        color: white;
        text-decoration: none;
        font-size: 18px;
    }
    nav ul li a.active {
        font-weight: bold;
        text-decoration: underline;
    }
    nav ul li a:hover {
        text-decoration: underline;
    }
</style>