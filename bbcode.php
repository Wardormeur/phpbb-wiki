<?php


namespace wardormeur\wiki;



class bbcode{
	public function __construct(
		\phpbb\config\config $config,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\user $user,
		\wardormeur\wiki\version $version,
		$table_prefix,
		$phpEx,
		$phpbb_root_path)
	{
		global $phpbb_container;

		$this->db = $db;
		$this->phpbb_user = $user;
		$this->user = $user;
		$this->config = $config;
		$this->table_prefix = $table_prefix;
		$this->phpbb_phpEx = $phpEx;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->version = $version

	}
	//actually, we're only gonna save those from src that are looked actually
	public function update($wiki_id,$wiki_page)
	{
		$this->get_remote_content($wiki_page);
		//check if we need to do smthing
		if($raw != $this->version->content){
			$this->version->add_version($wiki_id,$raw);
		}

	}

	public function get_content($wiki_id)
	{
		return $this->version->get_last_version();
	}

	public function get_remote_content($wiki_page)
	{
			return 	$raw = file_get_contents($this->config['wwiki_path'].$wiki_id);
			//get raw info => security issue there..
	}
	//remove ? => same as calling directly $version
	//add ?
}
?>
