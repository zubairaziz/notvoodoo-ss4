<?php

namespace App\Model;

use App\Util;
use App\Extension\Sortable;
use App\Form\Field\DBVideoURL;
use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use App\Traits\CMSPermissionProvider;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\SelectionGroup;
use SilverStripe\Forms\SelectionGroup_Item;
use SilverStripe\AssetAdmin\Forms\UploadField;

class SortableMedia extends DataObject {
	use CMSPermissionProvider;

	private static $table_name = 'SortableMedia';
	private static $singular_name = 'Media Item';

	private static $extensions = [
		Sortable::class
	];

	private static $db = [
		'VideoURL' => DBVideoURL::class,
		'Type' => 'Enum("Image,Video", "Image")'
	];

	private static $has_one = [
		'Image' => Image::class,
		'Owner' => DataObject::class
	];

	private static $owns = [
		'Image'
	];

	private static $summary_fields = [
		'Image.CMSThumbnail' => 'Image',
		'Type' => 'Type'
	];

	public function getCMSFields() {
		$fields = FieldList::create();

		if (!$this->exists()) {
			$fields->push(
				SelectionGroup::create('Type', [
					SelectionGroup_Item::create(
						'Image',
						UploadField::create('Image', '')->setAllowedFileCategories('image'),
						'Image'
					),
					SelectionGroup_Item::create(
						'Video',
						TextField::create('VideoURL', '')->showYouTubeHelper(),
						'Video'
					),
				])->setTitle('Media Type')
			);
		}

		if ($this->exists() && $this->Type == 'Image') {
			$fields->push(UploadField::create('Image', 'Image')->setAllowedFileCategories('image'));
		}

		if ($this->exists() && $this->Type == 'Video') {
			$fields->push(TextField::create('VideoURL', 'Video URL')->showYouTubeHelper());
			$fields->push(UploadField::create('Image', 'Image')->setAllowedFileCategories('image'));
		}

		return $fields;
	}

	public function getCMSValidator() {
		return RequiredFields::create([
			'Type'
		]);
	}

	public function getTitle() {
		return $this->Type == 'Image' ? 'Image' : 'Video';
	}

	public function onBeforeWrite() {
		if (!$this->ImageID && $this->Type == 'Video' && $this->VideoURL) {
			if ($thumnail = Util::getYouTubeThumbnail($this->VideoURL, $this->Image, 'Video')) {
				$this->ImageID = $thumnail->ID;
			}
		}

		parent::onBeforeWrite();
	}
}
