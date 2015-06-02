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

class wiki_module
{

	function main($id, $mode)
	{
		global $request, $template, $user, $phpbb_container,$phpbb_root_path,$config,$phpEx;
		$this->request = $request;
		$this->config = $config;
		$this->user = $user;
		$this->phpbb_container = $phpbb_container;
		$this->template = $template;
		
		$this->user->add_lang('acp/groups');

		$this->page_title = $this->user->lang['ACP_WIKI_TITLE'];
		$this->tpl_name = 'wiki_body';

		add_form_key('wikiunicornfart');
	}
}
