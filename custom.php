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

function return_to_exhibit(){
    $back = htmlspecialchars($_SERVER['HTTP_REFERER']);
    $html = '<a href="' . $back . '">Back to the Exhibit</a>';
    return $html;
}

function emiglio_exhibit_builder_summary_accordion($exhibitPage = null)
{
    if (!$exhibitPage) {
        $exhibitPage = get_current_record('exhibit_page');
    }

    $html = '<h3>'
          . '<a href="' . exhibit_builder_exhibit_uri(get_current_record('exhibit'), $exhibitPage) . '">'
          . metadata($exhibitPage, 'title') .'</a>';

    $children = $exhibitPage->getChildPages();
    if ($children) {
        $html .= '<div><ul>';
        foreach ($children as $child) {
            $html .= exhibit_builder_page_summary($child);
            release_object($child);
        }
        $html .= '</ul></div>';
    }
    $html .= '</h3>';
    return $html;
}
?>
