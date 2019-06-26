<?php

namespace App\Admin;

use SilverStripe\Forms\Tab;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\TabSet;
use App\Extension\FormSettings;
use SilverStripe\ORM\ArrayList;
use SilverStripe\Core\ClassInfo;
use SilverStripe\ORM\DataObject;
use SilverStripe\View\ArrayData;
use SilverStripe\Forms\FieldList;
use App\Form\FormSubmissionsHolder;
use SilverStripe\Admin\LeftAndMain;
use SilverStripe\Forms\HiddenField;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig;
use SilverStripe\Forms\GridField\GridFieldDetailForm;
use SilverStripe\Forms\GridField\GridFieldEditButton;
use SilverStripe\Forms\GridField\GridFieldDataColumns;
use SilverStripe\Forms\GridField\GridFieldToolbarHeader;
use SilverStripe\Forms\GridField\GridFieldSortableHeader;

class FormAdmin extends LeftAndMain {
	private static $url_segment = 'forms';
	private static $menu_title = 'Leads/Forms';
	private static $menu_icon_class = 'fas fa-inbox';

	private static $allowed_actions = [
		'EditForm',
		'submissions',
		'settings'
	];

	public function submissions($request) {
		return $this->index($request);
	}

	public function settings($request) {
		return $this->index($request);
	}

	public function getEditForm($id = null, $fields = null) {
		$formSubmissions = FormSubmissionsHolder::get();

		$submissionsConfig = GridFieldConfig::create()->addComponents(
			new GridFieldToolbarHeader,
			new GridFieldDataColumns,
			new GridFieldSortableHeader,
			new GridFieldEditButton,
			(new GridFieldDetailForm)
				->setItemEditFormCallback(function ($form, $itemRequest) {
					$form->addExtraClass('form-submissions-detail');
				})
		);

		$submissionsGrid = GridField::create(
			'Submissions',
			'Submissions',
			$formSubmissions,
			$submissionsConfig
		);

		// Find all settings objects (classes that use the FormSettings extension)
		// for populating the Settings gridfield
		$formSettings = ArrayList::create();

		foreach (ClassInfo::subclassesFor(DataObject::class) as $className) {
			if (singleton($className)->hasExtension(FormSettings::class)) {
				if ($settings = singleton($className)->get()->first()) {
					if (!$settings->config()->get('hide_from_form_admin')) {
						$formSettings->push($settings);
					}
				}
			}
		}

		$settingsConfig = GridFieldConfig::create()->addComponents(
			new GridFieldToolbarHeader,
			(new GridFieldDataColumns)
				->setDisplayFields([
					'Title' => 'Form'
				]),
			new GridFieldSortableHeader,
			new GridFieldEditButton,
			new GridFieldDetailForm
		);

		$settingsGrid = GridField::create(
			'Settings',
			'Settings',
			$formSettings->sort('ClassName'),
			$settingsConfig
		);

		$fields = FieldList::create(
			TabSet::create(
				'Root',
				Tab::create(
					'Submissions',
					'Submissions',
					$submissionsGrid
				),
				Tab::create(
					'Settings',
					'Settings',
					$settingsGrid
				)
			)->setTemplate('SilverStripe\\Forms\\CMSTabSet'),
			HiddenField::create('ID', false, 0)
		);

		// Build replacement form
		$form = Form::create(
			$this,
			'EditForm',
			$fields,
			FieldList::create()
		)->setHTMLID('Form_EditForm');
		$form->addExtraClass('cms-edit-form fill-height');
		$form->setTemplate($this->getTemplatesWithSuffix('_EditForm'));
		$form->addExtraClass('ss-tabset cms-tabset ' . $this->BaseCSSClasses());
		$form->setAttribute('data-pjax-fragment', 'CurrentForm');
		$this->extend('updateEditForm', $form);

		return $form;
	}

	public function Backlink() {
		return false;
	}

	public function Breadcrumbs($unlinked = false) {
		$crumbs = parent::Breadcrumbs($unlinked);
		$params = $this->getRequest()->allParams();
		if (isset($params['FieldName'])) {
			$firstCrumb = $crumbs->shift();
			if ($params['FieldName'] == 'Submissions') {
				$crumbs->unshift(ArrayData::create([
					'Title' => 'Submissions',
					'Link' => $this->Link('submissions')
				]));
			} elseif ($params['FieldName'] == 'Settings') {
				$crumbs->unshift(ArrayData::create([
					'Title' => 'Settings',
					'Link' => $this->Link('settings')
				]));
			}
			$crumbs->unshift($firstCrumb);
		}
		return $crumbs;
	}
}
