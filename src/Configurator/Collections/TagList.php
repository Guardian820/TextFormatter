<?php

/**
* @package   s9e\TextFormatter
* @copyright Copyright (c) 2010-2014 The s9e Authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\TextFormatter\Configurator\Collections;

use s9e\TextFormatter\Configurator\Validators\TagName;

/**
* Hosts a list of tag names. The config array it returns contains the names, deduplicated and sorted
*/
class TagList extends NormalizedList
{
	/**
	* {@inheritdoc}
	*/
	public function normalizeValue($attrName)
	{
		return TagName::normalize($attrName);
	}

	/**
	* {@inheritdoc}
	*/
	public function asConfig()
	{
		$list = array_unique($this->items);
		sort($list);

		return $list;
	}
}