<?php

namespace App\Page;

use App\Control\PaginatedPageControllerInterface;
use PageController;
use SilverStripe\Control\Director;
use SilverStripe\Core\Convert;
use SilverStripe\ORM\PaginatedList;
use SilverStripe\View\ArrayData;

abstract class PaginatedPageController extends PageController implements PaginatedPageControllerInterface {
	protected $pageLength = 6;

	protected function init() {
		parent::init();
	}

	protected function index() {
		if (method_exists($this, 'setIndexFilters')) {
			$this->setIndexFilters();
		}

		return $this->handleIndexAjaxResponse() ?: $this->render();
	}

	protected function handleIndexAjaxResponse() {
		if (Director::is_ajax($this->getRequest())) {
			return $this->getPageResults();
		}
	}

	protected function getResultsResponse($items, $listInclude) {
		$list = PaginatedList::create($items, $this->getRequest());
		$list->setPageLength($this->pageLength);

		if (Director::is_ajax($this->getRequest())) {
			$listHTML = $list->renderWith($listInclude)->value;
			$navHTML = $list->renderWith('Includes/PaginatedListNav')->value;

			$response = [
				'success' => true,
				'list' => $listHTML,
				'nav' => $navHTML
			];

			$this->getResponse()->addHeader('Content-Type', 'application/json');

			return Convert::array2json($response);
		} else {
			return ArrayData::create([
				'Items' => $list,
				'RenderedList' => $list->renderWith($listInclude)
			]);
		}
	}
}
