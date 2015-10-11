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
	public	$u_action;

	function main($id, $mode)
	{
		global $request, $template, $user, $phpbb_container,$phpbb_root_path,$config,$phpEx;
		$this->request = $request;
		$this->config = $config;
		$this->user = $user;
		$this->phpbb_container = $phpbb_container;
		$this->template = $template;

		$this->user->add_lang_ext('wardormeur/wiki','info_acp_wiki');

		$this->page_title = $this->user->lang['ACP_WIKI_TITLE'];
		$this->tpl_name = 'wiki_body';

		add_form_key('wikiunicornfart');
		if ($this->request->is_set_post('submit'))
		{
			if (!check_form_key('wikiunicornfart'))
			{
				trigger_error($this->user->lang['FORM_INVALID'] . adm_back_link($this->u_action), E_USER_WARNING);
			}
			$version_nb = $this->request->variable('version_nb','');
			$this->config->set('wwiki_version_nb',$version_nb);
			$wiki_path = $this->request->variable('wiki_path','');
			$this->config->set('wwiki_wiki_path',$wiki_path);
			trigger_error($this->user->lang['SETTINGS_SUCCESS'] . adm_back_link($this->u_action));

		}
		$this->template->assign_var('VERSION_NB', $this->config['wwiki_version_nb']);


	}


}
