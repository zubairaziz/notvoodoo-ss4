<?php

namespace App\Extension;

use App\Util;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\ORM\DataExtension;

class CallToAction extends DataExtension {
	private static $has_many = [
		'CallToActions' => 'App\Model\CallToAction.Owner'
	];

	public function HasCTAs($key = null) {
		return $this->owner->CTAs($key)->count();
	}

	public function CTAs($key = null) {
		$ctas = $this->owner->CallToActions();

		if ($key) {
			$ctas = $ctas->filter('PanelIdentifier', $key);
		}

		return $ctas;
	}

	// Helper for building a CTA Grid Field component
	public function buildCTAGrid($key = null) {
		$gridKey = 'CallToActions';
		$ctas = $this->owner->CallToActions();

		if ($key) {
			$ctas = $ctas->filter('PanelIdentifier', $key);
			$gridKey = $key;
		}

		$grid = GridField::create(
			$gridKey,
			'Call To Actions',
			$ctas,
			Util::getRecordEditorConfig()
		);

		return $grid;
	}
}
