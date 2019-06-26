<?php

namespace App\Form;

use App\Form\Field\StateDropdownField;
use App\Page\ContactPage;
use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Forms\CheckboxSetField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\HiddenField;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextareaField;

class ContactForm extends Form {
	public function __construct($controller, $name) {
		$settings = ContactFormSettings::current_settings();

		$hearAboutUsOptions = $settings->HearAboutUsOptions()->column('Title');
		$contactingUsOptions = $settings->ContactingUsOptions()->column('Title');

		$fields = FieldList::create([
			TextField::create('FirstName'),
			TextField::create('LastName'),
			EmailField::create('Email'),
			TextField::create('Phone')
				->setAttribute('type', 'tel'),
			TextField::create('City'),
			StateDropdownField::create('State')
				->addExtraClass('styled-dropdown styled-dropdown--form js-dropdown'),
			TextField::create('Zip'),
			DropdownField::create('HearAboutUs', 'How did you hear about us?')
				->setSource(array_combine($hearAboutUsOptions, $hearAboutUsOptions))
				->addExtraClass('styled-dropdown styled-dropdown--form js-dropdown'),
			DropdownField::create('ContactingUs', 'Why are you contacting us?')
				->setSource(array_combine($contactingUsOptions, $contactingUsOptions))
				->addExtraClass('styled-dropdown styled-dropdown--form js-dropdown'),
			TextareaField::create('Comments', 'Additional Comments or Questions')
				->setAttribute('placeholder', 'Enter Comments or Questions')
		]);

		$actions = FieldList::create(
			FormAction::create('submit', 'Submit')->setUseButtonTag(true)
		);

		$required = RequiredFields::create([
			'FirstName',
			'LastName',
			'Email',
			'Phone',
			'City',
			'State',
			'Zip',
			'HearAboutUs',
			'ContactingUs',
			'Comments'
		]);

		$fields->push(HiddenField::create('SourcePageID', 'Source', ContactPage::get()->first()->ID));
		$fields->push(HiddenField::create('OwnerElement', 'OwnerElement', null));

		parent::__construct($controller, $name, $fields, $actions, $required);

		$this->addExtraClass('form--fly form--styled form--ajax');
		$this->setAttribute('data-form-scroll', true);

		$this->enableSpamProtection([
			'mapping' => [
				'FirstName' => 'authorName',
				'Email' => 'authorMail',
				'Comments' => 'body'
			]
		]);
	}

	public function setSource($sourcePage) {
		$this->Fields()->dataFieldByName('SourcePageID')->setValue($sourcePage->ID);
	}

	public function setOwnerElement($element) {
		$this->Fields()->dataFieldByName('OwnerElement')->setValue($element->ID);
	}

	public function forTemplate() {
		return $this->renderWith('App/Form/ContactForm');
	}

	public function submit($data, $form) {
		$submission = ContactFormSubmission::create();
		$form->saveInto($submission);
		$success = $submission->write();

		$settings = ContactFormSettings::current_settings();

		if ($ownerElement = BaseElement::get()->byID($data['OwnerElement'])) {
			if ($ownerElement->EmailRecipients()->count()) {
				$settings->AdditionalRecipients = $ownerElement->EmailRecipients();
			}
		}

		$this->controller->handleemail(
			$success,
			$submission,
			'New Contact Form Submission',
			$settings
		);

		return $this->controller->handleresponse($success, $settings);
	}
}
