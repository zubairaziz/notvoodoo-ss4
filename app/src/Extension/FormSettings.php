<?php

namespace App\Extension;

use App\Util;
use App\Email\EmailRecipient;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;

class FormSettings extends DataExtension {
	private static $db = [
		'SubmitSuccessMessage' => 'HTMLText',
		'AutoresponderEnabled' => 'Boolean',
		'AutoresponderSubject' => 'Varchar',
		'AutoresponderContent' => 'HTMLText'
	];

	private static $has_many = [
		'EmailRecipients' => EmailRecipient::class
	];

	public function populateDefaults() {
		$this->owner->SubmitSuccessMessage = '<p>Thank you. We have received your submission.</p>';
		$this->owner->AutoresponderSubject = 'Thank you. We have received your submission.';
		$this->owner->AutoresponderContent = '<p>Thank you. We have received your submission.</p>';

		parent::populateDefaults();
	}

	public function canCreate($member = null, $context = []) {
		return false;
	}

	public function canDelete($member = null) {
		return false;
	}

	public function updateCMSFields(FieldList $fields) {
		// Email recipients tab
		$recipientsGrid = GridField::create(
			'EmailRecipients',
			'Email Recipients',
			$this->owner->EmailRecipients(),
			Util::getRecordEditorConfig($sortable = false)
		);

		$fields->addFieldsToTab('Root.Email Recipients', [
			Util::cmsInfoMessage('Notify these users of a new submission.'),
			$recipientsGrid
		]);

		// Messages tab
		$fields->addFieldsToTab('Root.Messages', [
			Util::cmsInfoMessage('Displays on the page after submitting the form.'),
			HTMLEditorField::create('SubmitSuccessMessage', 'Message')
		]);

		// Autoresponder tab
		$fields->addFieldsToTab('Root.Autoresponder', [
			Util::cmsInfoMessage('Sends an email to the user after submission.'),
			FieldGroup::create(
				'Enabled',
				CheckboxField::create('AutoresponderEnabled', 'Enable Autoresponder')
			),
			TextField::create('AutoresponderSubject', 'Email Subject'),
			HTMLEditorField::create('AutoresponderContent', 'Email Content')
		]);
	}
}
