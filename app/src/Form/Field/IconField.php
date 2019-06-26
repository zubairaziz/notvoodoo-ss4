<?php

namespace App\Form\Field;

use SilverStripe\Control\Director;
use SilverStripe\Core\Path;
use SilverStripe\Forms\DropdownField;
use SilverStripe\ORM\FieldType\DBField;

class IconField extends DropdownField {
	protected $list = [
		'all-hands-in',
		'arm-flex',
		'arrows-cycle',
		'bullseye',
		'chart',
		'checkmark-circle',
		'clipboard-list',
		'directory-book',
		'dollar-sign',
		'family',
		'hand-money',
		'hands-cup-heart',
		'handshake',
		'handshake-2',
		'heart',
		'heart-hands',
		'open-book',
		'people-circle-connected',
		'people-connected',
		'people-group',
		'person',
		'person-care',
		'person-mental-health',
		'person-traits',
		'plan',
		'puzzle',
		'small-hand-hearts'
	];

	protected $extraClasses = ['dropdown'];

	public function __construct($name, $title = null, $source = [], $value = '', $form = null) {
		if (!empty($source)) {
			$this->setList($source);
		}

		$this->setEmptyString('Select an Icon');

		parent::__construct($name, ($title === null) ? $name : $title, $source, $value, $form);

		$helper = DBField::create_field(
			'HTMLText',
			'<a href="/dev/tasks/App-Task-IconFieldIcons" target="_blank">View available icons</a>'
		);
		$this->setRightTitle($helper);
	}

	public function setList($list = []) {
		if (!is_array($list)) {
			trigger_error("The \$source passed isn't an array.", E_USER_ERROR);
		}

		if (empty($list)) {
			$list = $this->list;
		}

		$this->list = array_combine($list, $list);

		return $this;
	}

	public function getList() {
		$this->list = array_combine($this->list, $this->list);

		ksort($this->list);

		return $this->list;
	}

	public function setSource($source = []) {
		$this->setList($source);
		return $this;
	}

	public function getSource() {
		return $this->getList();
	}
}
