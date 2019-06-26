<?php

namespace App\View;

use App\Form\FormController;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\SSViewer;
use SilverStripe\ORM\DataObject;
use SilverStripe\View\ArrayData;
use SilverStripe\Control\Director;
use App\Subscription\SubscriptionController;
use SilverStripe\View\TemplateGlobalProvider;

class SiteTemplateGlobalProvider implements TemplateGlobalProvider {
	public static function get_template_global_variables() {
		return [
			'PhoneLink' => [
				'method' => 'PhoneLink',
				'casting' => 'HTMLFragment'
			],
			'TextEmphasize' => [
				'method' => 'TextEmphasize',
				'casting' => 'HTMLFragment'
			],
			'TextDeemphasize' => [
				'method' => 'TextDeemphasize',
				'casting' => 'HTMLFragment'
			],
			'TextEmphasizeFromStart' => [
				'method' => 'TextEmphasizeFromStart',
				'casting' => 'HTMLFragment'
			],
			'TextEmphasizeFromEnd' => [
				'method' => 'TextEmphasizeFromEnd',
				'casting' => 'HTMLFragment'
			],
			'Bread' => 'Breadcrumbs',
			'ContactForm' => 'ContactForm',
			'NewsSubscribeFooterForm' => 'NewsSubscribeFooterForm',
			'GetPageByType' => 'GetPageByType'
		];
	}

	public static function PhoneLink($number, $classes = '', $text = null) {
		$text = is_null($text) ? $number : $text;
		$parsedNumber = trim(sprintf('+1%s', preg_replace('/(\s|\.|\-|\(|\))/', '', $number)));
		$phoneLink = sprintf('<a href="tel:%s" class="phone-link %s">%s</a>', $parsedNumber, $classes, $text);

		return $phoneLink;
	}

	public static function TextEmphasize($text) {
		$formatted = str_replace('[', '<strong>', trim($text));
		$formatted = str_replace(']', '</strong>', trim($formatted));
		$formatted = str_replace('|', '<br>', trim($formatted));

		return trim($formatted);
	}

	public static function TextDeemphasize($text) {
		$formatted = str_replace('[', '', trim($text));
		$formatted = str_replace(']', '', trim($formatted));

		return trim($formatted);
	}

	public static function TextEmphasizeFromStart($text, $numWords = 1) {
		$text = trim($text);
		$output = '';
		$words = preg_split('/\s+/', $text);
		$chunks = array_chunk($words, $numWords);

		$wrapped = join(' ', $chunks[0]);
		$chunks[0] = ["<strong>{$wrapped}</strong>"];

		$output = call_user_func_array('array_merge', $chunks);
		$output = join(' ', $output);

		return $output;
	}

	public static function TextEmphasizeFromEnd($text, $numWords = 1) {
		$text = trim($text);
		$output = '';
		$words = preg_split('/\s+/', $text);
		$chunks = array_chunk($words, $numWords);

		$wrapped = join(' ', $chunks[count($chunks) - 1]);
		$chunks[count($chunks) - 1] = ["<strong>{$wrapped}</strong>"];

		$output = call_user_func_array('array_merge', $chunks);
		$output = join(' ', $output);

		return $output;
	}

	public static function Breadcrumbs() {
		$page = Director::get_current_page();
		$maxDepth = 20;
		$stopAtPageType = false;
		$showHidden = false;

		$pages = [];

		while ($page
			&& $page->exists()
			&& (!$maxDepth || count($pages) < $maxDepth)
			&& (!$stopAtPageType || $page->ClassName != $stopAtPageType)
		) {
			if ($showHidden || $page->ShowInMenus) {
				$pages[] = $page;
			}

			$page = $page->Parent();
		}

		$pages = ArrayList::create(array_reverse($pages));

		$template = SSViewer::create('Includes/Breadcrumbs');

		return $template->process(ArrayData::create([
			'Pages' => $pages,
			'Delimiter' => '/'
		]));
	}

	public static function ContactForm() {
		$controller = FormController::create();

		return $controller->ContactForm();
	}

	public static function GetPageByType($pageType) {
		return DataObject::get_one('App\\Page\\' . $pageType);
	}
}
