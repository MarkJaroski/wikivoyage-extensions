<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * @file
 * @ingroup Extensions
 * @version 0.1
 * @author Mark Jaroski <mark@geekhive.net>
 * @copyright Copyright © 2013 Mark Jaroski
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

if( !defined( 'MEDIAWIKI' ) ){
	die( "This is not a valid entry point.\n" );
}

$wgExtensionCredits['parserhook'][] = array(
	'path'           => __FILE__,
	'name'           => 'Carousel',
    'author'         => '', # XXX
	'url'            => '', # XXX
);

$wgHooks['ParserFirstCallInit'][] = "wfCarouselSetHook";

$wvResourceTemplate = array(
    'localBasePath' => dirname(__FILE__),
    'remoteExtPath' => '../../mediawiki/extensions/Carousel',
);

$wgResourceModules += array(
    'ext.wv.banner' => $wvResourceTemplate + array(
        'styles' => array('Carousel.css'),
        'scripts' => array(
            'js/wv.textresize.js',
        ),
    ),
    'ext.wv.worldmapnav' => $wvResourceTemplate + array(
        'scripts' => array(
            'js/jquery.rwdImageMaps.min.js',
            'js/wv.mapresize.js',
            'js/wv.textresize.js',
        ),
        'dependancies' => array('jquery.client'),
    )

);

$wvResourceLoad = array();

function wfCarouselSetHook( Parser $parser ) {
	$parser->setHook( 'carousel', 'wfCarouselRender' );
    $parser->setHook( 'banner', 'wfBannerRender' );
    $parser->setHook( 'mapbanner', 'wfMapBannerRender' );
	return true;
}

function wfCarouselRender() {
    $html = "<div class='carousel'>";
    $html .= "</div>";
    return($html);
}

function wfMapBannerRender( $text, array $args, Parser $parser, PPFrame $frame ) {

    $parts = preg_split('/\nFile:/', $text);
    $text = $parts[0];
    $img  = "File:" . $parts[1];

    $html = '';

    // Build the output
    $html .= "<div class='banner-image'>";
    $html .= "<div class='banner-box banner-box-welcome'>";
    $html .= $parser->recursiveTagParse($text);
    $html .= "</div>"; // map-box
    $html .= ImageMap::render($img, $args, $parser);
    $html .= "</div>"; // banner-image

    // Load required modules
    $html .= "<script>mw.loader.load('ext.wv.worldmapnav');</script>";

    return $html;
}

function wfBannerRender( $input, array $args, Parser $parser, PPFrame $frame ) {

    // Gather the data
    $direction = $args['direction'];
    $title = $args['title'];
    $section = $args['section'];
    $section_link = $args['section-link'];
    $image = $args['img'];

    // Load the CSS
    $html .= "<script>mw.loader.load('ext.wv.banner');</script>";

    // Build the output
    $html  .= "<div class='banner-image'>";

    $html .= "<div class='banner-box banner-box-$direction'>";

    $html .= "<div class='name'>"; 
    $html .= $parser->recursiveTagParse("[[$title]]");
    $html .= "</div>";

    $html .= "<div class='type'>";
    if ($section_link) {
        $html .= $parser->recursiveTagParse("[[$section_link|$section]]");
    } else {
        $html .= $section;
    }
    $html .= "</div>";

    $html .= "<div class='quote'>";
    $html .= $parser->recursiveTagParse("[[$title|$input]]");
    $html .= "</div>";

    $html .= "</div>"; // banner-box

    $html .= "<a href='' title='$title'>";
    $html .= $parser->recursiveTagParse("[[File:$image|frameless|1700px|link=$title|$title]]");
    $html .= "</a>";

    $html .= "</div>"; // banner-image

    return array($html, 'noparse' => false);
}

