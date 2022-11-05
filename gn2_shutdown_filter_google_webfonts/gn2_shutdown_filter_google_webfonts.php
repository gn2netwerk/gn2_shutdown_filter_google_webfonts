<?php

/*
Plugin Name:  gn2 Shutdown Filter Google Webfonts
Plugin URI:   https://www.gn2.de/
Description:  Removes Google webfont links from html
Version:      0.1.1
Author:       gn2
Author URI:   https://www.gn2.de/
Update URI:   https://raw.githubusercontent.com/gn2netwerk/gn2_shutdown_filter_google_webfonts/master/info.json
*/

// DOMDocument alternative https://github.com/Masterminds/html5-php
// provided by plugin gn2_shutdown_filter
use Masterminds\HTML5;

add_filter('gn2_shutdown', function ($html) {

    $html5 = new HTML5(['disable_html_ns' => true]);
    $doc = $html5->loadHTML($html);
    $xpath = new DOMXpath($doc);
    // search google webfont links
    $delete = [];
    foreach ($xpath->query("//link[contains(@href,'fonts.googleapis')]") as $node) {
        $delete[] = $node;
    }
    foreach ($xpath->query("//link[contains(@href,'fonts.gstatic')]") as $node) {
        $delete[] = $node;
    }
    // search adobe webfont links
    foreach ($xpath->query("//link[contains(@href,'use.typekit.net')]") as $node) {
        $delete[] = $node;
    }
    if (!$delete) {
        return $html;
    }
    foreach ($delete as $node) {
        $node->parentNode->removeChild($node);
    }

    return $html5->saveHTML($doc);
});