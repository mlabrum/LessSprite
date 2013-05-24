<?php

namespace LessSprite\Parser;

class SpriteArgs {

    /**
     * Name of the sprite group
     * @var string
     */
    public $spritename;

    /**
     * Relative path of the image
     * @var string 
     */
    public $imagesrc;

    /**
     * The width of the image
     * @var int
     */
    public $width;

    /**
     * The height of the image
     * @var int
     */
    public $height;

    /**
     * Parses lessphp arguments into an object
     * @param array $args
     */
    public function __construct($args) {
        if ($args[0] == 'list') {
            $items = $args[2];
            $mapping = Array('spritename', 'imagesrc', 'width', 'height');

            foreach ($items as $i => $item) {
                $name = $mapping[$i];
                if (is_array($item[2])) {
                    $this->$name = $item[2][0]; // String
                } else {
                    $this->$name = $item[1]; // Int
                }
            }
        }
    }

}