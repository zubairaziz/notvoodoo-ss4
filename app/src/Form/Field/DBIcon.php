<?php

namespace App\Form\Field;

use App\View\AssetTemplateGlobalProvider;
use SilverStripe\ORM\FieldType\DBVarchar;

class DBIcon extends DBVarchar {
	public function forTemplate() {
		if ($this->exists()) {
			return AssetTemplateGlobalProvider::getAssetIcon($this->value);
		}
	}

	public function scaffoldFormField($title = null, $params = null) {
		return IconField::create(
			$this->name,
			$title
		);
	}
}
