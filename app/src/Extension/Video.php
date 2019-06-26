<?php

namespace App\Extension;

use App\Form\Field\DBVideoURL;
use App\Util;
use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataExtension;
use SilverStripe\View\ArrayData;

class Video extends DataExtension {
	private static $db = [
		'VideoURL' => DBVideoURL::class,
		'VideoTitle' => 'Varchar',
		'VideoLeadIn' => 'Text'
	];

	private static $has_one = [
		'VideoThumbnail' => Image::class
	];

	private static $owns = [
		'VideoThumbnail'
	];

	public function getVideoData() {
		return ArrayData::create([
			'URL' => $this->owner->VideoURL,
			'Title' => $this->owner->VideoTitle,
			'LeadIn' => $this->owner->VideoLeadIn,
			'Thumbnail' => $this->owner->VideoThumbnail()
		]);
	}

	public function onAfterWrite() {
		if (!$this->owner->VideoThumbnailID && $this->owner->VideoURL) {
			if ($thumnail = Util::getYouTubeThumbnail($this->owner->VideoURL, $this->owner->VideoThumbnail, 'Video')) {
				$this->owner->VideoThumbnailID = $thumnail->ID;
				$this->owner->write();
			}
		}

		parent::onAfterWrite();
	}
}
