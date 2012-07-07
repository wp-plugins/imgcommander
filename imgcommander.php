<?php
/**
 * @package Imgcommander
 */
/*
Plugin Name: IMG.Commander
Plugin URI: http://imgcommander.com/
Description: The easiest way to get eye-catching images for your blog post. To get started: 1) Click the "Activate" link to the left of this description, 2) <a href="http://imgcommander.com">Sign up for an IMG.Commander API key</a>, and 3) Go to your <a href="plugins.php?page=imgcommander-key-config">IMG.Commander configuration</a> page, and save your API key.
Version: 0.3
Author: IMG.Commander
Author URI: http://imgcommander.com/
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

define('IMGCOMMANDER_VERSION', '0.3');
define('IMGCOMMANDER_PLUGIN_URL', plugin_dir_url( __FILE__ ));

if ( !function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
	exit;
}

function imgcommander_activate()
{
	update_option('imgcommander_licenses_to_search', "4,6,3,2,1,5,7");
	update_option('imgcommander_search_by_keywords', false);
	update_option('imgcommander_caption_template', "<a href=\"%author_url%\">%author_name%</a> at %provider%");
}

register_activation_hook( __FILE__, 'imgcommander_activate' );

function imgcommander_init() {

}
add_action('init', 'imgcommander_init');


if ( is_admin() )
	require_once dirname( __FILE__ ) . '/admin.php';
