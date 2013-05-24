<?php

namespace LessSprite;

class Plugin {

    /**
     * Holds the padding for the next sprite
     * @var Parser\Padding 
     */
    private $padNext = null;

    /**
     * Holds the last sprite
     * @var Sprite
     */
    private $lastSprite = null;

    /**
     * Contains an array of sprite groups
     * @var array
     */
    private $spriteGroups = Array();

    /**
     * Base path of images
     * @var string 
     */
    public $image_base_path = "";

    /**
     * Http base path of images
     * @var string
     */
    public $http_base_path = "";

    /**
     * Default padding spaces
     * 
     * Takes the lesscss format of
     * array(0px,0px,0px,0px)
     * 
     * @var array 
     */
    public $padding_default = null;

    /**
     * Add image size?
     * @var type 
     */
    public $add_image_size = false;

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
    public function addSprite($unparsed_args, $less) {
        $args = new Parser\SpriteArgs($unparsed_args);
        $padding = is_null($this->padNext) ? new Parser\Padding(array($this->padding_default)) : $this->padNext;
        $this->padNext = null; //reset padNext
        // Set the base path
        $args->imagesrc = ($this->image_base_path != '' ? $this->image_base_path : "") . $args->imagesrc;

        if (!isset($this->spriteGroups[$args->spritename])) {
            $this->spriteGroups[$args->spritename] = new SpriteGroup($args->spritename, $this, new Packer\Vertical());
        }

        $this->lastSprite = $sprite = new Sprite($args, $padding);

        // Add the sprite into the group and get the positioning information
        $positioning = $this->spriteGroups[$args->spritename]->add($sprite);

        $posTop = -$positioning['top'] + $padding->top;
        $posLeft = -$positioning['left'] + $padding->left;

        $css = 'background: url(' . $this->http_base_path . $this->spriteGroups[$args->spritename]->getRelativeFileName() . ') no-repeat ' . ($posLeft ? $posLeft . 'px' : 0) . " " . ($posTop ? $posTop . 'px' : 0);

        if ($this->add_image_size) {
            $sizes = $sprite->getImageSize();
            $css .= '; width: ' . $sizes['width'] . 'px; height: ' . $sizes['height'] . 'px';
        }

        return $css;
    }

    /**
     * Parses a wrapped sprite
     * Takes a lesscss format of
     * sprite-wrap('sprite-name', 'relative/to/parsingless/imagefile' [, width, height, top position, left position])
     * 
     * Width and height are optional unless you're using a 2x image then they will be mandatory to generate the background-size of the sprite correctly
     * Top and Left position are optional too, since you are not centering the wrapped sprite
     * 
     * @param array $unparsed_args
     * @param \lessc  $less
     * @return string
     */
    public function addSpriteWrap($unparsed_args, $less) {
        $args = new Parser\SpriteWrapArgs($unparsed_args);

        $css = $this->addSprite($unparsed_args, $less);

        $sizes = $this->lastSprite->getImageSize();

        $toppos = ($args->toppos ? $args->toppos : '50%');
        $leftpos = ($args->leftpos ? $args->leftpos : '50%');

        $mtop = round($sizes['height'] / 2, 3);
        $mleft = round($sizes['width'] / 2, 3);
        
        
        $css .= '; position: absolute; top: ' . $toppos . '; left: ' . $leftpos . '; margin-top: -' . $mtop . 'px; margin-left: -' . $mleft . 'px';

        return $css;
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
    public function padNext($unparsed_args, $less) {
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
    public function backgroundSize($unparsed_args) {
        if (isset($unparsed_args[2][0])) {
            $name = $unparsed_args[2][0];

            if (isset($this->spriteGroups[$name])) {
                $size = $this->spriteGroups[$name]->getBackgroundSize();

                // Generate the sprite group
                $this->spriteGroups[$name]->generate();
                return $size['width'] . "px " . $size['height'] . "px";
            } else {
                // Error
            }
        } else {
            // Error
        }
    }

    /**
     * Registers the plugin with lessphp
     * @param \lessc $less
     */
    public function register(\lessc $less) {
        $less->registerFunction("sprite", Array($this, 'addSprite'));
        $less->registerFunction("sprite-wrap", Array($this, 'addSpriteWrap'));
        $less->registerFunction("sprite-pad", Array($this, 'padNext'));
        $less->registerFunction("sprite-background-size", Array($this, 'backgroundSize'));
    }

}