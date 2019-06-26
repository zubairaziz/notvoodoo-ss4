<?php

namespace App\Form;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Forms\DropdownField;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\Filters\ExactMatchFilter;
use SilverStripe\ORM\Filters\PartialMatchFilter;
use SilverStripe\ORM\Search\SearchContext;

class ContactFormSubmission extends DataObject {
	private static $default_sort = 'Created DESC';
	private static $table_name = 'ContactFormSubmission';

	private static $db = [
		'FirstName' => 'Varchar',
		'LastName' => 'Varchar',
		'Email' => 'Varchar',
		'Phone' => 'Varchar',
		'City' => 'Varchar',
		'State' => 'Varchar',
		'Zip' => 'Varchar',
		'HearAboutUs' => 'Varchar',
		'ContactingUs' => 'Varchar',
		'Comments' => 'Text'
	];

	private static $has_one = [
		'SourcePage' => SiteTree::class
	];

	private static $summary_fields = [
		'Created.Nice' => 'Submit Date',
		'FirstName' => 'First Name',
		'LastName' => 'Last Name',
		'Email' => 'Email',
		'Phone' => 'Phone',
		'City' => 'City',
		'State' => 'State',
		'Zip' => 'Zip',
		'SourcePage.Title' => 'Source'
	];

	public function getTitle() {
		return sprintf('%s %s', $this->FirstName, $this->LastName);
	}

	public function getExportFields() {
		$fields = self::$summary_fields;

		$fields['HearAboutUs'] = 'How did you hear about us?';
		$fields['ContactingUs'] = 'Why are you contacting us?';
		$fields['Comments'] = 'Comments';

		return $fields;
	}

	public function getEmailFields() {
		$fields = [
			'Source' => $this->SourcePage()->exists() ? $this->SourcePage()->Title : null,
			'Name' => $this->getTitle(),
			'Email' => $this->Email,
			'Phone' => $this->Phone,
			'City' => $this->City,
			'State' => $this->State,
			'Zip' => $this->Zip,
			'How did you hear about us?' => $this->HearAboutUs,
			'Why are you contacting us?' => $this->ContactingUs,
			'Comments' => $this->Comments
		];

		return $fields;
	}

	public function getDefaultSearchContext() {
		$sources = array_filter(self::get()->columnUnique('SourcePageID'));
		$sources = array_unique($sources);

		$fields = $this->scaffoldSearchFields();
		$fields->removeByName('Created');
		$fields->removeByName('SourcePage__Title');

		if (!empty($sources)) {
			$fields->push(
				DropdownField::create('SourcePageID', 'Source')
					->setSource(SiteTree::get()->byIds($sources)->map('ID', 'Title'))
					->setEmptyString('Select a Source')
			);
		}

		$filters = [
			'FirstName' => new PartialMatchFilter('FirstName'),
			'LastName' => new PartialMatchFilter('LastName'),
			'Email' => new PartialMatchFilter('Email'),
			'Phone' => new PartialMatchFilter('Phone'),
			'City' => new PartialMatchFilter('City'),
			'State' => new PartialMatchFilter('State'),
			'Zip' => new PartialMatchFilter('Zip'),
			'SourcePageID' => new ExactMatchFilter('SourcePageID')
		];

		return new SearchContext(
			self::class,
			$fields,
			$filters
		);
	}
}
