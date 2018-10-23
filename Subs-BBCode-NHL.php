<?php
/**********************************************************************************
* Subs-BBCode-NHL.php
***********************************************************************************
* This mod is licensed under the 2-clause BSD License, which can be found here:
*	http://opensource.org/licenses/BSD-2-Clause
***********************************************************************************
* This program is distributed in the hope that it is and will be useful, but      *
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY    *
* or FITNESS FOR A PARTICULAR PURPOSE.                                            *
**********************************************************************************/
if (!defined('SMF')) 
	die('Hacking attempt...');

function BBCode_NHL(&$bbc)
{
	// Format: [nhl width=x height=x]{playlist ID}[/nhl]
	$bbc[] = array(
		'tag' => 'nhl',
		'type' => 'unparsed_content',
		'parameters' => array(
			'width' => array('match' => '(\d+)'),
			'height' => array('optional' => true, 'match' => '(\d+)'),
			'frameborder' => array('optional' => true, 'match' => '(\d+)'),
		),
		'validate' => 'BBCode_NHL_Validate',
		'content' => '{width}|{height}|{frameborder}',
		'disabled_content' => '$1',
	);

	// Format: [nhl]{playlist ID}[/nhl]
	$bbc[] = array(
		'tag' => 'nhl',
		'type' => 'unparsed_content',
		'validate' => 'BBCode_NHL_Validate',
		'content' => '0|0|0',
		'disabled_content' => '$1',
	);
}

function BBCode_NHL_Button(&$buttons)
{
	$buttons[count($buttons) - 1][] = array(
		'image' => 'nhl',
		'code' => 'nhl',
		'description' => 'NHL',
		'before' => '[nhl]',
		'after' => '[/nhl]',
	);
}

function BBCode_NHL_Validate(&$tag, &$data, &$disabled)
{
	global $modSettings, $txt;
	
	if (empty($data))
		return ($tag['content'] = '');
	list($width, $height, $frameborder) = explode('|', $tag['content']);
	if (empty($width) && !empty($modSettings['nhl_default_width']))
		$width = $modSettings['nhl_default_width'];
	if (empty($height) && !empty($modSettings['nhl_default_height']))
		$height = $modSettings['nhl_default_height'];
	preg_match('#(http|https):\/\/video\.nhl\.com/videocenter/(console\?id=|embed?playlist=)(\d+)#i', $data, $parts);
	$data = (isset($parts[3]) ? $parts[3] : (int) $data);
	$tag['content'] = (empty($data) ? '' : '<div' . (empty($width) || empty($height) ? '' : ' style="max-width: ' . $width . 'px; max-height: ' . $height . 'px;') . '"><div class="nhl-wrapper">' .
		'<iframe class="NHL-player" type="text/html" src="http://video.nhl.com/videocenter/embed?playlist=' . $data . '" allowfullscreen frameborder="' . $frameborder . '"></iframe></div></div>');
}

function BBCode_NHL_Settings(&$config_vars)
{
	$config_vars[] = array('int', 'nhl_default_width');
	$config_vars[] = array('int', 'nhl_default_height');
}

function BBCode_NHL_LoadTheme()
{
	global $context, $settings;
	$context['html_headers'] .= '
	<link rel="stylesheet" type="text/css" href="' . $settings['default_theme_url'] . '/css/BBCode-NHL.css" />';
	$context['allowed_html_tags'][] = '<iframe>';
}

function BBCode_NHL_Embed(&$message)
{
	$pattern = '#(|\[nhl(|.+?)\](([<br />]+)?))(http|https):\/\/video\.nhl\.com/videocenter/(console\?id=|embed?playlist=)(\d+)(([<br />]+)?)(\[/nhl\]|)#i';
	$message = preg_replace($pattern, '[nhl$2]$5://video.nhl.com/videocenter/$6$7$8[/nhl]', $message);
	$pattern = '#\[code(|(.+?))\](|.+?)\[nhl(|.+?)\](.+?)\[/nhl\](|.+?)\[/code\]#i';
	$message = preg_replace($pattern, '[code$1]$3$5$6[/code]', $message);
}

?>