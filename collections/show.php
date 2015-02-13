<?php
$collectionId = $collection->id;
echo head(array('title'=>metadata('collection', array('Dublin Core', 'Title')), 'bodyclass' => 'collections show')); ?>

<h1><?php echo metadata('collection', array('Dublin Core', 'Title')); ?></h1>

<div id="primary" class="show">
    <?php 
        $collectionDescription = metadata('collection', array('Dublin Core', 'Description'));
        $collectionSource = metadata('collection', array('Dublin Core', 'Source'));
        $collectionRights = metadata('collection', array('Dublin Core', 'Rights'));
        $collectionFormat = metadata('collection', array('Dublin Core', 'Format'));
        $collectionCoverage = metadata('collection', array('Dublin Core', 'Coverage'));
        $collectionCreator = metadata('collection', array('Dublin Core', 'Creator'));
        $collectionLanguage = metadata('collection', array('Dublin Core', 'Language'));
        $collectionIdentifier = metadata('collection', array('Dublin Core', 'Identifier'));
        $collectionRelation = metadata('collection', array('Dublin Core', 'Relation'));
    ?>
    <?php if ($collectionDescription): ?>
    <div id="description" class="element">
        <h2><?php echo __('About the Collection'); ?></h2>
        <div class="element-text"><?php echo $collectionDescription; ?></div>
    </div><!-- end description -->
    <?php endif; ?>
    <?php if ($collectionCreator): ?>
    <div id="creator" class="element">
        <h3><?php echo __('Creator'); ?></h3>
        <div class="element-text"><?php echo $collectionCreator; ?></div>
    </div><!-- end creator -->
    <?php endif; ?>
    <?php if ($collectionCoverage): ?>
    <div id="coverage" class="element">
        <h3><?php echo __('Coverage'); ?></h3>
        <div class="element-text"><?php echo $collectionCoverage; ?></div>
    </div><!-- end coverage -->
    <?php endif; ?>
    <?php if ($collectionFormat): ?>
    <div id="format" class="element">
        <h3><?php echo __('Format'); ?></h3>
        <div class="element-text"><?php echo $collectionFormat; ?></div>
    </div><!-- end format -->
    <?php endif; ?>
    <?php if ($collectionLanguage): ?>
    <div id="language" class="element">
        <h3><?php echo __('Language'); ?></h3>
        <div class="element-text"><?php echo $collectionLanguage; ?></div>
    </div><!-- end language -->
    <?php endif; ?>
    <?php if ($collectionRights): ?>
    <div id="rights" class="element">
        <h3><?php echo __('Rights'); ?></h3>
        <div class="element-text"><?php echo $collectionRights; ?></div>
    </div><!-- end Rights -->
    <?php endif; ?>
    <?php if ($collectionSource): ?>
    <div id="source" class="element">
        <h3><?php echo __('Source'); ?></h3>
        <div class="element-text"><?php echo $collectionSource; ?></div>
    </div><!-- end source -->
    <?php endif; ?>
    <?php if ($collectionIdentifier): ?>
    <div id="identifier" class="element">
        <h3><?php echo __('Identifier'); ?></h3>
        <div class="element-text"><?php echo $collectionIdentifier; ?></div>
    </div><!-- end identifier -->
    <?php endif; ?>
    <?php if ($collectionRelation): ?>
    <div id="relation" class="element">
        <h3><?php echo __('Relation'); ?></h3>
        <div class="element-text"><?php echo $collectionRelation; ?></div>
    </div><!-- end relation -->
    <?php endif; ?>
    <?php if (metadata('collection', array('Dublin Core', 'Contributor'))): ?>
    <div id="collectors" class="element">
        <h2><?php echo __('Contributor(s)'); ?></h2>
        <div class="element-text">
            <ul>
                <li><?php echo metadata('collection', array('Dublin Core', 'Contributor'), array('delimiter'=>'</li><li>')); ?></li>
            </ul>
        </div>
    </div><!-- end collectors -->
    <?php endif; ?>
    <?php echo fire_plugin_hook('public_collections_show', array('view' => $this, 'collection' => $collection)); ?>
</div>
<div id="secondary">
    <div id="collection-items">
        <?php $collectionItems = get_records('item', array('collection' => $collectionId), 3); ?>
        <?php foreach (loop('items', $collectionItems) as $item): ?>

            <h3><?php echo link_to_item(metadata($item, array('Dublin Core', 'Title')), array('class'=>'permalink'), 'show', $item); ?></h3>

            <?php if (metadata($item, 'has thumbnail')): ?>
            <div class="item-img">
                <?php echo link_to_item(item_image('square_thumbnail', array('alt'=>metadata($item,array('Dublin Core', 'Title'))))); ?>
            </div>
            <?php endif; ?>

            <?php if ($text = metadata($item, array('Item Type Metadata', 'Text'), array('snippet'=>250))): ?>
                <div class="item-description">
                <p><?php echo $text; ?></p>
                </div>
            <?php elseif ($description = metadata($item, array('Dublin Core', 'Description'), array('snippet'=>250))): ?>
                <div class="item-description">
                <?php echo $description; ?>
                </div>
            <?php endif; ?>
    <?php endforeach; ?>
    </div><!-- end collection-items -->
    <?php if (count($collectionItems) > 0): ?>
    <p class="view-items-link"><?php echo link_to_items_browse(__('View all items in %s', metadata('collection', array('Dublin Core', 'Title'))), array('collection' => $collectionId)); ?></p>
    <?php endif; ?>
</div>
<?php echo foot(); ?>
