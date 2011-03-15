<?php

/**
* @package   s9e\Toolkit
* @copyright Copyright (c) 2010 The s9e Authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\Toolkit\TextFormatter;

abstract class PluginConfig
{
	/**
	* @var ConfigBuilder
	*/
	protected $cb;

	/**
	* @var integer Maximum amount of matches to process - used by the parser when running the global
	*              regexp
	*/
	public $limit = 1000;

	/**
	* @var string  What to do if the number of matches exceeds the limit. Values can be: "ignore"
	*              (ignore matches past limit), "warn" (same as "ignore" but also log a warning) and
	*              "abort" (abort parsing)
	*/
	public $limitAction = 'ignore';

	/**
	* @var integer Order in which each parser is executed. A lower value gives this plugin's parser
	*              a higher priority
	*/
	public $parsingOrder = 10000;

	public function __construct(ConfigBuilder $cb)
	{
		$this->cb = $cb;
		$this->setUp();
	}

	protected function setUp() {}

	/**
	* @return array
	*/
	public function getConfig();
}