<?php
/**
 *
 * @package Manage Debug Options
 * @copyright (c) 2021 david63
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace david63\managedebug\migrations;

class version_3_3_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		// If the config value exists then don't run this migration
		return isset($this->config['debug_all']);
	}

	public function update_data()
	{
		return [
			['config.add', ['debug_all', '0']],
			['config.add', ['debug_group', [0]]],
			['config.add', ['debug_gzip', '0']],
			['config.add', ['debug_load', '0']],
			['config.add', ['debug_memory', '0']],
			['config.add', ['debug_queries', '0']],
			['config.add', ['debug_sql', '0']],

			// Add the ACP module
			['module.add', ['acp', 'ACP_CAT_DOT_MODS', 'MANAGE_DEBUG_OPTIONS']],

			['module.add', [
				'acp', 'MANAGE_DEBUG_OPTIONS', [
					'module_basename' => '\david63\managedebug\acp\managedebug_module',
					'modes' => ['main'],
				],
			]],
		];
	}
}
