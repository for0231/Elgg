<?php
/**
 * ECML Generic Object GUID
 *
 * @package ECML
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

$guid = $vars['guid'];

if ($entity = get_entity($guid)) {
	echo elgg_view('output/url', array(
		'href' => $entity->getURL(),
		'title' => $entity->title,
		'text' => $entity->title,
		'class' => "embeded_file link",
		// abusing the js attribute
		'js' => "style=\"background-image:url({$entity->getIcon('tiny')})\""

	));
} else {
	echo elgg_echo('ecml:entity:invalid');
}