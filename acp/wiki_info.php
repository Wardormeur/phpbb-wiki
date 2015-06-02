<?php
/**
*
* @package Ban Hammer
* @copyright (c) 2015 phpBB Modders <https://phpbbmodders.net/>
* @author Jari Kanerva <jari@tumba25.net>
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace wardormeur\wiki\acp;

class wiki_info
{
	function module()
	{
		return array(
			'filename'	=> '\wardormeur\wiki\acp\wiki_module',
			'title'	=> 'ACP_WIKI_TITLE',
			'version'	=> '0.0.1',
			'modes'	=> array(
				'settings'	=> array('title' => 'ACP_WIKI_SETTINGS',
									'auth' => 'ext_wardormeur/wiki && acl_a_user',
									'cat' => array('ACP_WIKI_TITLE')),
			),
		);
	}
}
