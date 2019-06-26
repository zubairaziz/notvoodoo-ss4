<?php

use SilverStripe\Assets\Folder;
use SilverStripe\Core\Environment;
use Wilr\GoogleSitemaps\GoogleSitemap;
use SilverStripe\FullTextSearch\Solr\Solr;
use SilverStripe\FullTextSearch\Solr\Services\Solr4Service;

// Configure Solr
if (Environment::getEnv('SOLR_HOST')) {
	Solr::configure_server([
		'host' => Environment::getEnv('SOLR_HOST'),
		'port' => Environment::getEnv('SOLR_PORT'),
		'path' => '/solr',
		'version' => '4',
		'service' => Solr4Service::class,
		'extraspath' => BASE_PATH . '/vendor/silverstripe/fulltextsearch/conf/solr/4/extras/',
		'templates' => BASE_PATH . '/vendor/silverstripe/fulltextsearch/conf/solr/4/templates/',
		'indexstore' => [
			'mode' => 'webdav',
			'path' => '/webdav/solr',
			'remotepath' => '/var/www/webdav/solr',
			'auth' => Environment::getEnv('SOLR_AUTH'),
			'port' => Environment::getEnv('SOLR_WEBDAV_PORT')
		]
	]);
}
