<?php

namespace App\Extension;

use Exception;
use SilverStripe\View\Parsers\URLSegmentFilter;
use SilverStripe\ORM\DataExtension;

class Sluggable extends DataExtension {
	protected $parentName;

	private static $db = [
		'URLSegment' => 'Varchar'
	];

	private static $indexes = [
		'URLSegment' => true
	];

	public function __construct($parentName = null) {
		parent::__construct();

		$this->parentName = $parentName;

		if ($this->owner) {
			$class = $this->owner->ClassName;
			$fields = $this->owner->getSchema()->fieldSpecs($class);
			$hasOne = $this->owner->hasOne();

			if (!isset($hasOne['Parent'])) {
				throw new Exception(sprintf(
					'You must define a `has_one` relationship called %s on this extensions owner class',
					$this->parentName
				));
			}

			if (!isset($fields['Title'])) {
				throw new Exception('You must define a `db` field called Title on this extensions owner class');
			}
		}
	}

	public function onBeforeWrite() {
		if (!$this->owner->URLSegment || ($this->owner->URLSegment && $this->owner->isChanged('Title'))) {
			$this->owner->URLSegment = URLSegmentFilter::create()->filter($this->owner->Title);
			$class = get_class($this->owner);
			$filter = [
				'URLSegment' => $this->owner->URLSegment
			];

			if ($this->parentName) {
				$idField = "{$this->parentName}ID";
				$filter[$idField] = $this->owner->{$idField};
			}

			$count = 1;
			while ($class::get()->filter($filter)->exclude('ID', $this->owner->ID)->exists()) {
				$this->owner->URLSegment = $this->owner->URLSegment . '-' . $count++;
				$filter['URLSegment'] = $this->owner->URLSegment;
			}
		}
	}
}
