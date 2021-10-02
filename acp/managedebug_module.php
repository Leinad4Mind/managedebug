<?php
/**
 *
 * @package Manage Debug Options
 * @copyright (c) 2021 david63
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace david63\managedebug\acp;

class managedebug_module
{
	public $u_action;

	public function main($id, $mode)
	{
		global $phpbb_container;

		$this->tpl_name   = 'acp_managedebug';
		$this->page_title = $phpbb_container->get('language')->lang('MANAGE_DEBUG_OPTIONS');

		// Get an instance of the admin controller
		$admin_controller = $phpbb_container->get('david63.managedebug.admin.controller');

		$admin_controller->display_options();
	}
}
