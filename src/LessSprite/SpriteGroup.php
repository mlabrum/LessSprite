<?php

namespace LessSprite;

class SpriteGroup {

    /**
     * The packer to use to make the sprite
     * @var Packer\PackerInterface $packer
     */
    private $packer;

    /**
     * The name of the sprite group
     * @var string
     */
    private $name;

    /**
     * List of sprites to include in the image
     * @var array
     */
    private $sprites;

    /**
     * Has this sprite group been generated yet
     * @var boolean
     */
    private $hasGenerated = false;

    public function __construct($name, Packer\PackerInterface $packer) {
        $this->name = $name;
        $this->packer = $packer;
    }

    /**
     * Adds a sprite to the image and returns the positioning information
     * @param \LessSprite\Sprite $sprite
     * @return array
     */
    public function add(Sprite $sprite) {
        $sprite_key = $sprite->getUniqueName();

        if (!isset($this->sprites[$sprite_key])) {
            $this->packer->add($sprite);
            $this->sprites[$sprite_key] = $sprite;
        } else {
            $sprite = $this->sprites[$sprite_key];
        }

        $sprite_size = $sprite->getBoxSize();

        return Array("top" => $sprite->top, "left" => $sprite->left, "width" => $sprite_size['width'], "height" => $sprite_size['height']);
    }

    /**
     * Fetches the completed sprite size
     */
    public function getBackgroundSize() {
        return Array("width" => $this->packer->getTotalWidth(), "height" => $this->packer->getTotalHeight());
    }

    public function getRelativeFileName() {
        return $this->name . ".png";
    }

    public function getBaseDir() {
        $sprites = array_values($this->sprites);
        return dirname($sprites[0]->getImagePath()) . "/";
    }

    /**
     * Generates and outputs the sprite image
     */
    public function generate() {
        if (!$this->hasGenerated) {
            $sprites = array_values($this->sprites);
            $base_path = $this->getBaseDir();

            $canvas = imagecreatetruecolor($this->packer->getRealTotalWidth(), $this->packer->getRealTotalHeight());

            // Transparent image support
            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);
            $trans_color = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
            imagefill($canvas, 0, 0, $trans_color);

            // Copy the sprites onto the canvas
            foreach ($sprites as $sprite) {
                if ($i = $sprite->getImage()) {
                    $size = $sprite->getImageRealSize();
                    imagecopy($canvas, $i, $sprite->real_left, $sprite->real_top, 0, 0, $size['width'], $size['height']);
                }
            }

            $this->hasGenerated = true;

            // Output the image
            return imagepng($canvas, $base_path . $this->getRelativeFileName());
        }
    }

    /**
     * Generate the sprite on destruct
     */
    public function __destruct() {
        if (!empty($this->sprites)) {
            $this->generate();
        }
    }

}

