<?php

namespace App;

use SilverStripe\ORM\ArrayList;
use SilverStripe\View\SSViewer;
use SilverStripe\View\ArrayData;
use SilverStripe\Forms\LiteralField;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\AssetAdmin\Controller\AssetAdmin;
use SilverStripe\Versioned\GridFieldArchiveAction;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\GridField\GridFieldToolbarHeader;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use Symbiote\GridFieldExtensions\GridFieldAddExistingSearchButton;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;

class Util {
	public static function cmsInfoMessage($message, $noMargin = false) {
		return LiteralField::create(
			'CMSInfoMessage',
			sprintf(
				'<div class="alert alert-info" role="alert" style="%s">%s</div>',
				$noMargin ? '' : 'margin-bottom: 30px',
				$message
			)
		);
	}

	public static function cmsWarningMessage($message, $noMargin = false) {
		return LiteralField::create(
			'CMSWarningMessage',
			sprintf(
				'<div class="alert alert-warning" role="alert" style="%s">%s</div>',
				$noMargin ? '' : 'margin-bottom: 30px',
				$message
			)
		);
	}

	public static function cmsSubscriptionNotificationMessage() {
		return self::cmsWarningMessage('A notification email will be sent to all subscribers once this record is published for the first time. Do not publish until you are ready to send.');
	}

	public static function getYouTubeID($url) {
		$pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';

		if (preg_match($pattern, $url, $match)) {
			return $match[1];
		}

		return false;
	}

	public static function getYouTubeThumbnail($videoURL, $imageObj, $folder) {
		if ($youtubeId = self::getYouTubeID($videoURL)) {
			$validURL = null;
			$hqURL = sprintf('https://img.youtube.com/vi/%s/maxresdefault.jpg', $youtubeId);
			$defaultURL = sprintf('https://img.youtube.com/vi/%s/mqdefault.jpg', $youtubeId);

			if (self::testYouTubeThumbnailURL($hqURL)) {
				$validURL = $hqURL;
			} elseif (self::testYouTubeThumbnailURL($defaultURL)) {
				$validURL = $defaultURL;
			}

			if ($validURL) {
				$fileData = file_get_contents($validURL, false);
				$filename = sprintf('%s/%s.jpg', $folder, md5($fileData));
				$imageObj->setFromString($fileData, $filename);
				$imageObj->publishSingle();
				AssetAdmin::create()->generateThumbnails($imageObj);

				return $imageObj;
			}
		}

		return false;
	}

	public static function testYouTubeThumbnailURL($url) {
		$handle = curl_init($url);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($handle);
		$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

		curl_close($handle);

		return $httpCode == 200;
	}

	// Useful for taking a bare array of string values and allowing them
	// to be iterated over and rendered in a template (via ArrayList)
	public static function array2ArrayList($arr) {
		$list = ArrayList::create();

		foreach ($arr as $item) {
			$text = DBField::create_field('Varchar', $item);
			$list->push($text);
		}

		return $list;
	}

	public static function getYearRange($start = 2010, $end = null) {
		$end = is_null($end) ? (int) date('Y') : $end;

		$years = array_reverse(range($start, $end));
		return array_combine($years, $years);
	}

	public static function getRecordEditorConfig($sortable = true) {
		$config = GridFieldConfig_RecordEditor::create(200);

		if ($sortable) {
			$config->addComponent(GridFieldOrderableRows::create());
		}

		return $config;
	}

	public static function getRelationEditorConfig($sortable = true, $sortColumn = 'Sort') {
		$config = GridFieldConfig_RelationEditor::create(200);
		$config->removeComponentsByType(GridFieldAddNewButton::class);
		$config->removeComponentsByType(GridFieldAddExistingAutocompleter::class);
		$config->removeComponentsByType(GridFieldArchiveAction::class);
		$config->addComponent(new GridFieldAddExistingSearchButton());

		if ($sortable) {
			$config->addComponent(GridFieldOrderableRows::create($sortColumn));
		}

		return $config;
	}

	public static function getFakeBreadcrumbs($parentPage, $parentTitle, $currentTitle) {
		$parentLink = $parentPage->Link();
		$parentTitle = $parentTitle ?: $parentPage->MenuTitle;

		$pages = [
			ArrayData::create(['Link' => $parentLink, 'MenuTitle' => $parentTitle]),
			ArrayData::create(['MenuTitle' => $currentTitle])
		];

		$pages = ArrayList::create($pages);

		$template = SSViewer::create('Includes\\Breadcrumbs');

		return $template->process(ArrayData::create([
			'Pages' => $pages,
			'Delimiter' => '/'
		]));
	}

	// Given a DataList and an item in that list, return the previous and next items
	public static function getItemNeighbors($list, $item) {
		$prev = null;
		$next = null;

		$ids = array_keys($list->map('ID', 'ClassName')->toArray());
		$index = array_search($item->ID, $ids);

		if (array_key_exists($index - 1, $ids)) {
			$prev = $list->byID($ids[$index - 1]);
		}

		if (array_key_exists($index + 1, $ids)) {
			$next = $list->byID($ids[$index + 1]);
		}

		return ArrayData::create([
			'Prev' => $prev,
			'Next' => $next
		]);
	}

	// Chunk a list into $chunks lists, favoring more items towards the left
	// Ex: Start list => [1, 2, 3, 4, 5, 6, 7] ($chunks = 3)
	//     End list => [[1, 2, 3], [4, 5, 6], [7]]
	public static function chunkList($list, $chunks = 2, $perChunk = null) {
		if (!$list) {
			return false;
		}

		if ($list->count() < $chunks) {
			$lists = ArrayList::create();
			$lists->push($list);

			return $lists;
		}

		$lists = ArrayList::create();
		$itemsPerList = ceil($list->count() / $chunks);

		if (!is_null($perChunk)) {
			$itemsPerList = $perChunk;
		}

		$chunked = array_chunk($list->toArray(), $itemsPerList);

		foreach ($chunked as $items) {
			$list = ArrayList::create();

			foreach ($items as $item) {
				$list->push($item);
			}

			$lists->push($list);
		}

		return $lists;
	}
}
