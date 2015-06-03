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

class add_lock extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array('\wardormeur\wiki\migrations\install_wiki');
	}

	public function update_schema()
	{
		return 
			array('add_columns' 	=> 
				array($this->table_prefix . 'wwiki_contents'	=> 
					array(
						'locker_id'	=> array('UINT', null),
					)
				)
			);
	}

	public function revert_schema()
	{
		return 
			array('drop_columns' => array(
				$this->table_prefix . 'wwiki_contents'	=> array(
					'locked'
					)
				)
			);
	}

}
	