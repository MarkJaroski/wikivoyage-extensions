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
$wgHooks['BeforePageDisplay'][] = 'wfCarouselBeforePageDisplay';

$wgResourceModules['ext.wv.carousel'] = array(
    'styles' => array('Carousel.css'),
    'localBasePath' => dirname(__FILE__),
    'remoteExtPath' => '/extensions/Carousel',
    'position' => 'top',
);

function wfCarouselSetHook( Parser $parser ) {
	$parser->setHook( 'carousel', 'wfCarouselRender' );
    $parser->setHook( 'banner', 'wfBannerRender' );
	return true;
}

function wfCarouselRender() {
    $html = "<div class='carousel'>";
    $html .= "</div>";
    return($html);
}

function wfBannerRender( $input, array $args, Parser $parser, PPFrame $frame ) {
    $direction = $args['direction'];
    $title = $args['title'];
    $section = $args['section'];
    $image = $args['img'];
    $out  = "<div class='banner-image'>";
    $out .= "<div class='banner-box banner-box-$direction'>";
    // XXX better alignment for these
    $out .= "<span class='name'>$title</span><br />"; # FIXME, should do the line-break with CSS
    $out .= "<span class='type'>$section</span><br />";
    $out .= "<span class='quote'>$input</span><br />";
    $out .= "</div>"; // banner-box
    $out .= "<a href='' title='$title'>";
    // XXX incorporate Nicolas' shifting image size trick
    $out .= $parser->recursiveTagParse("[[File:$image|frameless|1000px|link=$title|$title]]");
    $out .= "</a>";
    $out .= "</div>"; // banner-image
    return array($out, 'noparse' => false);
}

function wfCarouselBeforePageDisplay($out, $skin) {
    // FIXME we should only load this when absolutely necessary
    $out->addModuleStyles('ext.wv.carousel');
    return true;
}

