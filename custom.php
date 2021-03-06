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
        $html = '<h2>Appears in Exhibits</h2>';
		$e = null;
        $exhibitHtml = '';
        foreach($exhibits as $exhibit) {
			if ($exhibit->title != $e && $exhibit->public) {
				$exhibitHtml .= '<p class="element-text"><a href="../../exhibits/show/'.$exhibit->slug.'">'.$exhibit->title.'</a></p>';
			}
			$e = $exhibit->title;
        }
        // We only want to show that an item appears in exhibits if those exhibits are public
        if(!$exhibitHtml) {
            $html = '';
        }
        else {
            $html .= $exhibitHtml;
        }
        echo $html;
    }
}

function neatline_item_citation($item) {
    if($item){
        $authors = metadata($item, array('Dublin Core', 'Creator'), array('all' => true));
        $title = metadata($item, array('Dublin Core', 'Title'));
        $publisher = metadata($item, array('Dublin Core', 'Publisher'));
        $date = metadata($item, array('Dublin Core', 'Date'));
        $callNumber = metadata($item, array('Dublin Core', 'Identifier'));
        $source = metadata($item, array('Dublin Core', 'Source'));

        $authors = array_filter(array_map('strip_formatting', $authors));
        switch (count($authors)) {
            case 1:
            $author = $authors[0];
            break;
            case 2:
            $author = __('%1$s and %2$s', $authors[0], $authors[1]);
            break;
            default:
            $author = __('%s et al.', $authors[0]);
        }

        $html = '<p>' . $author . '.<br /><em>' . $title . '</em>. ' 
            . $publisher . ', ' . $date . '.<br />Call Number: ' . $callNumber
            . '<br />' . $source;
    }
    else {
        $html = '<p>No item selected</p>';
    }

    return $html;
}

/**
 * Get a gallery of file thumbnails for an item that will display file metadata in Lightbox.
 *
 * @param array $attrs HTML attributes for the components of the gallery, in
 *  sub-arrays for 'wrapper', 'linkWrapper', 'link', and 'image'. Set a wrapper
 *  to null to omit it.
 * @param string $imageType The type of derivative image to display.
 * @param boolean $filesShow Whether to link to the files/show. Defaults to
 *  false, links to the original file.
 * @param Item $item The Item to use, the current item if omitted.
 * @return string
 */
function item_image_gallery_lightbox($attrs = array(), $imageType = 'square_thumbnail', $filesShow = false, $item = null)
{
    if (!$item) {
        $item = get_current_record('item');
    }

    $files = $item->Files;
    if (!$files) {
        return '';
    }

    $defaultAttrs = array(
        'wrapper' => array('id' => 'item-images'),
        'linkWrapper' => array(),
        'link' => array('data-lightbox'=>'lightbox'),
        'image' => array()
    );
    $attrs = array_merge($defaultAttrs, $attrs);

    $html = '';
    if ($attrs['wrapper'] !== null) {
        $html .= '<div ' . tag_attributes($attrs['wrapper']) . '>';
    }
    foreach ($files as $file) {
        $attrs['link']['data-title'] = all_element_texts($file);

        if ($attrs['linkWrapper'] !== null) {
            $html .= '<div ' . tag_attributes($attrs['linkWrapper']) . '>';
        }

        $image = file_image($imageType, $attrs['image'], $file);
        if ($filesShow) {
            $html .= link_to($file, 'show', $image, $attrs['link']);
        } else {
            $linkAttrs = $attrs['link'] + array('href' => $file->getWebPath('original'));
            $html .= '<a ' . tag_attributes($linkAttrs) . '>' . $image . '</a>';
        }

        if ($attrs['linkWrapper'] !== null) {
            $html .= '</div>';
        }
    }
    if ($attrs['wrapper'] !== null) {
        $html .= '</div>';
    }
    return $html;
}

/**
 * Get a list of the current tags filtering the list of exhibits
 * (based on Omeka_View_Helper_ItemSearchFilters)
 * @return string
 *
 */
function exhibit_tag_filters() {
    $request = Zend_Controller_Front::getInstance()->getRequest();
    $requestArray = $request->getParams();
    $db = get_db();
    $displayArray = array();
    foreach ($requestArray as $key => $value) {
        $filter = $key;
        if($value != null) {
            $displayValue = null;
            if($key === 'tags') {
                $displayValue = $value;
            }
            elseif($key === 'tag') {
                $displayValue = $value;
            }
            if($displayValue) {
                $displayArray[$filter] = $displayValue;
            }
        }
    }
    $displayArray = apply_filters('exhibit_tag_filters', $displayArray, array('request_array'=>$requestArray));
    $html = '';
    if(!empty($displayArray)) {
        $html .= '<div id="item-filters">';
        $html .= '<ul>';
        foreach($displayArray as $name => $query) {
            $html .= '<li class="' . $name . '">' . html_escape(ucfirst($name)) . ': ' . html_escape($query) . '</li>';
        }
        $html .= '</ul>';
        $html .= '</div>';
    }
    return $html;
}

?>
