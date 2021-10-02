<?php
/**
 *
 * @package Manage Debug Options
 * @copyright (c) 2021 david63
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace david63\managedebug\event;

/**
 * @ignore
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use phpbb\config\config;
use phpbb\user;

/**
 * Event listener
 */
class listener implements EventSubscriberInterface
{
	/** @var config */
	protected $config;

	/** @var user */
	protected $user;

	/** @var string phpBB root path */
	protected $root_path;

	/** @var string phpBB extension */
	protected $php_ext;

	/**
	 * Constructor for listener
	 *
	 * @param config	$config		Config object
	 * @param user 		$user		User object
	 * @param string	$root_path	phpBB root path
	 * @param string	$php_ext	phpBB file extension
	 *
	 * @access public
	 */
	public function __construct(config $config, user $user, string $root_path, string $php_ext)
	{
		$this->config		= $config;
		$this->user			= $user;
		$this->root_path	= $root_path;
		$this->php_ext		= $php_ext;
	}

	/**
	 * Assign functions defined in this class to event listeners in the core
	 *
	 * @return array
	 * @static
	 * @access public
	 */
	public static function getSubscribedEvents()
	{
		return [
			'core.phpbb_generate_debug_output' => 'display_debug_options',
		];
	}

	/**
	 * Set the debug options to be displayed for the user
	 *
	 * @param object $event The event object
	 * @return $event
	 * @access public
	 */
	public function display_debug_options($event)
	{
		$debug_info = $event['debug_info'];

		if (!function_exists('group_memberships'))
		{
			include_once $this->root_path . 'includes/functions_user.' . $this->php_ext;
		}

		if (group_memberships(json_decode($this->config['debug_group']), $this->user->data['user_id']) || $this->config['debug_all'])
		{
			if (!$this->config['debug_load'])
			{
				unset($debug_info[0]);
			}

			if (!$this->config['debug_memory'])
			{
				unset($debug_info[1]);
			}

			if (!$this->config['debug_gzip'])
			{
				unset($debug_info[2]);
			}

			if (!$this->config['debug_queries'])
			{
				unset($debug_info[3]);
			}

			if (!$this->config['debug_sql'])
			{
				unset($debug_info[4]);
			}
		}
		else
		{
			$debug_info = [];
		}

		$event['debug_info'] = $debug_info;
	}
}
