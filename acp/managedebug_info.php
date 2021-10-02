<?php
/**
 *
 * @package Manage Debug Options
 * @copyright (c) 2021 david63
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace david63\managedebug\acp;

class managedebug_info
{
	public function module()
	{
		return [
			'filename'	=> '\david63\managedebug\acp\managedebug_module',
			'title'		=> 'MANAGE_DEBUG_OPTIONS',
			'modes'		=> [
				'main' => ['title' => 'MANAGE_DEBUG_OPTIONS', 'auth' => 'ext_david63/managedebug && acl_a_board', 'cat' => ['MANAGE_DEBUG_OPTIONS']],
			],
		];
	}
}
