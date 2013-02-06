<?php

namespace LessSprite;

class Plugin{
	
	/**
	 * Holds the padding for the next sprite
	 * @var Parser\Padding 
	 */
	private $padNext = null;
	
	/**
	 * Contains an array of sprite groups
	 * @var array
	 */
	private $spriteGroups = Array();
	
	/**
	 * Parses a sprite
	 * Takes a lesscss format of
	 * sprite('sprite-name', 'relative/to/parsingless/imagefile' [, width, height])
	 * 
	 * Width and height are optional unless you're using a 2x image then they will be mandatory to generate the background-size of the sprite correctly
	 * 
	 * @param array $unparsed_args
	 * @param \lessc $less
	 * @return string
	 */
	public function addSprite($unparsed_args, $less){
		$args			= new Parser\SpriteArgs($unparsed_args);
		$padding		= $this->padNext;
		$this->padNext	= null; //reset padNext
		
		/*
			We need to handle both normal sprites and larger @2x sprites
			this means adding the background-size property to translate it back to 1x
		 */
		// TODO
		// background: url(sprite.png) no-repeat -100px 0;
		// Set Background-size to width and height of sprite
		return "a";
	}
	
	/**
	 * Tells the parser to add padding to the next sprite image created
	 * 
	 * Takes the lesscss format of
	 * sprite-pad(0px 0px 0px 0px)
	 *  
	 * @param array $unparsed_args
	 * @param \lessc $less
	 * @return string
	 */
	public function padNext($unparsed_args, $less){
		$this->padNext = new Parser\Padding($unparsed_args);		
		
		return "0px";
	}
	
	/**
	 * This calculates the background size of the processed sprite
	 * 
	 * Takes the lesscss format of
	 * sprite-background-size(sprite-name)
	 * 
	 * NOTE: This must be called after all the sprites have been added, otherwise you'll get an incorrect background size
	 * 
	 * @param array $unparsed_args
	 */
	public function backgroundSize($unparsed_args){
		
	}
	
	/**
	 * Registers the plugin with lessphp
	 * @param \lessc $less
	 */
	public function register(\lessc $less){
		$less->registerFunction("sprite", Array($this, 'addSprite'));
		$less->registerFunction("sprite-pad", Array($this, 'padNext'));
		$less->registerFunction("sprite-background-size", Array($this, 'backgroundSize'));
		
	}
	
}