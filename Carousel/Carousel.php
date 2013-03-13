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
 * @author Nicolas ???
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
    ),
    'ext.wv.worldmapnav' => $wvResourceTemplate + array(
        'scripts' => array(
            'js/jquery.rwdImageMaps.min.js',
            'js/wv.mapresize.js',
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
    $welcome = $parser->recursiveTagParse($args['welcome']);
    $tagline = $parser->recursiveTagParse($args['tagline']);
    $text    = $parser->recursiveTagParse($text);

    $html = '';

    // Build the output
    $html .= "<div class='banner-image'>";
    $html .= "<div class='banner-box banner-box-welcome'>";
    $html .= "<div class='welcome'>$welcome</div>";
    $html .= "<div class='welcome-sub'>$tagline</div>";
    $html .= "<div class='welcome-map-nav'>$text</div>";
    $html .= "</div>"; // map-box
    $html .= '<map name="ImageMap_13_116267614" id="ImageMap_13_116267614">';
    $html .= '<area href="/wiki/Antarctica" shape="poly" coords="1673,463,1560,440,984,429,689,471,613,486,1673,486" alt="Antarctica" title="Antarctica" />';
    $html .= '<area href="/wiki/Oceania" shape="poly" coords="1499,359,1488,310,1517,302,1541,281,1552,272,1575,277,1609,201,1673,213,1673,338,1673,404" alt="Oceania" title="Oceania" />';
    $html .= '<area href="/wiki/Asia" shape="poly" coords="1303,103,1244,141,1284,209,1318,197,1376,243,1441,245,1474,274,1540,283,1550,274,1574,277,1591,245,1612,193,1610,113,1671,58,1669,16,1489,8,1301,0" alt="Asia" title="Asia" />';
    $html .= '<area href="/wiki/Africa" shape="poly" coords="1216,364,1306,325,1350,305,1318,197,1284,209,1245,141,1173,126,1103,142,1084,153,1085,211,1199,357" alt="Africa" title="Africa" />';
    $html .= '<area href="/wiki/Europe" shape="poly" coords="1102,17,1062,46,1105,98,1116,138,1174,126,1218,135,1245,141,1302,103,1301,0" alt="Europe" title="Europe" />';
    $html .= '<area href="/wiki/North_America" shape="poly" coords="588,1,630,94,638,186,734,251,852,262,913,207,945,178,948,112,991,101,1068,39,1093,20,1093,0,586,0" alt="North America" title="North America" />';
    $html .= '<area href="/wiki/South_America" shape="poly" coords="852,262,946,178,1049,260,1033,320,985,399,956,422,914,420,902,303,880,284" alt="South America" title="South America" />';
    $html .= '</map>';
    $html .= '<img alt="Flat earth night banner2.jpg" src="//upload.wikimedia.org/wikipedia/commons/thumb/f/f6/Flat_earth_night_banner2.jpg/1673px-Flat_earth_night_banner2.jpg" width="1673" height="486" srcset="//upload.wikimedia.org/wikipedia/commons/thumb/f/f6/Flat_earth_night_banner2.jpg/2510px-Flat_earth_night_banner2.jpg 1.5x, //upload.wikimedia.org/wikipedia/commons/thumb/f/f6/Flat_earth_night_banner2.jpg/3346px-Flat_earth_night_banner2.jpg 2x" usemap="#ImageMap_13_116267614" />';
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

    // XXX better alignment for these
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

    // XXX incorporate Nicolas' shifting image size trick
    $html .= "<a href='' title='$title'>";
    $html .= $parser->recursiveTagParse("[[File:$image|frameless|1700px|link=$title|$title]]");
    $html .= "</a>";

    $html .= "</div>"; // banner-image

    return array($html, 'noparse' => false);
}

