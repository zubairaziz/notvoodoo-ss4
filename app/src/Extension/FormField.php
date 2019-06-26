<?php

namespace App\Extension;

use SilverStripe\ORM\DataExtension;

class FormField extends DataExtension {
	public function showEmphasisHelper() {
		$currentTitle = trim($this->owner->RightTitle());
		$helper = 'Surround text in [ and ] brackets for emphasis styling.';

		if ($currentTitle) {
			if (substr($currentTitle, -1) != '.') {
				$helper = sprintf('%s. %s', $currentTitle, $helper);
			} else {
				$helper = sprintf('%s %s', $currentTitle, $helper);
			}
		}

		$this->owner->setRightTitle($helper);

		return $this->owner;
	}

	public function showSuggestedSizeHelper($sizeStr) {
		$currentTitle = trim($this->owner->RightTitle());
		$helper = sprintf('Suggested size: %s.', $sizeStr);

		if ($currentTitle) {
			if (substr($currentTitle, -1) != '.') {
				$helper = sprintf('%s. %s', $currentTitle, $helper);
			} else {
				$helper = sprintf('%s %s', $currentTitle, $helper);
			}
		}

		$this->owner->setRightTitle($helper);

		return $this->owner;
	}

	public function showYouTubeHelper($hidePlaceholder = false) {
		$currentTitle = trim($this->owner->RightTitle());
		$helper = 'Enter a YouTube video URL (ex: https://www.youtube.com/watch?v=xxxxxxxxxxx).';

		if ($currentTitle) {
			if (substr($currentTitle, -1) != '.') {
				$helper = sprintf('%s. %s', $currentTitle, $helper);
			} else {
				$helper = sprintf('%s %s', $currentTitle, $helper);
			}
		}

		if (!$hidePlaceholder) {
			$this->owner->setAttribute('placeholder', 'YouTube Video URL');
		}

		$this->owner->setRightTitle($helper);

		return $this->owner;
	}
}
