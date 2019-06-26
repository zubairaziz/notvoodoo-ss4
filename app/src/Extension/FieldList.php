<?php

namespace App\Extension;

use SilverStripe\ORM\DataExtension;

class FieldList extends DataExtension {
	public function moveFieldToMain($fieldName) {
		$field = $this->owner->moveFieldToTab($fieldName, 'Main');

		return $field;
	}

	public function moveFieldToTab($fieldName, $tabName) {
		$field = $this->owner->dataFieldByName($fieldName);
		$this->owner->removeByName($fieldName);
		$this->owner->addFieldToTab('Root.' . $tabName, $field);

		return $field;
	}

	public function hideField($fieldName) {
		$field = $this->owner->dataFieldByName($fieldName);

		$field->addExtraClass('hide-field');

		return $field;
	}
}
