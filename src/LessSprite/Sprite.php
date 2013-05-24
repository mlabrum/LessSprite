<?php

namespace LessSprite;

class Sprite {

    /**
     * Holds the arguments
     * @var Parser\SpriteArgs 
     */
    private $args;

    /**
     * Contains the padding for the sprite
     * @var Parser\Padding 
     */
    private $padding;

    /**
     * The real width of the image
     * @var int 
     */
    private $real_image_width = 0;

    /**
     * The real height of the image
     * @var int
     */
    private $real_image_height = 0;

    /**
     * The user supplied width of the image
     * @var int
     */
    private $image_width = 0;

    /**
     * The user supplied height of the image
     * @var type 
     */
    private $image_height = 0;

    /**
     * The mimetype of the image
     * @var string 
     */
    private $mime;

    /**
     * The sprites position within the image
     * @var int 
     */
    public $real_top = 0;

    /**
     * The sprites position within the image
     * @var int 
     */
    public $real_left = 0;

    /**
     * User specified top
     * @var int
     */
    public $top = 0;

    /**
     * User specified left
     * @var int
     */
    public $left = 0;

    /**
     * Initializes and loads information about the sprite
     * @param \LessSprite\Parser\SpriteArgs $args
     * @param \LessSprite\Parser\Padding $padding
     */
    public function __construct(Parser\SpriteArgs $args, Parser\Padding $padding) {
        $this->args = $args;
        $this->padding = $padding;

        $info = getimagesize($this->getImagePath());
        $this->image_width = $this->real_image_width = $info[0];
        $this->image_height = $this->real_image_height = $info[1];
        $this->mime = $info['mime'];

        if (!is_null($this->args->width)) {
            $this->image_width = $this->args->width;
        }

        if (!is_null($this->args->height)) {
            $this->image_height = $this->args->height;
        }
    }

    /**
     * Returns the size of the containing box (padding etc)
     */
    public function getBoxSize() {
        return Array("width" => $this->padding->left + $this->image_width + $this->padding->right, "height" => $this->padding->top + $this->image_height + $this->padding->bottom);
    }

    /**
     * Fetches the position of the image within the bounding box
     * @return Parser\Padding
     */
    public function getPadding() {
        return $this->padding;
    }

    /**
     * Fetches the image size (as defined by the user or automaticly guessed)
     * This is used to calculate the offset to pass into css
     * @return array
     */
    public function getImageSize() {
        return Array("width" => $this->image_width, "height" => $this->image_height);
    }

    /**
     * Fetches the real size of the image from the image file
     * This is used to calculate the image to output
     * @return array
     */
    public function getImageRealSize() {
        return array("width" => $this->real_image_width, "height" => $this->real_image_height);
    }

    /**
     * Fetches the image from the file system and loads it with GD
     * @return resource 
     */
    public function getImage() {
        switch ($this->mime) {
            case "image/png" : return imagecreatefrompng($this->getImagePath());
            case "image/gif" : return imagecreatefromgif($this->getImagePath());
            case "image/jpeg" : return imagecreatefromjpeg($this->getImagePath());
        }

        return false;
    }

    /**
     * Fetches the full path to the image
     * @return string
     */
    public function getImagePath() {
        return $this->args->imagesrc;
    }

    /**
     * Returns a unique name for the sprite from all the attributes
     */
    public function getUniqueName() {
        return md5(var_export($this, true));
    }

}
