<?php
/**
 * @copyright  Copyright 2012 metaio GmbH. All rights reserved.
 * @author     metaio GmbH
 **/

/**
 *
 * Constants for screen anchors
 *
 */
abstract class ArelAnchor
{
    /**
     * No anchor, i.e. not relative-to-screen
     */
    const ANCHOR_NONE       =	0;
    /**
     * Anchor to the left edge
     */
    const ANCHOR_LEFT       =	1;
    /**
     * Anchor to the right edge
     */
    const ANCHOR_RIGHT      =	2;
    /**
     * Anchor to the bottom edge
     */
    const ANCHOR_BOTTOM     =	4;
    /**
     * Anchor to the top edge
     */
    const ANCHOR_TOP        =	8;
    /**
     * Anchor to the horizontal center
     */
    const ANCHOR_CENTER_H   =	16;
    /**
     * Anchor to the vertical center
     */
    const ANCHOR_CENTER_V   =	32;

    /**
     * Anchor to the Top-Left
     */
    const ANCHOR_TL         =   9;
    /**
     * Anchor to the Top-Center
     */
    const ANCHOR_TC         =   24;
    /**
     * Anchor to the Top-Right
     */
    const ANCHOR_TR         =   10;
    /**
     * Anchor to the Center-Left
     */
    const ANCHOR_CL         =   33;
    /**
     * Anchor to the Center
     */
    const ANCHOR_CC         =   48;
    /**
     * Anchor to the Center-Right
     */
    const ANCHOR_CR         =   34;
    /**
     * Anchor to the Bottom-Left
     */
    const ANCHOR_BL         =   5;
    /**
     * Anchor to the Bottom-Center
     */
    const ANCHOR_BC         =   20;
    /**
     * Anchor to the Bottom-Right
     */
    const ANCHOR_BR         =   6;


    /**
     * Flags used for the attribute flag of the element screenanchor
     *
     */

    /**
     * No flag, all geometric transforms are considered
     */
    const FLAG_NONE =						0;
    /**
     * Ignore rotation of the geometry
     */
    const FLAG_IGNORE_ROTATION =			1;
    /**
     * Ignore animations of the geometry
     */
    const FLAG_IGNORE_ANIMATIONS =			2;
    /**
     * Do not scale geometry according to the screen resolution
     */
    const FLAG_IGNORE_SCREEN_RESOLUTION =	4;
	/**
	 * scale model to be same physical size on all displays
	 */
	const FLAG_MATCH_DISPLAY = 4;
	/**
	 * Autoscale geometries according to the screen
	 */
	const FLAG_AUTOSCALE = 8;
}
