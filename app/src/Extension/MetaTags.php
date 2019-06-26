<?php

namespace App\Extension;

use SilverStripe\View\ArrayData;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\ToggleCompositeField;

class MetaTags extends DataExtension {
	private static $db = [
		'MetaTitle' => 'Varchar',
		'MetaDescription' => 'Text'
	];

	public function updateCMSFields(FieldList $fields) {
		$fields->addFieldsToTab('Root.Main', [
			ToggleCompositeField::create(
				'Metadata',
				'Metadata',
				[
					TextField::create('MetaTitle'),
					TextareaField::create('MetaDescription')
				]
			)->setHeadingLevel(4)
		]);
	}

	public function getMetaTags($defaultTitle = 'Title', $defaultMetaSource = 'Content') {
		return ArrayData::create([
			'MetaTitle' => $this->owner->MetaTitle ?: $this->owner->dbObject($defaultTitle),
			'MetaDescription' => $this->owner->MetaDescription ?: $this->getDefaultMetaDescription($defaultMetaSource)
		]);
	}

	public function getDefaultMetaDescription($sourceField) {
		$field = $this->owner->dbObject($sourceField);

		return $field->LimitCharactersToClosestWord(120, '');
	}
}
