<?php

namespace App\Task;

use App\Model\HomePageOverlay;
use Cake\Chronos\Chronos;
use SilverStripe\Dev\BuildTask;

class UpdateHomePageOverlayTask extends BuildTask {
	private static $segment = 'UpdateHomePageOverlayTask';
	protected $title = 'Update Home Page Overlay Statuses';
	protected $description = 'Updates all home page overlay statuses';

	public function run($request) {
		foreach (HomePageOverlay::get() as $overlay) {
			$overlay->setStatus();
		}
	}
}
