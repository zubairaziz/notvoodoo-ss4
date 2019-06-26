<?php

namespace App\Model;

use App\Form\Field\DBIcon;
use App\Extension\Sortable;
use App\Form\Field\IconField;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use App\Traits\CMSPermissionProvider;
use SilverStripe\Forms\RequiredFields;

class SortableIconText extends DataObject {
	use CMSPermissionProvider;

	private static $table_name = 'SortableIconText';
	private static $singular_name = 'Item';

	private static $extensions = [
		Sortable::class
	];

	private static $db = [
		'Title' => 'Varchar',
		'Icon' => DBIcon::class
	];

	private static $has_one = [
		'Owner' => DataObject::class
	];

	private static $summary_fields = [
		'Title'
	];

	public function getCMSFields() {
		$fields = FieldList::create(
			TextField::create('Title'),
			IconField::create('Icon')
		);

		$this->extend('updateCMSFields', $fields);

		return $fields;
	}

	public function getCMSValidator() {
		return RequiredFields::create([
			'Title'
		]);
	}
}
