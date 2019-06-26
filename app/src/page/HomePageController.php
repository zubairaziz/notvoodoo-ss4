<?php

namespace App\Page;

use PageController;

class HomePageController extends PageController {
	private static $allowed_actions = [
		'overlay'
	];

	private static $url_handlers = [
		'overlay/$ID!' => 'overlay'
	];

	protected function init() {
		parent::init();
	}

	public function overlay() {
		if ($overlay = $this->Overlays()->byID($this->getRequest()->param('ID'))) {
			return $overlay->renderWith('App/Page/Includes/HomePageOverlay');
		}
	}
}
