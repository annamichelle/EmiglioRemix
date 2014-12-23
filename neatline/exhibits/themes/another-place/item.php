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
<?php 
	$item = get_current_record('item');
	$exhibit = get_record_by_id('Exhibit', 1);
	$text = 'View item in Omeka';

    $uri = url(array('slug'=>$exhibit->slug, 'item_id'=>$item->id), 'exhibitItem');
    
    $html = '<a href="' . html_escape($uri) . '">' . $text . '</a>';

	echo  $html;
?>
