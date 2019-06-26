<?php

namespace App\Page;

use Page;
use App\Extension\SinglePageInstance;

class ContactPage extends Page {
	private static $table_name = 'ContactPage';
	private static $singular_name = 'Contact';
	private static $plural_name = 'Contact';
	private static $description = 'Displays main contact form';

	private static $extensions = [
		SinglePageInstance::class
	];
}
