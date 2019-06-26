<?php

namespace App\Search;

use SilverStripe\Versioned\Versioned;
use SilverStripe\FullTextSearch\Solr\SolrIndex;

class PageSearchIndex extends SolrIndex {
	public function init() {
		$this->addClass(Page::class);
		$this->addAllFulltextFields();
		$this->excludeVariantState([SearchVariantVersioned::class => Versioned::DRAFT]);
	}
}
