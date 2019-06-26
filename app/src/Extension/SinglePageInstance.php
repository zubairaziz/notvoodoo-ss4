<?php

namespace App\Extension;

use App\Page\HomePage;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DataExtension;

class SinglePageInstance extends DataExtension {
	public function canCreate($member = null, $context = []) {
		$existing = DataObject::get_one($this->owner->ClassName);

		return !$existing;
	}
}
