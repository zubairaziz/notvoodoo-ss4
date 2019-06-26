<?php

namespace App\Extension;

use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\DataObject;

class Sortable extends DataExtension {
	private static $default_sort = 'Sort ASC';

	private static $db = [
		'Sort' => 'Int'
	];

	public function onBeforeWrite() {
		$shouldIgnore = $this->owner->config()->get('ignore_sortable_sort');

		if (!$this->owner->Sort && !$shouldIgnore) {
			$this->owner->Sort = DataObject::get($this->owner->ClassName)->max('Sort') + 1;
		}

		parent::onBeforeWrite();
	}
}
