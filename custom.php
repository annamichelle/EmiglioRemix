<?php 

function emiglio_exhibit_builder_page_nav($exhibitPage = null) // creates exhibit builder page nav function, sets default $exhibitPage to null
{
    if (!$exhibitPage) { // if $exhibitPage is false or null, executes the following:
        if (!($exhibitPage = get_current_record('exhibit_page', false))) { // sets $exhibitPage to current record and won't throw an exception if no current record set; if that new value is false...
            return; // quits the function
        }
    }

    $exhibit = $exhibitPage->getExhibit(); // sets variable $exhibit to the page's owning exhibit if $exhibitPage was NOT empty
    $html = '<ul class="exhibit-page-nav navigation" id="secondary-nav">' . "\n";   // sets $html variable to unorderd list with classes exhibit-page-nav and navigation, and id secondary-nav ; \n is a line break
    $pages = $exhibit->getTopPages(); // sets variable $pages to all the pages with no parent
    foreach ($pages as $page) { // for each of these top-level pages...
        $current = (exhibit_builder_is_current_page($page)) ? 'class="current"' : ''; // variable $current is set to "current" class if it's the current page, otherwise the variable is empty
        $html .= "<li $current>" . exhibit_builder_link_to_exhibit($exhibit, $page->title, array(), $page); // added to the $html variable is a list element (with current class, if applicable) with a link to that page
        
        if ($page->countChildPages() > 0) {
            $childPages = $page->getChildPages();
            // The following function shows the child pages of the current top-level section
            if ($current) {
                $html .= '<ul class="child-pages">';
                foreach ($childPages as $childPage) {
                        $current = (exhibit_builder_is_current_page($childPage)) ? 'class="current"' : '';
                        $html .= "<li $current>" . exhibit_builder_link_to_exhibit($exhibit, $childPage->title, array(), $childPage) . '</li>';
                }
                $html .= '</ul>';
            }
            // This is currently pretty terrible but I will figure it out!
            else {
                foreach ($childPages as $childPage) {
                    $current = (exhibit_builder_is_current_page($childPage)) ? 'class="current"' : '';
                    if ($current) {
                        $html .= '<ul class="child-pages">';
                        foreach ($childPages as $childPage) {
                            $html .= "<li $current>" . exhibit_builder_link_to_exhibit($exhibit, $childPage->title, array(), $childPage) . '</li>';
                        }
                    }
                }
            }
        }
    }


        // Original function below (lists all child pages in navigation)
        /* if ($page->countChildPages() > 0) {
            $childPages = $page->getChildPages();
            $html .= '<ul class="child-pages">';
                foreach ($childPages as $childPage) {
                    $current = (exhibit_builder_is_current_page($childPage)) ? 'class="current"' : '';
                    $html .= "<li $current>" . exhibit_builder_link_to_exhibit($exhibit, $childPage->title, array(), $childPage) . '</li>';
                }
            $html .= '</ul>';
        } */
        $html .='</li>'; // adds closing li tag to $html
    }
    $html .= '</ul>' . "\n";
    $html = apply_filters('exhibit_builder_page_nav', $html);
    return $html;
}
?>