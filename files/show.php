<?php 
queue_js_file('lightbox.min', 'javascripts/vendor');
queue_css_file('lightbox');

$fileTitle = metadata('file', 'display title');

if ($fileTitle != '') {
    $fileTitle = ': &ldquo;' . $fileTitle . '&rdquo; ';
} else {
    $fileTitle = '';
}
$fileTitle = __('File #%s', metadata('file', 'id')) . $fileTitle;

echo head(array('title' => $fileTitle, 'bodyclass'=>'files show primary-secondary'));
echo flash();
?>

<h1 class="file-title"><?php echo $fileTitle; ?></h1>

<div id="primary">
    <?php echo all_element_texts($file); ?>
</div>

<div id="secondary">
    <?php echo file_markup($file, 
        array('imageSize' => 'fullsize', 
            'linkToFile' => true, 
            'imgAttributes' => array('style' => 'width:230px; height:auto;')
            )
        ); 
    ?>
</div>

<?php echo foot();
