
<?php echo head(array(
  'title' => nl_getExhibitField('title'),
  'bodyclass' => 'neatline show'
), 'anotherplace_header'); ?>

<!-- Narrative -->
<div id="neatline-narrative" class="narrative">

  <header>

    <!-- Credits. -->
    <span><a href="<?php echo WEB_ROOT; ?>exhibits/show/anotherplace">&larr; Back to Another Place exhibit</a></span> &bull;
    <span>By <a href="http://uwm.edu/libraries/special/">UWM Libraries Special Collections</a></span>,
    <span>with <a href="http://omeka.org">Omeka</a></span>
    <span>and <a href="http://neatline.org">Neatline</a></span>

  </header>

  <!-- Content. -->
  <h1><?php echo nl_getExhibitField('title'); ?></h1>
  <?php echo nl_getExhibitField('narrative'); ?>

</div>

<!-- Exhibit. -->
<div class="exhibit">
  <?php echo nl_getExhibitMarkup(); ?>
</div>

<!-- WMS spinner. -->
<div id="wms-loader">
  <div class="three-quarters"></div>
  <span>Loading imagery...</span>
</div>

<!-- Zoom buttons. -->
<div id="zoom">
  <div class="btn in">+</div>
  <div class="btn out">-</div>
</div>

<?php echo foot(array(),'neatline_footer'); ?>
