<?php

namespace App\Form;

use App\Extension\FormSettings;
use App\Model\SortableText;
use App\Traits\FormSettings as AppFormSettings;
use App\Util;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\TabSet;
use SilverStripe\ORM\DataObject;

class ContactFormSettings extends DataObject {
	use AppFormSettings;

	private static $table_name = 'ContactFormSettings';

	private static $extensions = [
		FormSettings::class
	];

	private static $has_many = [
		'HearAboutUsOptions' => SortableText::class . '.Owner',
		'ContactingUsOptions' => SortableText::class . '.AdditionalOwner'
	];

	public function getCMSFields() {
		$fields = FieldList::create(
			TabSet::create('Root')
		);

		$this->extend('updateCMSFields', $fields);

		$fields->addFieldsToTab('Root.Options', [
			GridField::create(
				'HearAboutUsOptions',
				'How Did You Hear About Us Options',
				$this->HearAboutUsOptions(),
				Util::getRecordEditorConfig()
			),
			GridField::create(
				'ContactingUsOptions',
				'Why Are You Contacting Us Options',
				$this->ContactingUsOptions(),
				Util::getRecordEditorConfig()
			)
		]);

		return $fields;
	}
}
