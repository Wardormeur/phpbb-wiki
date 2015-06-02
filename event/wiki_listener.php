<?php

namespace wardormeur\wiki\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
		

class wiki_listener implements EventSubscriberInterface
{
    static public function getSubscribedEvents()
    {
        return array(
            'core.delete_posts_before'	=>	'listen_delete_related_post_wiki',
			'core.viewtopic_modify_post_action_conditions'	=>	'listen_modify_display_editable',
			'core.posting_modify_template_vars'	=>	'listen_add_make_wiki',
			'core.submit_post_end'	=>	'listen_add_version'
		);
    }
	
	public function __construct(
		\phpbb\template\template $template,
		\phpbb\user $user,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\auth\auth $auth,
		\phpbb\request\request $request,
		\phpbb\cache\driver\driver_interface $cache,
		$phpbb_root_path,
		$phpExt,
		\wardormeur\wiki\version $version)
	{
		$this->template		= $template;
		$this->user			= $user;
		$this->db			= $db;
		$this->auth			= $auth;
		$this->request		= $request;
		$this->cache		= $cache;
		$this->root_path	= $phpbb_root_path;
		$this->php_ext		= $phpExt;
		$this->version		= $version;
	}
	//Can he transform to post to wiki?
	public function listen_add_make_wiki($event)
	{
		$this->template->assign_var('MAKE_WIKI', (($this->auth->acl_get('u_wwiki_edit') || $this->auth->acl_get('a_wwiki_edit') || $this->auth->acl_get('m_wwiki_edit'))));
		if($event['post_data']['post_id'])
		{
			$this->template->assign_var('IS_WIKI', $this->version->is_wiki($event['post_data']['post_id']));
		}
	}
	
	//When post is deleted, delete wiki too
	public function listen_delete_related_post_wiki($event)
	{
		$this->version->deactivate($this->version->get_wiki_by_post($event['data']['post_id']),$event['post_data']['post_id']);
	}
	
	//Can he edit the wiki post?
	public function listen_modify_display_editable($event)
	{	
	
		$this->template->assign_var('force_edit_allowed', ($this->auth->acl_get('u_wwiki_edit') || $this->auth->acl_get('a_wwiki_edit') || $this->auth->acl_get('m_wwiki_edit')) 
			&& ($this->version->is_wiki($event['row']['post_id'])>0) );
	}
	
	//save a new version when it's a wiki
	public function listen_add_version($event)
	{
		$wiki = $this->request->variable('post_wiki','');
		
		$post_id  = $event['data']['post_id'];
		$message = $event['data']['message'];
		$was_wiki = $this->version->is_wiki($post_id);
		if($was_wiki > 0 )//possess rows, so it's a wiki
		{
			$wiki_id = $this->version->get_wiki_by_post($post_id);
			if($wiki == 'on')
			{// Is active or want to activate
				$this->version->add_version($wiki_id,$message);
			}else{//Is inactive or want to deactivate
				$this->version->deactivate($wiki_id,$post_id);
			}
		}else
		{//not a wiki yet
			if ($wiki == 'on')//brand new wiki, hurray !
			{
				$wiki_id = $this->version->activate($post_id);
				$this->version->add_version($wiki_id,$message);
			}

		}
	}
}

?>