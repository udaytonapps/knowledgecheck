
<?php
$menu = array(
    'index.php' => '<span class="fa fa-pencil-square-o"> Linked Knowledge Check</span>',
    'ManageKCs.php' => '<span class="fa fa-wrench"></span> Manage'
);
?>

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php">Knowledge Check</a>
        </div>
        <ul class="nav navbar-nav">
            <?php foreach( $menu as $menupage => $menulabel ) : ?>
                <li<?php if($menupage == basename($_SERVER['PHP_SELF'])){echo ' class="active"';} ?>>
                    <a href="<?php echo $menupage ; ?>">
                        <?php echo $menulabel ; ?>
                    </a>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
</nav>