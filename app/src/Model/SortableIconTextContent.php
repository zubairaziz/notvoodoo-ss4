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
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;

class SortableIconTextContent extends DataObject {
	use CMSPermissionProvider;

	private static $table_name = 'SortableIconTextContent';
	private static $singular_name = 'Item';

	private static $extensions = [
		Sortable::class
	];

	private static $db = [
		'Title' => 'Varchar',
		'Icon' => DBIcon::class,
		'Content' => 'HTMLText'
	];

	private static $has_one = [
		'Owner' => DataObject::class
	];

	private static $summary_fields = [
		'Title'
	];

	public function getCMSFields() {
		$fields = FieldList::create(
			TextField::create('Title')->showEmphasisHelper(),
			IconField::create('Icon'),
			HTMLEditorField::create('Content')
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
