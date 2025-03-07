<?php
declare(strict_types=1);
require(__DIR__ . '/_cli.php');

performRequirementCheck(FreshRSS_Context::$system_conf->db['type'] ?? '');

$params = array(
		'user:',
		'password:',
		'api_password:',
		'language:',
		'email:',
		'token:',
		'purge_after_months:',
		'feed_min_articles_default:',
		'feed_ttl_default:',
		'since_hours_posts_per_rss:',
		'max_posts_per_rss:',
	);

if (!isset($isUpdate)) {
	$isUpdate = false;
} elseif (!$isUpdate) {
	$params[] = 'no_default_feeds';	//Only for creating new users
}

$GLOBALS['options'] = getopt('', $params);

if (!validateOptions($argv, $params) || empty($options['user'])) {
	fail('Usage: ' . basename($_SERVER['SCRIPT_FILENAME']) .
		" --user username ( --password 'password' --api_password 'api_password'" .
		" --language en --email user@example.net --token 'longRandomString'" .
		($isUpdate ? '' : ' --no_default_feeds') .
		" --purge_after_months 3 --feed_min_articles_default 50 --feed_ttl_default 3600" .
		" --since_hours_posts_per_rss 168 --max_posts_per_rss 400 )");
}

function strParam(string $name): ?string {
	global $options;
	return isset($options[$name]) ? strval($options[$name]) : null;
}

function intParam(string $name): ?int {
	global $options;
	return isset($options[$name]) && ctype_digit($options[$name]) ? intval($options[$name]) : null;
}

$values = array(
		'language' => strParam('language'),
		'mail_login' => strParam('email'),
		'token' => strParam('token'),
		'old_entries' => intParam('purge_after_months'),	//TODO: Update with new mechanism
		'keep_history_default' => intParam('feed_min_articles_default'),	//TODO: Update with new mechanism
		'ttl_default' => intParam('feed_ttl_default'),
		'since_hours_posts_per_rss' => intParam('since_hours_posts_per_rss'),
		'max_posts_per_rss' => intParam('max_posts_per_rss'),
	);

$values = array_filter($values);
