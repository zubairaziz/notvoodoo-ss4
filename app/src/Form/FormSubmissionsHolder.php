<?php

namespace App\Form;

use SilverStripe\Forms\TabSet;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\FieldList;
use App\Subscription\Subscription;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig;
use SilverStripe\Forms\GridField\GridFieldButtonRow;
use SilverStripe\Forms\GridField\GridFieldPageCount;
use SilverStripe\Forms\GridField\GridFieldPaginator;
use SilverStripe\Forms\GridField\GridFieldDetailForm;
use SilverStripe\Forms\GridField\GridFieldEditButton;
use SilverStripe\Forms\GridField\GridFieldDataColumns;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Forms\GridField\GridFieldExportButton;
use SilverStripe\Forms\GridField\GridFieldFilterHeader;
use SilverStripe\Forms\GridField\GridFieldToolbarHeader;
use SilverStripe\Forms\GridField\GridFieldSortableHeader;

class FormSubmissionsHolder extends DataObject {
	private static $default_sort = 'Sort';
	private static $table_name = 'FormSubmissionsHolder';

	private static $db = [
		'Title' => 'Varchar',
		'Sort' => 'Int'
	];

	private static $has_one = [
		'Resource' => DataObject::class,
		'Parent' => self::class,
		'SourcePage' => SiteTree::class
	];

	private static $summary_fields = [
		'Title' => 'Type',
		'NiceCount' => 'Count',
		'NiceLatestSubmitDate' => 'Latest Submit Date'
	];

	public function canCreate($member = null, $context = []) {
		return false;
	}

	public function canDelete($member = null) {
		return false;
	}

	public function getChildren() {
		$children = self::get()->filter('ParentID', $this->ID);

		return $children;
	}

	public function getNiceCount() {
		return $this->getRecords()->count();
	}

	public function getNiceLatestSubmitDate() {
		if ($this->getNiceCount()) {
			return $this->getRecords()->sort('Created', 'DESC')->first()->dbObject('Created')->Nice();
		}
	}

	public function getRecords() {
		if ($this->getChildren()->count()) {
			return $this->getChildren();
		}

		$records = DataList::create($this->ResourceClass);

		if ($this->ResourceClass == ContactFormSubmission::class) {
			if ($this->SourcePage()->exists()) {
				return $records->filter('SourcePageID', $this->SourcePageID);
			}
		}

		return $records;
	}

	public function getCMSFields() {
		$fields = FieldList::create();

		$recordsConfig = GridFieldConfig::create()->addComponents(
			new GridFieldButtonRow('before'),
			new GridFieldToolbarHeader,
			new GridFieldDataColumns,
			new GridFieldDetailForm,
			new GridFieldEditButton,
			new GridFieldDeleteAction,
			new GridFieldFilterHeader,
			new GridFieldPageCount('toolbar-header-right'),
			new GridFieldPaginator(100),
			(new GridFieldSortableHeader)
				->setFieldSorting([
					'Created.Nice' => 'Created'
				])
		);

		if ($this->ResourceClass == Subscription::class) {
			$recordsConfig->getComponentByType(GridFieldSortableHeader::class)->setFieldSorting([
				'NiceResourceClass' => 'ResourceClass'
			]);
		}

		if (method_exists(singleton($this->ResourceClass), 'getExportFields')) {
			$recordsConfig->addComponent(
				new GridFieldExportButton('buttons-before-left', singleton($this->ResourceClass)->getExportFields())
			);
		}

		$recordsGrid = GridField::create(
			'Records',
			'Submissions',
			$this->getRecords()->sort('Created', 'DESC'),
			$recordsConfig
		);

		$fields->push($recordsGrid);

		return $fields;
	}
}
