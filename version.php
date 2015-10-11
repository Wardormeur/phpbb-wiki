<?php


namespace wardormeur\wiki;



class version{
	public function __construct(
		\phpbb\config\config $config,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\user $user,
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

	}

	//should use builders https://wiki.phpbb.com/Queries_in_phpBB3

	public function add_version($wiki_id, $content)
	{
		$sql = 'SELECT MAX(version_id) AS version_nb
        FROM ' . $this->table_prefix."wwiki_contents WHERE wiki_id=$wiki_id";

		$result = $this->db->sql_query($sql);

		// The user count is now available here:
		$version_nb = (int) $this->db->sql_fetchfield('version_nb');
		$version_nb ++;
		$user_id = $this->user->data['user_id'];
		$content = htmlspecialchars($content);
		$sql = 'INSERT INTO '.$this->table_prefix."wwiki_contents ".$this->db->sql_build_array('INSERT',
['wiki_id'=>$wiki_id,'version_id'=>$version_nb,'wiki_text'=>$content,'wiki_edit_user'=>$user_id,'wiki_edit_time'=>time()]);
		$this->db->sql_query($sql);
		$this->db->sql_freeresult($result);
	}

	public function remove_version($wiki_id, $version_id)
	{
		//Remove content
		$sql = 'DELETE FROM '.$this->table_prefix."wwiki_contents WHERE wiki_id= $wiki_id AND version_id = $version_id";
		$this->db->sql_query($sql);
		$this->db->sql_freeresult($result);
	}

	public function toggle_wiki_mode($post_id)
	{
		$wiki = (int) $this->is_wiki($post_id);
		if( $wiki > 0 ) //Is Wiki, but we want to get rid of it
		{
			deactivate($this->get_wiki_by_post($post_id), $post_id);
		}else // Is not Wiki, but we'd like it to become one
		{
			$wiki_id = $this->activate($post_id);
		}
		return $wiki;

	}

	public function is_wiki($post_id)
	{
		$wiki_count = 0;
		$sql = 'SELECT COUNT(post_id) AS wiki_count
        FROM ' . $this->table_prefix."wwiki_posts WHERE post_id=$post_id";

		$result = $this->db->sql_query($sql);

		$wiki_count = (int) $this->db->sql_fetchfield('wiki_count');

		$this->db->sql_freeresult($result);

		return $wiki_count;
	}

	public function deactivate($wiki_id,$post_id)
	{
		//recover every versions
		$sql = 'SELECT wiki_id, version_id FROM '.$this->table_prefix."wwiki_contents WHERE wiki_id = $wiki_id";
		$result = $this->db->sql_query($sql);

		while ($version = $this->db->sql_fetchrow($result))
		{
			$this->remove_version($wiki_id,$version['version_id']);
		}
		$this->db->sql_freeresult($result);

		//Remove link to post
		$sql = 'DELETE FROM '.$this->table_prefix."wwiki_posts WHERE wiki_id= $wiki_id AND post_id = $post_id";
		$this->db->sql_query($sql);

	}

	public function activate($post_id)
	{
		//Remove link to post
		$sql = 'INSERT INTO '.$this->table_prefix."wwiki_posts (post_id) VALUES($post_id) ";
		$this->db->sql_query($sql);
		//we recover the data from the post?
		return $this->db->sql_nextid();
	}

	public function get_wiki_by_post($post_id){
		$wiki_ids = [];
		$sql = 'SELECT wiki_id FROM '.$this->table_prefix."wwiki_posts WHERE post_id = $post_id";
		$result = $this->db->sql_query($sql);
		while ($version = $this->db->sql_fetchrow($result))
		{
			$wiki_ids[] = $version['wiki_id'];
		}
		$this->db->sql_freeresult($result);
		return $wiki_ids[count($wiki_ids)-1];
	}

	private function get_nb_version($wiki_id)
	{
		$versions = 0;
		$sql = 'SELECT COUNT(wiki_id) AS version_count
        FROM ' . $this->table_prefix."wwiki_contents WHERE wiki_id=$wiki_id";

		$result = $this->db->sql_query($sql);

		$versions = (int) $this->db->sql_fetchfield('version_count');

		$this->db->sql_freeresult($result);

		return $versions;
	}


	public function clean_version ($wiki_id, $post_id){
		$versions = $this->get_nb_version($wiki_id);
		$max_version = $this->config['wwiki_version_nb'];
		if($versions > $max_version)
		{

			for($i = 0; $i<($versions-$max_version); $i++)
			{
				$sql = 'SELECT MIN(version_id) as min_version FROM '.$this->table_prefix."wwiki_contents WHERE wiki_id = $wiki_id";

				$result = $this->db->sql_query($sql);
				$row = $this->db->sql_fetchrow($result);
				$this->db->sql_freeresult($result);
				$this->remove_version($wiki_id,$row['min_version']);
			}
		}
	}


	//This should be another service
	public function set_lock($wiki_id, $locker_id)
	{
		$sql = 'UPDATE '.$this->table_prefix."wwiki_contents SET locker_id = $locker_id WHERE wiki_id=$wiki_id "; //in previous version, i was targeting only the last version. I dont care anymore.
		$this->db->sql_query($sql);
	}

	public function get_lock($post_id){
		$wiki_id = $this->get_wiki_by_post($post_id);
		$sql = 'SELECT locker_id FROM '.$this->table_prefix."wwiki_contents WHERE wiki_id = $wiki_id";
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);
		return $row['locker_id'];
	}

/*Todo : return data + return last version for bbcode*/
	public function get_last($wiki_id)
	{
		$this->id = $wiki_id;
		$sql = 'SELECT content,author_id FROM '.$this->table_prefix."wwiki_contents WHERE wiki_id = $wiki_id AND version_nb = (SELECT MAX(version_nb) FROM ".$this->table_prefix."wwiki_contents WHERE wiki_id = $wiki_id)";
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->id = $wiki_id;
		//todo recover more info
		$this->content = htmlspecialchars_decode($row['content']);
		$this->authors[] = $row['author_id'];

		$this->db->sql_freeresult($result);

		return $this;

	}

	public function get_content()
	{
			return $this->content;
	}
	public function get_authors()
	{
		return $this->authors;
	}

	public function get_last_author()
	{
		return $this->authors[count($this->authors)-1];
	}

	public function get_posts()
	{
		return $this->posts;
	}

}
?>
