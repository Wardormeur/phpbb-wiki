<?php
/**
*
* @package Ban Hammer
* @copyright (c) 2015 phpBB Modders <https://phpbbmodders.net/>
* @author Jari Kanerva <jari@tumba25.net>
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace wardormeur\wiki\migrations;

class install_wiki extends \phpbb\db\migration\migration
{
	public function update_data()
	{
		global $config;
		// Default settings to start with.
	
		$config->set('wwiki_version_nb', 3);
	
		return(array(	
			array('permission.add', array('a_wwiki_edit')), 
			array('permission.add', array('m_wwiki_edit')), 
			array('permission.add', array('m_wwiki_edit', false)), 
			array('permission.add', array('u_wwiki_edit')), 
			array('permission.add', array('u_wwiki_edit', false)), 
			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_WIKI_TITLE'
			)),

			array('module.add', array(
				'acp',
				'ACP_WIKI_TITLE',
				array(
					'module_basename'	=> '\wardormeur\wiki\acp\wiki_module',
					'modes'				=> array('settings'),
				),
			)),
		));
	}

	public function revert_data()
	{
		return(array(
			array('config.remove', array('wwiki_version_nb')),
			array('permission.remove', array('a_wwiki_edit')), 
			array('permission.remove', array('m_wwiki_edit')), 
			array('permission.remove', array('m_wwiki_edit', false)), 
			array('permission.remove', array('u_wwiki_edit')), 
			array('permission.remove', array('u_wwiki_edit', false)), 
			array('module.remove', array(
				'acp',
				'ACP_WIKI_TITLE',
				array(
					'module_basename'	=> '\wardormeur\wiki\acp\wiki_module',
				),
			)),
		));
	}
	public function update_schema()
	{
		return array(
			 'add_tables'    => array(
				$this->table_prefix . 'wwiki_posts'        => array(
					'COLUMNS'        => array(
						'post_id'                => array('UINT', NULL),
						'wiki_id'                => array('UINT', NULL, 'auto_increment')
					),
					'PRIMARY_KEY'        => array('wiki_id','post_id'),
					'KEYS'                => array(
						'pwidi'            => array('INDEX', array('post_id','wiki_id')),
						'pwidu'            => array('UNIQUE', array('post_id','wiki_id')),
					),
				),
				$this->table_prefix . 'wwiki_contents'        => array(
                'COLUMNS'        => array(
                    'wiki_id'		=> array('UINT',NULL),
                    'wiki_text'		=> array('MTEXT_UNI', ''),
					'version_id'	=> array('UINT',0),
					'wiki_edit_time'	=> array('TIMESTAMP', 0),
					'wiki_edit_user'	=> array('UINT', 0)
				
				),
                'PRIMARY_KEY'        => array('wiki_id','version_id'),
                'KEYS'                => array(
						'widi'            => array('INDEX', array('wiki_id','version_id')),
						'widu'            => array('UNIQUE', array('wiki_id','version_id')),
					),
				)
			)
		);
	}

public function revert_schema()
{
    return array(
        'drop_tables'        => array(
			$this->table_prefix. 'wwiki_posts',
			$this->table_prefix . 'wwiki_contents'
        ),
    );
}
}
