<?php

namespace App\Model;

use App\Extension\Sortable;
use SilverStripe\Assets\File;
use App\Form\Field\DBVideoURL;
use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\HiddenField;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Control\Controller;
use App\Traits\CMSPermissionProvider;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\SelectionGroup;
use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\Forms\SelectionGroup_Item;
use UncleCheese\DisplayLogic\Forms\Wrapper;
use SilverStripe\AssetAdmin\Forms\UploadField;

class CallToAction extends DataObject {
	use CMSPermissionProvider;
	private static $table_name = 'CallToAction';
	private static $extensions = [
		Sortable::class
	];
	private static $db = [
		'Title' => 'Varchar',
		'Type' => 'Enum("Internal,Anchor,File,Video,External", "Internal")',
		'AnchorTarget' => 'Varchar',
		'ExternalLink' => 'Varchar',
		'PanelIdentifier' => 'Varchar',
		'InternalLinkAnchorTarget' => 'Varchar',
		'VideoURL' => DBVideoURL::class,
		'Style' => 'Varchar'
	];
	private static $has_one = [
		'Owner' => DataObject::class,
		'InternalLink' => SiteTree::class,
		'File' => File::class,
		'ButtonThumbnail' => Image::class
	];
	private static $owns = [
		'File',
		'ButtonThumbnail'
	];
	private static $summary_fields = [
		'Title' => 'Title',
		'NiceType' => 'Link Type'
	];

	public function getCMSFields() {
		$fields = FieldList::create();
		$types = [
			SelectionGroup_Item::create(
				'Internal',
				[
					TreeDropdownField::create('InternalLinkID', '', SiteTree::class)->setHasEmptyDefault(true),
					TextField::create('InternalLinkAnchorTarget', '')
						->setDescription('Optionally include an anchor target on the linked page')
						->setAttribute('placeholder', 'Enter anchor element (with #)')
				],
				'Link to internal page'
			),
			SelectionGroup_Item::create(
				'Anchor',
				TextField::create('AnchorTarget', '')->setAttribute('placeholder', 'Enter anchor element (with #)'),
				'Link to page anchor'
			),
			SelectionGroup_Item::create(
				'External',
				TextField::create('ExternalLink', '')->setAttribute('placeholder', 'Enter URL'),
				'Link to external page'
			),
			SelectionGroup_Item::create(
				'Video',
				TextField::create('VideoURL', '')->showYouTubeHelper(),
				'Link to YouTube video'
			),
			SelectionGroup_Item::create(
				'File',
				UploadField::create('File')->setAllowedFileCategories('document')->setFolderName('Documents'),
				'Link to file'
			)
		];
		$fields->push(SelectionGroup::create('Type', $types)->setTitle('Link Type'));
		$fields->push(TextField::create('Title'));
		$fields->push(DropdownField::create(
			'Style',
			'Style',
			[
				'button-hollow' => 'Button (Hollow)',
				'button-filled' => 'Button (Solid)'
			]
		));
		$buttonThumbnail = Wrapper::create(
			UploadField::create('ButtonThumbnail')
				->setAllowedFileCategories('image')
				->setDescription('Overrides any button text')
		);
		$buttonThumbnail->displayIf('Type')->isEqualTo('Video');
		$fields->push($buttonThumbnail);
		$fields->push(HiddenField::create(
			'PanelIdentifier',
			'PanelIdentifier',
			Controller::curr()->getRequest()->param('FieldName')
		));
		$this->extend('updateCMSFields', $fields);
		return $fields;
	}

	public function validate() {
		$result = parent::validate();
		switch ($this->Type) {
			case 'Internal':
				if (trim($this->Title) == '') {
					$result->addError('Title is required');
				}
				if (is_null($this->InternalLinkID)) {
					$result->addError('Internal Link is required');
				}
				break;
			case 'Anchor':
				if (trim($this->Title) == '') {
					$result->addError('Title is required');
				}
				if (trim($this->AnchorTarget) == '') {
					$result->addError('Anchor Target is required');
				}
				break;
			case 'External':
				if (trim($this->Title) == '') {
					$result->addError('Title is required');
				}
				if (trim($this->ExternalLink) == '') {
					$result->addError('External Link is required');
				}
				break;
			case 'Video':
				if (trim($this->Title) == '') {
					$result->addError('Title is required');
				}
				if (trim($this->VideoURL) == '') {
					$result->addError('Video URL is required');
				}
				break;
			case 'File':
				if (trim($this->Title) == '') {
					$result->addError('Title is required');
				}
				if (!$this->FileID) {
					$result->addError('File is required');
				}
				break;
		}
		return $result;
	}

	public function getNiceType() {
		switch ($this->Type) {
			case 'Internal':
				return sprintf('Internal Link (%s)', $this->InternalLink()->Title);
				break;
			case 'Anchor':
				return sprintf('Page Anchor (%s)', $this->AnchorTarget);
				break;
			case 'External':
				return sprintf('External Link (%s)', $this->ExternalLink);
				break;
			case 'Video':
				return 'Video';
				break;
			case 'File':
				return 'File';
				break;
		}
	}

	public function getClasses() {
		$classes = ['cta'];
		if ($this->Style == 'button-hollow') {
			$classes[] = 'btn';
		}
		if ($this->Style == 'button-filled') {
			$classes = array_merge($classes, ['btn', 'btn--fill']);
		}
		if ($this->Type == 'Video') {
			$classes = ['cta', 'btn--video'];
		}
		return join(' ', $classes);
	}

	public function getIsButton() {
		return $this->Style == 'button-hollow' || $this->Style == 'button-filled';
	}

	public function onBeforeWrite() {
		parent::onBeforeWrite();
		if ($this->Type != 'Internal') {
			$this->InternalLinkID = null;
			$this->InternalLinkAnchorTarget = null;
		}
		if ($this->Type != 'Anchor') {
			$this->AnchorTarget = null;
		}
		if ($this->Type != 'External') {
			$this->ExternalLink = null;
		}
		if ($this->Type != 'Video') {
			$this->VideoURL = null;
		}
		if ($this->Type != 'File') {
			$this->FileID = null;
		}
	}

	public function onAfterWrite() {
		parent::onAfterWrite();
		$this->publishRecursive();
	}
}
