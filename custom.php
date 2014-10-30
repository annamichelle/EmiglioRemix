<?php 

function emiglio_exhibit_builder_page_nav($exhibitPage = null)
{
    if (!$exhibitPage) {
        if (!($exhibitPage = get_current_record('exhibit_page', false))) {
            return;
        }
    }

    $exhibit = $exhibitPage->getExhibit();
    $html = '<ul class="exhibit-page-nav navigation" id="secondary-nav">' . "\n";
    $pages = $exhibit->getTopPages();
    $htmlChild = '';
    foreach ($pages as $page) {
        $current = (exhibit_builder_is_current_page($page)) ? 'class="current"' : '';
        $html .= "<li $current>" . exhibit_builder_link_to_exhibit($exhibit, $page->title, array(), $page);
        if ($current) {
            if ($page->countChildPages() > 0) {
                $childPages = $page->getChildPages();
                $html .= '<ul class="child-pages">';
                    foreach ($childPages as $childPage) {
                        $html .= "<li>" . exhibit_builder_link_to_exhibit($exhibit, $childPage->title, array(), $childPage) . '</li>';
                    }
                $html .= '</ul>';
            }
        }
        else {
            if ($page->countChildPages() > 0) {
                $childPages = $page->getChildPages();
                $htmlChild = '<ul class="child-pages">';
                $currentChild = '';
                    foreach ($childPages as $childPage) {
                        $current = (exhibit_builder_is_current_page($childPage)) ? 'class="current"' : '';
                        $currentChild .= $current;
                        $htmlChild .= "<li $current>" . exhibit_builder_link_to_exhibit($exhibit, $childPage->title, array(), $childPage) . '</li>';
                    }
                $htmlChild .= '</ul>';
                if (!$currentChild) {
                    $htmlChild = '';
                }
            }
        }
        $html .= $htmlChild . '</li>';
        $htmlChild = '';
    }
    $html .= '</ul>' . "\n";
    $html = apply_filters('exhibit_builder_page_nav', $html);
    return $html;
}

function emiglio_exhibit_builder_page_summary($exhibitPage = null)
{
    if(!$exhibitPage) {
        $exhibitPage = get_current_record('exhibit_page');
    }

    $html = '<li>'
            . '<a href="' . exhibit_builder_exhibit_uri(get_current_record('exhibit'), $exhibitPage) . '">'
            . metadata($exhibitPage, 'title') . '</a>'
            . '</li>';
    return $html;
}

function link_to_related_exhibits($item) {

    $db = get_db();

    $select = "
    SELECT e.* FROM {$db->prefix}exhibits AS e
    INNER JOIN {$db->prefix}exhibit_pages AS ep on ep.exhibit_id = e.id
    INNER JOIN {$db->prefix}exhibit_page_blocks AS epb ON epb.page_id = ep.id
    INNER JOIN {$db->prefix}exhibit_block_attachments AS epba ON epba.block_id = epb.id
    WHERE epba.item_id = ?";

    $exhibits = $db->getTable("Exhibit")->fetchObjects($select,array($item->id));
	
	if(!empty($exhibits)) {
        echo '<h2>Appears in Exhibits</h2>';
		$e = null;
        foreach($exhibits as $exhibit) {
			if ($exhibit->title != $e) {
				echo '<p class="element-text"><a href="/exhibits/show/'.$exhibit->slug.'">'.$exhibit->title.'</a></p>';
			}
			$e = $exhibit->title;
        }
    }
}
?>
