<?php
/**
 * Elgg pagination
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses int $vars['offset']
 * @uses int $vars['limit']
 * @uses int $vars['count'] Number of entities.
 * @uses string $vars['word'] Word to use in GET params for the offset
 * @uses string $vars['baseurl'] Base URL to use in links
 */

if (elgg_in_context('widget')) {
	// widgets do not show pagination
	return true;
}

$offset = abs((int) elgg_get_array_value('offset', $vars, 0));
// because you can say $vars['limit'] = 0
if (!$limit = (int) elgg_get_array_value('limit', $vars, 10)) {
	$limit = 10;
}

$count = (int) elgg_get_array_value('count', $vars, 0);
$word = elgg_get_array_value('word', $vars, 'offset');
$base_url = elgg_get_array_value('baseurl', $vars, current_page_url());

$num_pages = elgg_get_array_value('num_pages', $vars, 10);
$delta = ceil($num_pages / 2);

if ($count <= $limit && $offset == 0) {
	// no need for pagination
	return true;
}

$total_pages = ceil($count / $limit);
$current_page = ceil($offset / $limit) + 1;

$pages = new stdClass();
$pages->prev = array(
	'text' => '&laquo; ' . elgg_echo('previous'),
	'href' => '',
);
$pages->next = array(
	'text' => elgg_echo('next') . ' &raquo;',
	'href' => '',
);
$pages->items = array();

// Add pages before the current page
if ($current_page > 1) {
	$prev_offset = $offset - $limit;
	if ($prev_offset < 0) {
		$prev_offset = 0;
	}

	$pages->prev['href'] = elgg_http_add_url_query_elements($base_url, array($word => $prev_offset));

	$first_page = $current_page - $delta;
	if ($first_page < 1) {
		$first_page = 1;
	}

	$pages->items = range($first_page, $current_page - 1);
}


$pages->items[] = $current_page;


// add pages after the current one
if ($current_page < $total_pages) {
	$next_offset = $offset + $limit;
	if ($next_offset >= $count) {
		$next_offset--;
	}

	$pages->next['href'] = elgg_http_add_url_query_elements($base_url, array($word => $next_offset));

	$last_page = $current_page + $delta;
	if ($last_page > $total_pages) {
		$last_page = $total_pages;
	}

	$pages->items = array_merge($pages->items, range($current_page + 1, $last_page));
}


echo '<ul class="elgg-pagination">';

if ($pages->prev['href']) {
	$link = elgg_view('output/url', $pages->prev);
	echo "<li>$link</li>";
} else {
	echo "<li><span class=\"inactive\">{$pages->prev['text']}</span></li>";
}

foreach ($pages->items as $page) {
	if ($page == $current_page) {
		echo "<li><span class=\"active\">$page</span></li>";
	} else {
		$page_offset = (($page - 1) * $limit);
		$url = elgg_http_add_url_query_elements($base_url, array($word => $page_offset));
		echo "<li><a href=\"$url\">$page</a></li>";
	}
}

if ($pages->next['href']) {
	$link = elgg_view('output/url', $pages->next);
	echo "<li>$link</li>";
} else {
	echo "<li><span class=\"inactive\">{$pages->next['text']}</span></li>";
}

echo '</ul>';
