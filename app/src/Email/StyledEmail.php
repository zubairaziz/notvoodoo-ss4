<?php

namespace App\Email;

use SilverStripe\Control\Director;
use SilverStripe\Control\Email\Email;
use SilverStripe\Core\Environment;
use SilverStripe\SiteConfig\SiteConfig;

class StyledEmail extends Email {
	public function __construct($to, $subject) {
		parent::__construct();

		$this->setTo($to);
		$this->setSubject($subject);
		$this->setHTMLTemplate('App/Email/StyledEmail');
	}

	public function build($body = '', $footer = null) {
		$this->setData([
			'SiteConfig' => SiteConfig::current_site_config(),
			'SiteLogo' => Director::absoluteURL('/resources/app/client/dist/images/email-logo.png'),
			'Subject' => $this->getSubject(),
			'Body' => $body,
			'Footer' => $footer
		]);

		return $this;
	}
}
