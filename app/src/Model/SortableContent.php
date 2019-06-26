<?php

namespace App\Model;

use App\Extension\Sortable;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\FieldList;
use App\Traits\CMSPermissionProvider;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;

class SortableContent extends DataObject {
	use CMSPermissionProvider;

	private static $table_name = 'SortableContent';
	private static $singular_name = 'Item';

	private static $extensions = [
		Sortable::class
	];

	private static $db = [
		'Content' => 'HTMLText'
	];

	private static $has_one = [
		'Owner' => DataObject::class
	];

	private static $summary_fields = [
		'Content.Summary' => 'Content'
	];

	public function getCMSValidator() {
		return RequiredFields::create([
			'Content'
		]);
	}

	public function getCMSFields() {
		$fields = FieldList::create(
			HTMLEditorField::create('Content')
		);

		$this->extend('updateCMSFields', $fields);

		return $fields;
	}

	public function getTitle() {
		return 'Content Block';
	}
}
