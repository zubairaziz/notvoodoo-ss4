<?php

namespace App\Extension;

use App\Page\HomePage;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\CMS\Model\RedirectorPage;

class SiteTree extends DataExtension {
	private static $db = [
		'ShowInPrimaryMenu' => 'Boolean',
		'ShowInFooter' => 'Boolean'
	];

	public function updateSettingsFields(FieldList $fields) {
		$fields->insertAfter(
			'ShowInMenus',
			CheckboxField::create('ShowInPrimaryMenu', 'Show in primary menu?')
		);

		$fields->insertAfter(
			'ShowInPrimaryMenu',
			CheckboxField::create('ShowInFooter', 'Show in footer?')
		);
	}

	public function getSitePrimaryMenu() {
		return \SilverStripe\CMS\Model\SiteTree::get()->filter('ShowInPrimaryMenu', true);
	}

	public function getSiteFooterMenu() {
		return \SilverStripe\CMS\Model\SiteTree::get()->filter('ShowInFooter', true);
	}

	public function getIsExternalRedirector() {
		if ($this->owner->ClassName == RedirectorPage::class) {
			return $this->owner->RedirectionType == 'External';
		}
	}

	public function getIsHomePage() {
		return $this->owner->ClassName == HomePage::class;
	}
}
