<?php

use SilverStripe\Assets\Image;
use SilverStripe\ORM\ArrayList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\TextareaField;
use App\Page\DefinitionOfServicesPage;
use SilverStripe\AssetAdmin\Forms\UploadField;

class Page extends SiteTree {
	private static $db = [
		'HeaderTitle' => 'Varchar',
		'HeaderLeadIn' => 'Text',
		'EnablePanelLinks' => 'Boolean',
		'EnableBreadcrumbs' => 'Boolean',
		'UseLargeLeadIn' => 'Boolean'
	];

	private static $has_one = [
		'HeaderBackground' => Image::class
	];

	private static $owns = [
		'HeaderBackground'
	];

	public function getCMSFields() {
		$fields = parent::getCMSFields();

		$titleDescription = $this->exists() ? sprintf("Leave blank to use '%s'", $this->MenuTitle) : null;

		// Header tab
		$fields->addFieldsToTab('Root.Header', [
			TextField::create('HeaderTitle', 'Title')
				->setDescription($titleDescription)->showEmphasisHelper(),
			TextareaField::create('HeaderLeadIn', 'Lead In'),
			UploadField::create('HeaderBackground', 'Background Photo')
				->setAllowedFileCategories('image')
				->setFolderName('HeaderBackground')
				->showSuggestedSizeHelper('2050x850'),
			FieldGroup::create(
				'Panel Links',
				CheckboxField::create('EnablePanelLinks', 'Display scroll to links for panels')
			)
		]);

		if ($this->ParentID != 0) {
			$fields->addFieldsToTab('Root.Header', [
				FieldGroup::create(
					'Breadcrumbs',
					CheckboxField::create('EnableBreadcrumbs', 'Display breadcrumbs')
				)
			]);
		}

		$fields->addFieldsToTab('Root.Header', [
			FieldGroup::create(
				'Styling',
				CheckboxField::create('UseLargeLeadIn', 'Use a larger font size for Lead In')
			),
			$this->buildCTAGrid('PageHeader')
		]);

		$this->extend('updateCMSFields', $fields);

		return $fields;
	}

	public function getBodyClasses() {
		$classes = [];

		$classes[] = sprintf('page-%s', strtolower($this->URLSegment));
		$classes[] = sprintf('pagetype-%s', strtolower((new \ReflectionClass($this))->getShortName()));

		$pageContainerPageTypes = [
			Page::class,
			ContactPage::class,
		];

		$pageContainerElementTypes = [
			IPAOverview::class,
			WraparoundOverview::class
		];

		$isPageType = in_array($this->ClassName, $pageContainerPageTypes) != false;
		$isElementType = false;

		$hasPageContainer = $isPageType || $isElementType;

		if ($hasPageContainer) {
			$classes[] = 'has-page-container';
		}

		return join(' ', $classes);
	}

	public function getSiteHeaderClasses() {
		$classes = ['site-header'];

		if ($this->HeaderBackground()->exists() ||
			$this->ClassName == self::class ||
			$this->config()->get('force_fixed_site_header')
		) {
			$classes[] = 'site-header--fixed';
		}

		if ($this->config()->get('needs_body_offset')) {
			$classes[] = 'site-header--body-offset';
		}

		return join(' ', $classes);
	}

	public function getPageHeaderClasses() {
		$classes = ['page-header'];

		if ($this->HeaderBackground()->exists()) {
			$classes[] = 'page-header--bg';
		}

		$classes[] = sprintf('%s-header', strtolower($this->URLSegment));

		return join(' ', $classes);
	}

	public function getNicePageHeaderTitle() {
		$headerTitle = $this->getField('HeaderTitle');

		if ($headerTitle) {
			return $headerTitle;
		}

		return $this->MenuTitle;
	}

	public function getHeaderPanelLinks() {
		if (!$this->EnablePanelLinks) {
			return false;
		}
		return false;
	}
}
