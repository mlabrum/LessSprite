<?php

namespace LessSprite;

class Plugin {

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
        $args->imagesrc = ($this->image_base_path != '' ? $this->image_base_path . "/" : "") . $args->imagesrc;

        if (!isset($this->spriteGroups[$args->spritename])) {
            $this->spriteGroups[$args->spritename] = new SpriteGroup($args->spritename, new Packer\Vertical());
        }

        // Add the sprite into the group and get the positioning information
        $positioning = $this->spriteGroups[$args->spritename]->add(new Sprite($args, $padding));

        return "url(" . $this->http_base_path . $this->spriteGroups[$args->spritename]->getRelativeFileName() . ") no-repeat " . (-$positioning['left'] + $padding->left) . "px " . (-$positioning['top'] + $padding->top) . "px";
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
        $less->registerFunction("sprite-pad", Array($this, 'padNext'));
        $less->registerFunction("sprite-background-size", Array($this, 'backgroundSize'));
    }

}