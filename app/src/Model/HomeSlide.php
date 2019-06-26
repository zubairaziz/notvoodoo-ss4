<?php

namespace App\Model;

use App\Util;
use App\Extension\Sortable;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\TabSet;
use App\Extension\CallToAction;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\CMS\Model\SiteTree;
use App\Traits\CMSPermissionProvider;
use SilverStripe\Forms\CheckboxField;
use UncleCheese\DisplayLogic\Forms\Wrapper;
use SilverStripe\AssetAdmin\Forms\UploadField;

class HomeSlide extends DataObject {
	use CMSPermissionProvider;

	private static $table_name = 'HomeSlide';
	private static $singular_name = 'Home Slide';

	private static $extensions = [
		Sortable::class,
		CallToAction::class
	];

	private static $db = [
		'Headline' => 'Varchar',
		'UseDefaultHeadline' => 'Boolean',
		'UseDefaultImage' => 'Boolean',
		'UseDefaultCTAs' => 'Boolean'
	];

	private static $has_one = [
		'Owner' => SiteTree::class,
		'BackgroundImage' => Image::class
	];

	private static $owns = [
		'BackgroundImage'
	];

	private static $summary_fields = [
		'BackgroundImage.CMSThumbnail' => 'Image',
		'Headline' => 'Headline'
	];

	public function getCMSFields() {
		$fields = FieldList::create(
			TabSet::create('Root')
		);

		$fields->addFieldsToTab('Root.Main', [
			CheckboxField::create('UseDefaultHeadline', 'Use the default headline'),
			TextField::create('Headline')->showEmphasisHelper()->hideIf('UseDefaultHeadline')->isChecked()->end()
		]);

		if ($this->exists()) {
			$fields->addFieldsToTab('Root.Main', [
				CheckboxField::create('UseDefaultImage', 'Use the default image'),
				Wrapper::create(
					UploadField::create('BackgroundImage')
						->setAllowedFileCategories('image')
						->setFolderName('Home')
				)->hideIf('UseDefaultImage')->isChecked()->end(),
				CheckboxField::create('UseDefaultCTAs', 'Use the default Call To Actions'),
				Wrapper::create(
					$this->buildCTAGrid('HomeSlide' . $this->ID)
				)->hideIf('UseDefaultCTAs')->isChecked()->end()
			]);
		} else {
			$fields->push(
				Util::cmsInfoMessage('You can add an image and call to actions after saving for the first time')
			);
		}

		$this->extend('updateCMSFields', $fields);

		return $fields;
	}

	public function getTitle() {
		return 'Slide';
	}
}
