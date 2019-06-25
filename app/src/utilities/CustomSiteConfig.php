<?php

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\TextareaField;

class CustomSiteConfig extends DataExtension {
	private static $db = [
		'Street' => 'Varchar',
		'City' => 'Varchar',
		'State' => 'Varchar',
		'Zip' => 'Varchar',
		'Disclaimer' => 'Text'
	];

	public function updateCMSFields(FieldList $f) {
		$f->addFieldToTab('Root.Footer', new TextField('Street'));
		$f->addFieldToTab('Root.Footer', new TextField('City'));
		$f->addFieldToTab('Root.Footer', new TextField('State'));
		$f->addFieldToTab('Root.Footer', new TextField('Zip'));
		$f->addFieldToTab('Root.Footer', new TextareaField('Disclaimer', 'Disclaimer text'));
	}
}
