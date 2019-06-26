<?php

namespace App\Model;

use App\Extension\Sortable;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use App\Traits\CMSPermissionProvider;
use SilverStripe\Forms\RequiredFields;

class SortableText extends DataObject {
	use CMSPermissionProvider;

	private static $table_name = 'SortableText';
	private static $singular_name = 'Item';

	private static $extensions = [
		Sortable::class
	];

	private static $db = [
		'Title' => 'Varchar'
	];

	private static $has_one = [
		'Owner' => DataObject::class,
		'AdditionalOwner' => DataObject::class
	];

	private static $summary_fields = [
		'Title'
	];

	public function getCMSFields() {
		$fields = FieldList::create(
			TextField::create('Title')
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
