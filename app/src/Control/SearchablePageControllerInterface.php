<?php

namespace App\Control;

interface SearchablePageControllerInterface {
	public function setIndexFilters();

	public function getFilterParams();

	public function getIsFiltered();

	public function getFilteredHeading();
}
