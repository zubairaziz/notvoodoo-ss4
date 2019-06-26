<?php

namespace App\Task;

use App\Form\Field\IconField;
use App\View\AssetTemplateGlobalProvider;
use SilverStripe\Dev\BuildTask;

class IconFieldIcons extends BuildTask {
	protected $title = 'Icon Field Icons';

	protected $description = 'Displays all available icons';

	public function run($request) {
		$field = IconField::create('Icons');

		$icons = $field->getList();

		$spriteFile = AssetTemplateGlobalProvider::getAsset('spritemap.svg');

		$html = '<div class="sprites">';

		foreach ($icons as $icon) {
			$svg = AssetTemplateGlobalProvider::getAssetIcon($icon);

			$html .= sprintf('<div>%s<div>%s</div></div>', $svg, $icon);
		}

		$html .= '</div>';

		echo '<style>h1 { display: none; } .sprites  { display: flex; flex-wrap: wrap; } .sprites > div { width: 80px; height: 80px; padding: 40px; text-align: center; font-family: sans-serif; font-size: 14px; } .sprites [data-icon] { width: 100%; height: 100%; margin-bottom: 10px; }</style>';

		echo $html;
	}
}
