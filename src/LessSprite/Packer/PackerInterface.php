<?php

namespace LessSprite\Packer;

interface PackerInterface{
	
	/**
	 * Adds a block into the packer and sets its x, y location
	 * @param \LessSprite\Sprite $sprite
	 */
	public function add(\LessSprite\Sprite $sprite);
	
	/**
	 * Fetches the total width of the resulting image
	 * @return int
	 */
	public function getRealTotalWidth();
	
	/**
	 * Fetches the total height of the resulting image
	 * @return int
	 */
	public function getRealTotalHeight();
	
	/**
	 * Fetches the total width of all the sprites
	 */
	public function getTotalWidth();
	
	/**
	 * Fetches the total height of all the sprites
	 */
	public function getTotalheight();
	
}