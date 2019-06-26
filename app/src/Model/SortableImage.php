<?php

namespace App\Model;

use App\Extension\Sortable;
use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\FieldList;
use App\Traits\CMSPermissionProvider;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\AssetAdmin\Forms\UploadField;

class SortableImage extends DataObject {
	use CMSPermissionProvider;

	private static $table_name = 'SortableImage';
	private static $singular_name = 'Image';

	private static $extensions = [
		Sortable::class
	];

	private static $has_one = [
		'Image' => Image::class,
		'Owner' => DataObject::class
	];

	private static $owns = [
		'Image'
	];

	private static $summary_fields = [
		'Image.CMSThumbnail' => 'Image'
	];

	public function getCMSFields() {
		$fields = FieldList::create(
			UploadField::create('Image')->setAllowedFileCategories('image')
		);

		return $fields;
	}

	public function getCMSValidator() {
		return RequiredFields::create([
			'Image'
		]);
	}

	public function Me() {
		return $this->Image();
	}

	public function getTitle() {
		return 'Image';
	}

	public function onAfterWrite() {
		parent::onAfterWrite();

		$this->publishRecursive();
	}
}
