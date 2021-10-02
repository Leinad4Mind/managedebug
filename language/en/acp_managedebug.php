<?php
/**
 *
 * @package Manage Debug Options
 * @copyright (c) 2021 david63
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

/**
 * DO NOT CHANGE
 */
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

/**
 * DEVELOPERS PLEASE NOTE
 *
 * All language files should use UTF-8 as their encoding and the files must not contain a BOM.
 *
 * Placeholders can now contain order information, e.g. instead of
 * 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
 * translators to re-order the output of data while ensuring it remains correct
 *
 * You do not need this where single placeholders are used, e.g. 'Message %d' is fine
 * equally where a string contains only two placeholders which are used to wrap text
 * in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
 *
 * Some characters you may want to copy&paste:
 * ’ » “ ” …
 *
 */

$lang = array_merge($lang, [
	'DEBUG_ALL_GROUPS'				=> 'All groups',
	'DEBUG_ALL_GROUPS_EXPLAIN'		=> 'Select this option if you want the memmbers of all of the User Groups to see the debug output.',
	'DEBUG_GROUP_OPTIONS'			=> 'Debug group options',
	'DEBUG_GROUPS'					=> 'Select groups',
	'DEBUG_GROUPS_EXPLAIN'			=> 'Select one, or more, User Groups to see the debug output.<br><b>Note:</b> This option will only apply if the “All groups” option above is set to “No”.',
	'DEBUG_OPTIONS'					=> 'Debug output options',

	'MANAGE_DEBUG_OPTIONS'			=> 'Manage debug options',
	'MANAGE_DEBUG_OPTIONS_EXPLAIN'	=> 'Here you can set the options for which group(s) can see which debug options.',

	'SHOW_DEBUG_GZIP'				=> 'Show GZIP',
	'SHOW_DEBUG_GZIP_EXPLAIN'		=> 'Display in the debug output whether GZIP is on or off.',
	'SHOW_DEBUG_LOAD'				=> 'Show load time',
	'SHOW_DEBUG_LOAD_EXPLAIN'		=> 'Display in the debug output the load time for this page.',
	'SHOW_DEBUG_MEMORY'				=> 'Show memory useage',
	'SHOW_DEBUG_MEMORY_EXPLAIN'		=> 'Display in the debug output the memory useage for this page.',
	'SHOW_DEBUG_QUERIES'			=> 'Show number of queries',
	'SHOW_DEBUG_QUERIES_EXPLAIN'	=> 'Display in the debug output the number of queries executed for this page.',
	'SHOW_DEBUG_SQL'				=> 'Show sql',
	'SHOW_DEBUG_SQL_EXPLAIN'		=> 'Display in the debug output the sql queries that have been run for this page.<br><b>Note:</b> This will only be available for Admins.',
]);
