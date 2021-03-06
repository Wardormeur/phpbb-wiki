<?php
/**
*
* @package Ban Hammer
* @copyright (c) 2015 phpBB Modders <https://phpbbmodders.net/>
* @author Jari Kanerva <jari@tumba25.net>
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* DO NOT CHANGE
*/
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
$lang = array_merge($lang, array(
	'ACP_WIKI_TITLE'				=> 'Wiki settings !',
	'POST_WIKI'				=> 'Transformer en wiki',
	'ACP_WIKI_SETTINGS'		=> 'Wiki options',
	'VERSION_NB'			=> 'Nombre de versions sauvegardées max',
	'WIKI_EDITION_ONGOING'	=> 'QQun est déjà en train d\'éditer ce post, veuillez attendre',
	'SETTINGS_ERROR'		=> 'There was an error saving your settings. Please submit the back trace with your error report.',
	'SETTINGS_SUCCESS'		=> 'The settings were successfully saved',
	'WIKI_PATH'		=> 'Path to source wiki',
	'WIKI_HELPLINE_PATH'	=>'identifier included in the url of the original wiki page, often the name of the page'
	)
);
