<?php

namespace App\Search;

use PageController;
use SilverStripe\FullTextSearch\Search\Queries\SearchQuery;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;

class SearchController extends PageController {
	private static $allowed_actions = [
		'index'
	];

	protected function init() {
		parent::init();
	}

	public function index() {
		$results = ArrayList::create();

		if ($term = $this->getRequest()->getVar('q')) {
			$query = SearchQuery::create()->addSearchTerm($term)->setLimit(999);
			$results = PageSearchIndex::singleton()->search($query);
		}

		$data = [
			'MetaTitle' => 'Search',
			'SiteHeaderClasses' => 'site-header site-header--body-offset',
			'PageHeaderClasses' => 'page-header',
			'NicePageHeaderTitle' => 'Search Results',
			'BodyClasses' => 'pagetype-search',
			'SearchResults' => $results,
			'SearchTerm' => $term
		];

		return $this->customise($data)->render();
	}
}
