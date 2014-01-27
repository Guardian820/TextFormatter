<?php

/**
* @package   s9e\TextFormatter
* @copyright Copyright (c) 2010-2014 The s9e Authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\TextFormatter\Plugins;

use InvalidArgumentException;
use RuntimeException;
use s9e\TextFormatter\Configurator;
use s9e\TextFormatter\Configurator\ConfigProvider;
use s9e\TextFormatter\Configurator\Helpers\ConfigHelper;
use s9e\TextFormatter\Configurator\Validators\AttributeName;
use s9e\TextFormatter\Configurator\Validators\TagName;

abstract class ConfiguratorBase implements ConfigProvider
{
	/**
	* @var Configurator
	*/
	protected $configurator;

	/**
	* @var mixed Ignored if FALSE. Otherwise, this plugin's parser will only be executed if this
	*            string is present in the original text
	*/
	protected $quickMatch = false;

	/**
	* @var integer Maximum amount of matches to process - used by the parser when running the global
	*              regexp
	*/
	protected $regexpLimit = 10000;

	/**
	* @var string  What to do if the number of matches exceeds the limit. Values can be: "ignore"
	*              (ignore matches past limit), "warn" (same as "ignore" but also log a warning) and
	*              "abort" (abort parsing)
	*/
	protected $regexpLimitAction = 'warn';

	/**
	* @param Configurator $configurator
	* @param array        $overrideProps Properties of the plugin will be overwritten with those
	*/
	final public function __construct(Configurator $configurator, array $overrideProps = [])
	{
		$this->configurator = $configurator;

		foreach ($overrideProps as $k => $v)
		{
			$methodName = 'set' . ucfirst($k);

			if (method_exists($this, $methodName))
			{
				$this->$methodName($v);
			}
			elseif (property_exists($this, $k))
			{
				$this->$k = $v;
			}
			else
			{
				throw new RuntimeException("Unknown property '" . $k . "'");
			}
		}

		$this->setUp();
	}

	/**
	* Executed by this plugin's constructor
	*/
	protected function setUp()
	{
	}

	/**
	* Finalize this plugin's configuration
	*
	* Executed by the configurator whenever the tags' config must be in a usable state:
	*  - before the parser's config is generated
	*  - before the renderer's stylesheet is generated
	*  - before HTML5 rules are generated
	*
	* As such, this method may be called multiple times during configuration
	*/
	public function finalize()
	{
	}

	/**
	* @return array|bool This plugin's config, or FALSE to disable this plugin
	*/
	public function asConfig()
	{
		$properties = get_object_vars($this);
		unset($properties['configurator']);

		return ConfigHelper::toArray($properties);
	}

	/**
	* Return a list of base properties meant to be added to asConfig()'s return
	*
	* NOTE: this final method exists so that the plugin's configuration can always specify those
	*       base properties, even if they're omitted from asConfig(). Going forward, this ensure
	*       that new base properties added to ConfiguratorBase appear in the plugin's config without
	*       having to update every plugin
	*
	* @return array
	*/
	final public function getBaseProperties()
	{
		return [
			'className'         => preg_replace('/Configurator$/', 'Parser', get_class($this)),
			'quickMatch'        => $this->quickMatch,
			'regexpLimit'       => $this->regexpLimit,
			'regexpLimitAction' => $this->regexpLimitAction
		];
	}

	/**
	* Return this plugin's JavaScript parser
	*
	* This is the base implementation, meant to be overridden by custom plugins. By default it
	* returns the Parser.js file from stock plugins' directory, if available
	*
	* @return string|null JavaScript source, or NULL if no JS parser is available
	*/
	public function getJSParser()
	{
		$className = get_class($this);
		if (substr($className, 0 , 26) === 's9e\\TextFormatter\\Plugins\\')
		{
			$p = explode('\\', $className);
			$pluginName = $p[3];

			$filepath = __DIR__ . '/' . $pluginName . '/Parser.js';
			if (file_exists($filepath))
			{
				return file_get_contents($filepath);
			}
		}

		return null;
	}

	/**
	* Return the tag associated with this plugin, if applicable
	*
	* @return Tag
	*/
	public function getTag()
	{
		if (!isset($this->tagName))
		{
			throw new RuntimeException('No tag associated with this plugin');
		}

		return $this->configurator->tags[$this->tagName];
	}

	//==========================================================================
	// Setters
	//==========================================================================

	/**
	* Disable quickMatch
	*
	* @return void
	*/
	public function disableQuickMatch()
	{
		$this->quickMatch = false;
	}

	/**
	* Set $this->attrName with given attribute name, normalized
	*
	* @param  string $attrName New attribute name
	* @return void
	*/
	protected function setAttrName($attrName)
	{
		if (!property_exists($this, 'attrName'))
		{
			throw new RuntimeException("Unknown property 'attrName'");
		}

		$this->attrName = AttributeName::normalize($attrName);
	}

	/**
	* Set the quickMatch string
	*
	* @param  string $quickMatch
	* @return void
	*/
	public function setQuickMatch($quickMatch)
	{
		if (!is_string($quickMatch))
		{
			throw new InvalidArgumentException('quickMatch must be a string');
		}

		$this->quickMatch = $quickMatch;
	}

	/**
	* Set the maximum number of regexp matches
	*
	* @param  integer $limit
	* @return void
	*/
	public function setRegexpLimit($limit)
	{
		$limit = filter_var($limit, FILTER_VALIDATE_INT, [
			'options' => ['min_range' => 1]
		]);

		if (!$limit)
		{
			throw new InvalidArgumentException('regexpLimit must be a number greater than 0');
		}

		$this->regexpLimit = $limit;
	}

	/**
	* Set the action to perform when the regexp limit is broken
	*
	* @param  string $action
	* @return void
	*/
	public function setRegexpLimitAction($action)
	{
		if ($action !== 'ignore'
		 && $action !== 'warn'
		 && $action !== 'abort')
		{
			throw new InvalidArgumentException("regexpLimitAction must be any of: 'ignore', 'warn' or 'abort'");
		}

		$this->regexpLimitAction = $action;
	}

	/**
	* Set $this->tagName with given tag name, normalized
	*
	* @param  string $tagName New tag name
	* @return void
	*/
	protected function setTagName($tagName)
	{
		if (!property_exists($this, 'tagName'))
		{
			throw new RuntimeException("Unknown property 'tagName'");
		}

		$this->tagName = TagName::normalize($tagName);
	}
}