<?php

namespace App\View;

use SilverStripe\Core\Manifest\ManifestFileFinder;
use SilverStripe\Core\Path;
use SilverStripe\View\TemplateGlobalProvider;
use SilverStripe\View\Requirements;
use SilverStripe\Control\Director;
use SilverStripe\Control\Controller;
use SilverStripe\Core\Config\Config;

class AssetTemplateGlobalProvider implements TemplateGlobalProvider {
	private static $manifestCache = null;
	private static $assetIconSymbolsCache = null;
	private static $spritemapFile = 'spritemap.svg';

	public static function get_template_global_variables() {
		return [
			'SiteCSS' => 'getSiteCSS',
			'SiteJS' => 'getSiteJS',
			'Asset' => 'getAsset',
			'AssetInline' => [
				'method' => 'getAssetInline',
				'casting' => 'HTMLFragment'
			],
			'AssetIcon' => [
				'method' => 'getAssetIcon',
				'casting' => 'HTMLFragment'
			]
		];
	}

	public static function getSiteCSS() {
		$manifest = self::getManifest();

		if (!empty($manifest['entrypoints'])) {
			foreach ($manifest['entrypoints'] as $entrypoint) {
				if (array_key_exists('css', $entrypoint)) {
					foreach ($entrypoint['css'] as $path) {
						Requirements::themedCSS(self::getResourcePath($path));
					}
				}
			}
		}
	}

	public static function getSiteJS() {
		// Polyfill for svg sprites
		Requirements::javascript(
			'https://cdnjs.cloudflare.com/ajax/libs/svgxuse/1.2.6/svgxuse.min.js',
			['defer' => true]
		);

		// Handle polyfills that babel-preset-env doesn't support
		$polyfills = [
			'Element.prototype.closest',
			'NodeList.prototype.forEach',
			'Node.prototype.contains',
			'fetch-polyfill'
		];

		$polyfillUrl = 'https://polyfill.io/v3/polyfill.min.js?flags=gated&features=default%2C';

		Requirements::javascript($polyfillUrl . join('%2C', $polyfills));

		$manifest = self::getManifest();

		if (!empty($manifest['entrypoints'])) {
			Requirements::set_force_js_to_bottom(true);

			foreach ($manifest['entrypoints'] as $name => $entrypoint) {
				if (array_key_exists('js', $entrypoint)) {
					foreach ($entrypoint['js'] as $path) {
						Requirements::themedJavascript(self::getResourcePath($path));
					}
				}
			}
		}
	}

	/**
	 * Returns a path to a resource file
	 */
	public static function getAsset($path) {
		return self::getResourcePath($path);
	}

	/**
	 * Returns a file's actual content (only really useful for SVGs)
	 */
	public static function getAssetInline($path) {
		return file_get_contents(self::getAbsResourcePath($path));
	}

	/**
	 * Returns an SVG icon based on a spritemap file
	 *
	 * Requires this webpack plugin to be used and configured properly
	 * https://github.com/cascornelissen/svg-spritemap-webpack-plugin
	 *
	 * The viewBox attribute is extracted from the spritemap symbol
	 * and applied to the parent <svg> so it's easier to work with in CSS
	 */
	public static function getAssetIcon($name) {
		$spritemapPath = self::getAsset(self::$spritemapFile);
		$symbols = self::getAssetIconSymbols();
		$symbol = $symbols["sprite-$name"];

		return sprintf('
            <svg data-icon="%s" aria-hidden="true" viewBox="%s">
                <use xlink:href="%s#sprite-%s"></use>
            </svg>
        ', $name, $symbol->getAttribute('viewBox'), $spritemapPath, $name);
	}

	/**
	 * Loads all the symbols (icons) in the SVG spritemap file
	 *
	 * Cached on the first access so we don't continuously parse the same SVG file
	 */
	private static function getAssetIconSymbols() {
		if (!is_null(self::$assetIconSymbolsCache)) {
			return self::$assetIconSymbolsCache;
		}

		$spritemap = new \DomDocument;
		$spritemap->validateOnParse = true;
		$spritemap->load(self::getAbsResourcePath(self::$spritemapFile));
		$symbolNodes = $spritemap->getElementsByTagName('symbol');
		$symbols = [];

		foreach ($symbolNodes as $node) {
			$symbols[$node->getAttribute('id')] = $node;
		}

		self::$assetIconSymbolsCache = $symbols;

		return self::$assetIconSymbolsCache;
	}

	/**
	 * Loads the manifest.json file so the paths are available for Requirements
	 *
	 * Requires this webpack plugin to be used and configured properly
	 * https://github.com/webdeveric/webpack-assets-manifest
	 */
	private static function getManifest() {
		if (!is_null(self::$manifestCache)) {
			return self::$manifestCache;
		}

		$manifestFile = self::getAbsResourcePath('manifest.json');

		if (file_exists($manifestFile)) {
			$contents = json_decode(file_get_contents($manifestFile), true);
			self::$manifestCache = $contents;
		} else {
			self::$manifestCache = false;
		}

		return self::$manifestCache;
	}

	/**
	 * Returns a path to a file relative to the theme's resources folder (usually the dist or output directory)
	 */
	private static function getResourcePath($path) {
		return Path::join(
			'/',
			ManifestFileFinder::RESOURCES_DIR,
			'app',
			'client',
			'dist',
			$path
		);
	}

	/**
	 * Returns an absolute path to a file on disk
	 */
	private static function getAbsResourcePath($path) {
		$resourcePath = Director::makeRelative(self::getResourcePath($path));
		return Director::getAbsFile($resourcePath);
	}
}
