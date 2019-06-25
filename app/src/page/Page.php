<?php

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Control\Director;

class Page extends SiteTree {
    private static $db = [];

    private static $has_one = [];

    public function CSSTimeStamp() {
        $file = Director::baseFolder() . '/public' . $this->ThemeDir() . '/dist/styles/main.css';
        if (file_exists($file)):
            $stat = stat($file);
            return $stat['mtime'];
        endif;
    }

    public function JSTimeStamp() {
        $file = Director::baseFolder() . '/public' . $this->ThemeDir() . '/dist/js/index.js';
        if (file_exists($file)):
            $stat = stat($file);
            return $stat['mtime'];
        endif;
    }

}
