<?php /* ?>

// TODO: Doesn't need the file. Should be removed!

<?php 
global $active_main_nav_item;
$active_main_nav_item = $active_main_nav_item ? $active_main_nav_item : '';

$main_nav_items = array(
    array(
        'id' => 'websites',
        'title' => 'Websites',
        'icon' => '&#xE051;',
        'link' => '#'
    ),
    array(
        'id' => 'domains',
        'title' => 'Domains',
        'icon' => '&#xE85D;',
        'link' => '#'
    ),
    array(
        'id' => 'uptime',
        'title' => 'Uptime',
        'icon' => '&#xE922;',
        'link' => '#'
    ),
    array(
        'id' => 'backups',
        'title' => 'Backups',
        'icon' => '&#xE149;',
        'link' => '#'
    ),
    array(
        'id' => 'seo',
        'title' => 'SEO',
        'icon' => '&#xE880;',
        'link' => '#'
    ),
    array(
        'id' => 'security',
        'title' => 'Security',
        'icon' => '&#xE32A;',
        'link' => '#'
    ),
    array(
        'id' => 'backlink_manager',
        'title' => 'Backlink manager',
        'icon' => '&#xE157;',
        'link' => '#'
    ),
);

?>

<nav class="main-nav f6 fw5">
    <?php
    foreach ($main_nav_items as $k => $v) { ?>
        <a href="<?php echo $v['link']; ?>" title="<?php echo $v['title']; ?>" <?php echo $active_main_nav_item === $v['id'] ? ' class="active"': ''; ?>>
            <i class="material-icons"><?php echo $v['icon']; ?></i>
            <span><?php echo $v['title']; ?></span>
        </a><?php
    }
    ?>
    <a href="#" title="" class="collapse-expand">
        <i class="material-icons collapse">&#xE5C4;</i>
        <i class="material-icons expand">&#xE5C8;</i>
    </a>
</nav>

<span class="toggle-author-nav when-collapsed"><i class="material-icons">&#xE5D2;</i></span>
<span class="toggle-author-nav when-expanded"><i class="material-icons">&#xE5CD;</i></span>

<?php */ ?>
