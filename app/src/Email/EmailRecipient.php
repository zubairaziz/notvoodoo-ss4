<?php

namespace App\Email;

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Security\Permission;
use SilverStripe\Forms\RequiredFields;

class EmailRecipient extends DataObject {
	private static $table_name = 'EmailRecipient';

	private static $db = [
		'Email' => 'Varchar(100)'
	];

	private static $has_one = [
		'Owner' => DataObject::class
	];

	private static $summary_fields = [
		'Email'
	];

	public function getTitle() {
		return $this->Email;
	}

	public function getCMSFields() {
		return FieldList::create(
			TextField::create('Email')
		);
	}

	public function getCMSValidator() {
		return RequiredFields::create([
			'Email'
		]);
	}

	public function onBeforeWrite() {
		parent::onBeforeWrite();

		$this->Email = trim($this->Email);
	}
}
