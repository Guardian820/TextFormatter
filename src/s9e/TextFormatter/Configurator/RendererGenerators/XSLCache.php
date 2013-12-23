<?php

/**
* @package   s9e\TextFormatter
* @copyright Copyright (c) 2010-2013 The s9e Authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\TextFormatter\Configurator\RendererGenerators;

use InvalidArgumentException;
use s9e\TextFormatter\Configurator\Rendering;
use s9e\TextFormatter\Renderers\XSLCache as XSLCacheRenderer;

class XSLCache extends XSLT
{
	/**
	* @var string Path to the directory in which the stylesheet files will be saved
	*/
	protected $cacheDir;

	/**
	* Constructor
	*
	* @param  string $cacheDir Path to the directory in which the stylesheet files will be saved
	* @return void
	*/
	public function __construct($cacheDir)
	{
		$this->cacheDir = realpath($cacheDir);

		if ($this->cacheDir === false)
		{
			throw new InvalidArgumentException("Path '" . $cacheDir . "' is invalid");
		}
	}

	/**
	* {@inheritdoc}
	*/
	public function getRenderer(Rendering $rendering)
	{
		$xsl = $this->getXSL($rendering);
		$md5 = md5($xsl);

		$filepath = $this->cacheDir . '/xslcache.' . $md5 . '.xsl';
		file_put_contents($filepath, $xsl);

		return new XSLCacheRenderer($filepath);
	}
}