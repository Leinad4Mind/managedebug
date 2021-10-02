<?php
/**
 *
 * @package Manage Debug Options
 * @copyright (c) 2021 david63
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace david63\managedebug\controller;

use phpbb\config\config;
use phpbb\request\request;
use phpbb\template\template;
use phpbb\user;
use phpbb\db\driver\driver_interface;
use phpbb\language\language;
use phpbb\log\log;
use phpbb\group\helper;
use david63\managedebug\core\functions;

/**
 * Admin controller
 */
class admin_controller
{
	/** @var config */
	protected $config;

	/** @var request */
	protected $request;

	/** @var template */
	protected $template;

	/** @var user */
	protected $user;

	/** @var driver_interface */
	protected $db;

	/** @var language */
	protected $language;

	/** @var log */
	protected $log;

	/** @var helper */
	protected $group_helper;

	/** @var functions */
	protected $functions;

	/** @var string */
	protected $ext_images_path;

	/** @var array phpBB tables */
	protected $tables;

	/** @var string Custom form action */
	protected $u_action;

	/**
	 * Constructor for admin controller
	 *
	 * @param config			$config             Config object
	 * @param request			$request            Request object
	 * @param template			$template           Template object
	 * @param user				$user               User object
	 * @param driver_interface	$db					The db connection
	 * @param language			$language           Language object
	 * @param log				$log                Log object
	 * @param helper            $group_helper   	Group helper object
	 * @param functions			$functions          Functions for the extension
	 * @param string			$ext_images_path    Path to this extension's images
	 * @param array				$tables				phpBB db tables
	 *
	 * @return \david63\managedebug\controller\admin_controller
	 * @access public
	 */
	public function __construct(config $config, request $request, template $template, user $user, driver_interface $db, language $language, log $log, helper $group_helper, functions $functions, string $ext_images_path, array $tables)
	{
		$this->config			= $config;
		$this->request			= $request;
		$this->template			= $template;
		$this->user				= $user;
		$this->db				= $db;
		$this->language			= $language;
		$this->log				= $log;
		$this->group_helper		= $group_helper;
		$this->functions		= $functions;
		$this->ext_images_path	= $ext_images_path;
		$this->tables			= $tables;
	}

	/**
	 * Display the options a user can configure for this extension
	 *
	 * @return null
	 * @access public
	 */
	public function display_options()
	{
		// Add the language files
		$this->language->add_lang(['acp_managedebug', 'acp_common'], $this->functions->get_ext_namespace());

		// Create a form key for preventing CSRF attacks
		$form_key = 'manage_debug';
		add_form_key($form_key);

		$back = false;

		// Is the form being submitted
		if ($this->request->is_set_post('submit'))
		{
			// Is the submitted form is valid
			if (!check_form_key($form_key))
			{
				trigger_error($this->language->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
			}

			// If no errors, process the form data
			// Set the options the user configured
			$this->set_options();

			// Add option settings change action to the admin log
			$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_MANAGE_DEBUG_OPTIONS');

			// Option settings have been updated and logged
			// Confirm this to the user and provide link back to previous page
			trigger_error($this->language->lang('CONFIG_UPDATED') . adm_back_link($this->u_action));
		}

		// Template vars for header panel
		$version_data = $this->functions->version_check();

		// Are the PHP and phpBB versions valid for this extension?
		$valid = $this->functions->ext_requirements();

		// Create the groups select
		$this->create_groups_select(json_decode($this->config['debug_group']));

		$this->template->assign_vars([
			'DOWNLOAD' 			=> (array_key_exists('download', $version_data)) ? '<a class="download" href =' . $version_data['download'] . '>' . $this->language->lang('NEW_VERSION_LINK') . '</a>' : '',

			'EXT_IMAGE_PATH' 	=> $this->ext_images_path,

			'HEAD_TITLE' 		=> $this->language->lang('MANAGE_DEBUG_OPTIONS'),
			'HEAD_DESCRIPTION'	=> $this->language->lang('MANAGE_DEBUG_OPTIONS_EXPLAIN'),

			'NAMESPACE' 		=> $this->functions->get_ext_namespace('twig'),

			'PHP_VALID' 		=> $valid[0],
			'PHPBB_VALID' 		=> $valid[1],

			'S_BACK' 			=> $back,
			'S_VERSION_CHECK' 	=> (array_key_exists('current', $version_data)) ? $version_data['current'] : false,

			'VERSION_NUMBER' 	=> $this->functions->get_meta('version'),
		]);

		// Set output vars for display in the template
		$this->template->assign_vars([
			'DEBUG_ALL' 	=> isset($this->config['debug_all']) ? $this->config['debug_all'] : '',
			'DEBUG_GZIP' 	=> isset($this->config['debug_gzip']) ? $this->config['debug_gzip'] : '',
			'DEBUG_LOAD' 	=> isset($this->config['debug_load']) ? $this->config['debug_load'] : '',
			'DEBUG_MEMORY' 	=> isset($this->config['debug_memory']) ? $this->config['debug_memory'] : '',
			'DEBUG_QUERIES' => isset($this->config['debug_queries']) ? $this->config['debug_queries'] : '',
			'DEBUG_SQL' 	=> isset($this->config['debug_sql']) ? $this->config['debug_sql'] : '',

			'U_ACTION' 		=> $this->u_action,
		]);
	}

	/**
	 * Set the options a user can configure
	 *
	 * @return null
	 * @access protected
	 */
	protected function set_options()
	{
		$this->config->set('debug_all', $this->request->variable('debug_all', 0));
		$this->config->set('debug_group', json_encode($this->request->variable('debug_group', [0])));
		$this->config->set('debug_gzip', $this->request->variable('debug_gzip', 0));
		$this->config->set('debug_load', $this->request->variable('debug_load', 0));
		$this->config->set('debug_memory', $this->request->variable('debug_memory', 0));
		$this->config->set('debug_queries', $this->request->variable('debug_queries', 0));
		$this->config->set('debug_sql', $this->request->variable('debug_sql', 0));
	}

	/**
	 * Set page url
	 *
	 * @param string $u_action Custom form action
	 * @return null
	 * @access public
	 */
	public function set_page_url($u_action)
	{
		return $this->u_action = $u_action;
	}

	/**
	 * Create the groups select data and send to the template.
	 *
	 * @param array		$selected		Array of selected groups
	 * @param bool		$exclude_bots	True will exclude the Bots group, false will in clude the Bots group
	 *
	 * @return void
	 */
	public function create_groups_select($selected = [0], $exclude_bots = true)
	{
		$exclude_sql = ($exclude_bots) ? 'WHERE group_name <> "' . $this->db->sql_escape('BOTS') . '"' : '';

		$sql = 'SELECT group_id, group_name
			FROM ' . $this->tables['groups'] . "
			$exclude_sql
			ORDER BY group_name ASC";

		$result = $this->db->sql_query($sql);
		$groups = $this->db->sql_fetchrowset($result);

		$this->db->sql_freeresult($result);

		array_walk($groups, function (&$group) use ($selected)
		{
			$group['group_selected'] = in_array($group['group_id'], $selected);
		});

		foreach ($groups as $group)
		{
			$this->template->assign_block_vars('groups', [
				'ID'			=> $group['group_id'],
				'NAME'			=> $this->group_helper->get_name($group['group_name']),
				'S_SELECTED'	=> (bool) $group['group_selected'],
			]);
		}
	}
}
