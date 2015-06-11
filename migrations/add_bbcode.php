<?php
/**
*
* @package phpBB Gallery Core => THX :)))
* @copyright (c) 2014 nickvergessen
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace phpbbgallery\core\migrations;

class add_bbcode extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array('\wardormeur\wiki\migrations\install_wiki');
	}

	public function update_data()
	{
		return array(
			array('custom', array(array(&$this, 'install_bbcode'))),
		);
	}

	public function revert_data()
	{
		return array(
			array('custom', array(array(&$this, 'remove_bbcode'))),
		);
	}

	public function install_bbcode()
	{
		$sql = 'SELECT bbcode_id FROM ' . $this->table_prefix . 'bbcodes WHERE LOWER(bbcode_tag) = \'wiki\'';
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if (!$row)
		{
			// Create new BBCode
			$sql = 'SELECT MAX(bbcode_id) AS max_bbcode_id FROM ' . $this->table_prefix . 'bbcodes';
			$result = $this->db->sql_query($sql);
			$row = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			if ($row)
			{
				$bbcode_id = $row['max_bbcode_id'] + 1;
				// Make sure it is greater than the core BBCode ids...
				if ($bbcode_id <= NUM_CORE_BBCODES)
				{
					$bbcode_id = NUM_CORE_BBCODES + 1;
				}
			}
			else
			{
				$bbcode_id = NUM_CORE_BBCODES + 1;
			}

			$url = $this->config['wwiki_path'];
			if ($bbcode_id <= BBCODE_LIMIT)
			{
				$this->db->sql_query('INSERT INTO ' . $this->table_prefix . 'bbcodes ' . $this->db->sql_build_array(
					'INSERT',
					array(
						'bbcode_tag'			=> 'wiki',
						'bbcode_id'				=> (int) $bbcode_id,
						'bbcode_helpline'		=> 'WIKI_HELPLINE_PATH',
						'display_on_posting'	=> 1,
						'bbcode_match'			=> '[wiki]{TEXT}[/wiki]',
						'bbcode_tpl'			=> '<a href="' . $url . '{WIKI}"></a>',
						'first_pass_match'		=> '!\[wiki\]([a-zA-Z0-9]+)\[/wiki\]!i',
						'first_pass_replace'	=> '[wiki:$uid]${1}[/wiki:$uid]',
						'second_pass_match'		=> '!\[wiki:$uid\]([a-zA-Z0-9]+)\[/wiki:$uid\]!s',
						'second_pass_replace'	=> '<a href="' . $url . '${1}"></a>'
					)
				));
			}
		}
	}
	public function remove_bbcode()
	{
		$sql = 'DELETE FROM ' . BBCODES_TABLE . ' WHERE bbcode_tag = \'wiki\'';
		$this->db->sql_query($sql);
	}
}
