<?php

/**
 * @package     omeka
 * @subpackage  neatline
 * @copyright   2014 Rector and Board of Visitors, University of Virginia
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html
 */

?>

<!-- Files. -->
<?php if (metadata('item', 'has files')): ?>
<?php $lightboxGroup = metadata('item', array('Dublin Core', 'Title')); ?>
  <h3><?php echo __('Files'); ?></h3>
  <?php echo files_for_item(
  	array(
  		'imageSize' => 'square_thumbnail', 
  		'imgAttributes' => array(
  			'height' => '100px', 
  			'float' => 'left', 
  			'padding' => '5px'
  		),
  		'linkAttributes' => array('data-lightbox' => $lightboxGroup)
  	)
  ); ?>
<?php endif; ?>

<hr />

<!-- Link. -->
<?php echo link_to(
  get_current_record('item'), 'show', 'View the item in Omeka'
); ?>
