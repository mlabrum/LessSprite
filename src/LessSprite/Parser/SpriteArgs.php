<?php

namespace LessSprite\Parser;

class SpriteArgs{
	public $name;
	public $imagesrc;
	
	public $_width;
	public $_height;	
	
	public function __construct($args){
		if($args[0] == 'list'){
			$items		= $args[2];
			$mapping	= Array('name', 'imagesrc', '_width', '_height');

			foreach($items as $i => $item){
				$name = $mapping[$i];
				if(is_array($item[2])){
					$this->$name = $item[2][0]; // String
				}else{
					$this->$name = $item[1]; // Int
				}
			}
		}
	}
	
	
	public function __get($name){
		if($name == 'width' || $name == 'height'){
			if(empty($this->_width) || empty($this->_height)){
				// Automaticly calculate
			}
			
			
			if($name == 'width'){
				return $this->_width;
			}elseif($name == 'height'){
				return $this->_height;
			}
		}
	}
	
}