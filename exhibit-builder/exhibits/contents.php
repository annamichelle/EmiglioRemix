<?php echo head(array('title' => 'Table of Contents &middot; ' . metadata('exhibit', 'title'), 'bodyclass'=>'exhibits toc')); ?>

<div id="primary">
<h1><?php echo metadata('exhibit', 'title') . ": Table of Contents:"; ?></h1>

<?php set_exhibit_pages_for_loop_by_exhibit(); ?>
<?php if (has_loop_records('exhibit_page')): ?>
<nav>
    <ul>
        <?php foreach (loop('exhibit_page') as $exhibitPage): ?>
        <?php echo exhibit_builder_page_summary($exhibitPage); ?>
        <?php endforeach; ?>
    </ul>
</nav>
<?php endif; ?>
</div>

<?php set_exhibit_pages_for_loop_by_exhibit(); ?>
<?php if (has_loop_records('exhibit_page')): ?>
<nav id="exhibit-pages">
    <ul>
        <?php foreach (loop('exhibit_page') as $exhibitPage): ?>
        <?php echo emiglio_exhibit_builder_page_summary($exhibitPage); ?>
        <?php endforeach; ?>
    </ul>
</nav>
<?php endif; ?>

<?php echo foot(); ?>