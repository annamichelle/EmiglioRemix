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
?>