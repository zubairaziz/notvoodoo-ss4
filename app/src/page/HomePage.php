<?php

namespace App\Page;

use Page;
use App\Util;
use App\Model\HomeSlide;
use App\Model\HomePageOverlay;
use SilverStripe\ORM\ArrayList;
use SilverStripe\Control\Cookie;
use SilverStripe\View\ArrayData;
use App\Extension\SinglePageInstance;
use SilverStripe\Forms\GridField\GridField;

class HomePage extends Page {
	private static $table_name = 'HomePage';
	private static $singular_name = 'Home';
	private static $plural_name = 'Home';
	private static $description = 'The main Home Page for the site';

	private static $extensions = [
		SinglePageInstance::class
	];

	private static $has_many = [
		'Slides' => HomeSlide::class,
		'Overlays' => HomePageOverlay::class
	];

	public function getCMSFields() {
		$fields = parent::getCMSFields();

		$fields->addFieldsToTab('Root.Header', [
			GridField::create(
				'Slides',
				'Additional Home Slides',
				$this->Slides(),
				Util::getRecordEditorConfig()
			)
		]);

		$fields->addFieldsToTab('Root.Overlays', [
			GridField::create(
				'Overlays',
				'Overlays',
				$this->Overlays(),
				Util::getRecordEditorConfig(false)
			)
		]);

		return $fields;
	}

	public function getHomeSlides() {
		$defaultTitle = $this->HeaderTitle;
		$defaultImage = $this->HeaderBackground();
		$defaultCTAs = $this->CTAs('PageHeader');

		$slides = ArrayList::create();

		$slides->push(ArrayData::create([
			'Title' => $defaultTitle,
			'Image' => $defaultImage,
			'CTAs' => $defaultCTAs
		]));

		foreach ($this->Slides() as $slide) {
			$slides->push(ArrayData::create([
				'Title' => $slide->UseDefaultHeadline ? $defaultTitle : $slide->Headline,
				'Image' => $slide->UseDefaultImage ? $defaultImage : $slide->BackgroundImage(),
				'CTAs' => $slide->UseDefaultCTAs ? $defaultCTAs : $slide->CTAs('HomeSlide' . $slide->ID)
			]));
		}

		return $slides;
	}

	public function getActiveOverlay() {
		/* if (Cookie::get('seenhomepageoverlay')) { */
		/*     return false; */
		/* } */

		return $this->Overlays()->filter([
			'Status' => HomePageOverlay::ACTIVE
		])->first();
	}
}
