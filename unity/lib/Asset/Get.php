<?php

namespace Unity\Asset;

/**
 * Class Get
 * @package Unity\Asset
 */
class Get {
	public $head;
	public $footer;

	private $_asset;

	public function __construct(\Unity\Asset $asset) {
		unset($this->head, $this->footer);

		$this->_asset = $asset;
	}

	public function __get($method) {

		$html = '';
		switch ($method) {
			case 'head':
				$content = '';
				foreach ($this->_asset->__paths as $path) {
					if (substr($path, -4) == '.css') {
						$content .= file_get_contents($path);
					}
				}

				$html .= '<link href="' . config()->asset_url() . 'css/bundle/base.css?v=' . $this->_asset->version . '" rel="stylesheet">';
				file_put_contents(config()->asset_dir() . 'css/bundle/base.css', $content);
				break;
			case 'footer':
				$content = '';
				foreach ($this->_asset->__paths as $path) {
					if (substr($path, -3) == '.js') {
						$content .= file_get_contents($path);
					}
				}

				$html .= '<script src="' . config()->asset_url() . 'js/bundle/base.js?v=' . $this->_asset->version . '"></script>';
				file_put_contents(config()->asset_dir() . 'js/bundle/base.js', $content);
				break;
		}

		return $html;
	}
}