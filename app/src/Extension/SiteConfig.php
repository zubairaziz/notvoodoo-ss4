<?php

namespace App\Extension;

use SilverStripe\Assets\Image;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\AssetAdmin\Forms\UploadField;

class SiteConfig extends DataExtension {
	private static $db = [
		'GoogleID' => 'Varchar',
		'ContactAddress' => 'Varchar',
		'ContactCity' => 'Varchar',
		'ContactState' => 'Varchar',
		'ContactZip' => 'Varchar',
		'ContactPhoneNumber' => 'Varchar',
		'FacebookURL' => 'Varchar',
		'InstagramURL' => 'Varchar',
		'TwitterURL' => 'Varchar',
		'LinkedInURL' => 'Varchar',
		'YouTubeURL' => 'Varchar'
	];

	private static $has_one = [
		'SocialSharePhoto' => Image::class
	];

	private static $owns = [
		'SocialSharePhoto'
	];

	public function updateCMSFields(FieldList $fields) {
		$fields->removeByName('Tagline');

		$fields->addFieldsToTab('Root.Main', [
			TextField::create('GoogleID', 'Google Tag Manager ID')
		]);

		$fields->addFieldsToTab('Root.Contact Info', [
			TextField::create('ContactAddress', 'Address'),
			TextField::create('ContactCity', 'City'),
			TextField::create('ContactState', 'State'),
			TextField::create('ContactZip', 'Zip'),
			TextField::create('ContactPhoneNumber', 'Phone Number')
		]);

		$fields->addFieldsToTab('Root.Social Media', [
			TextField::create('FacebookURL'),
			TextField::create('InstagramURL'),
			TextField::create('TwitterURL'),
			TextField::create('LinkedInURL', 'LinkedIn URL'),
			TextField::create('YouTubeURL', 'YouTube URL'),
			UploadField::create('SocialSharePhoto')->setAllowedFileCategories('image')
		]);
	}

	public function getSocialMediaList() {
		$items = [
			[
				'Name' => 'Facebook',
				'Icon' => 'facebook',
				'URL' => $this->owner->FacebookURL
			],
			[
				'Name' => 'Instagram',
				'Icon' => 'instagram',
				'URL' => $this->owner->InstagramURL
			],
			[
				'Name' => 'LinkedIn',
				'Icon' => 'linkedin',
				'URL' => $this->owner->LinkedInURL
			],
			[
				'Name' => 'YouTube',
				'Icon' => 'youtube',
				'URL' => $this->owner->YouTubeURL
			],
			[
				'Name' => 'Twitter',
				'Icon' => 'twitter',
				'URL' => $this->owner->TwitterURL
			]
		];

		$list = ArrayList::create();

		foreach ($items as $item) {
			if ($item['URL']) {
				$list->push(ArrayData::create($item));
			}
		}

		return $list;
	}

	public function getContactPhoneNumberWithCountryCode() {
		return sprintf('+1 %s', $this->owner->ContactPhoneNumber);
	}

	public function getDirectionsLink() {
		$address = [
			$this->owner->ContactAddress,
			$this->owner->ContactCity,
			$this->owner->ContactState,
			$this->owner->ContactZip
		];

		$link = sprintf('https://www.google.com/maps/place/%s', join('+', $address));

		return str_replace(' ', '+', $link);
	}

	public function onAfterWrite() {
		parent::onAfterWrite();

		$this->owner->publishRecursive();
	}
}
