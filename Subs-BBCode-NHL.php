<?php
/**********************************************************************************
* Subs-BBCode-Sports.php
***********************************************************************************
***********************************************************************************
* This program is distributed in the hope that it is and will be useful, but      *
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY    *
* or FITNESS FOR A PARTICULAR PURPOSE.                                            *
**********************************************************************************/

function BBCode_NHL(&$bbc)
{
	// Format: [nhl width=x height=x]{playlist ID}[/nhl]
	$bbc[] = array(
		'tag' => 'nhl',
		'type' => 'unparsed_content',
		'parameters' => array(
			'width' => array('value' => ' width="$1"', 'match' => '(\d+)'),
			'height' => array('value' => ' height="$1"', 'match' => '(\d+)'),
		),
		'validate' => 'BBCode_NHL_Validate',
		'content' => '<iframe class="youtube-player" type="text/html"{width}{height} src="http://video.nhl.com/videocenter/embed?playlist=$1" allowfullscreen frameborder="0"></iframe>',
		'disabled_content' => '$1',
	);

	// Format: [nhl]{playlist ID}[/nhl]
	$bbc[] = array(
		'tag' => 'nhl',
		'type' => 'unparsed_content',
		'validate' => 'BBCode_NHL_Validate',
		'content' => '<iframe class="youtube-player" type="text/html" width="640" height="400" src="http://video.nhl.com/videocenter/embed?playlist=$1" allowfullscreen frameborder="0"></iframe>',
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
	parse_str(parse_url(str_replace('&amp;', '&', $data), PHP_URL_QUERY), $out);
	$data = (isset($out['id']) ? $out['id'] : (int) $data);
}

?>