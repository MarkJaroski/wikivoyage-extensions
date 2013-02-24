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

$wgResourceModules['ext.Carousel'] = array(
    'styles' => 'Carousel.css',
    'localBasePath' => __DIR__,
    'remoteExtPath' => 'Carousel',
);

$wgResourceLoaderDebug = true;

function wfCarouselSetHook( Parser $parser ) {
	$parser->setHook( 'carousel', 'wfCarouselRender' );
    $parser->setHook( 'banner', 'wfBannerRender' );
	return true;
}

function wfCarouselRender() {
    global $wgOut;
    $wgOut->addModules('ext.Carousel');
    $html = "<div class='carousel'>";
    $html .= "</div>";
    return($html);
}

function wfBannerRender( $input, array $args, Parser $parser, PPFrame $frame ) {
    global $wgOut;
    $wgOut->addModules('ext.Carousel');
    $direction = $args['direction'];
    $title = $args['title'];
    $html = "<div class='banner-image'>";
    $html .= "<div class='banner-box banner-box-$direction'>";
    $html .= "<span class='name'>$title</span>";
    $html .= "<span class='type'></span>";
    $html .= "<span class='quote'></span>";
    $html .= "<a href='' title='$title'>";
    $html .= "<img src='' alt='$title'/>";
    $html .= "</a>";
    $html .= "</div>";
    $html .= "</div>";
    return($html);
}

