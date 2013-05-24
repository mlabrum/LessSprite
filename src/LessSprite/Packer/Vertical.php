<?php

namespace LessSprite\Packer;

class Vertical implements PackerInterface {

    /**
     * Stores the width of the resulting image
     * @var int
     */
    private $real_width;

    /**
     * Stores the height of the resulting image
     * @var int
     */
    private $real_height;

    /**
     * Stores the user width of the resulting image
     * @var int
     */
    private $width;

    /**
     * Stores the user height of the resulting image
     * @var int
     */
    private $height;

    /**
     * Adds a sprite into the packed box taking into account padding
     * @param \LessSprite\Sprite $sprite
     */
    public function add(\LessSprite\Sprite $sprite) {
        // Add an image into the "box", taking into account padding

        $position_in_box = $sprite->getPadding();
        $image_size = $sprite->getImageRealSize();

        // Set the top offset for the sprite
        $sprite->real_top = $this->real_height + $position_in_box->top;

        // Set the new height for our image
        $this->real_height = $sprite->real_top + $image_size['height'] + $position_in_box->bottom;

        // Left padding
        $sprite->real_left = $position_in_box->left;

        // The width of the new image
        $width = $sprite->real_left + $image_size['width'] + $position_in_box->right;

        // Update the width
        if ($this->real_width < $width) {
            $this->real_width = $width;
        }

        // Calculate the user specified size of the image
        $box_size = $sprite->getBoxSize();

        // Left offset
        $sprite->left = $position_in_box->left;

        // Top offset
        $sprite->top = $this->height + $position_in_box->top;

        if ($this->width < $box_size['width']) {
            $this->width = $box_size['width'];
        }

        $this->height += $box_size['height'];
    }

    /**
     * Returns the real width of the resulting image
     * @return int
     */
    public function getRealTotalWidth() {
        return $this->real_width;
    }

    /**
     * Returns the real height of the resulting image
     * @return int
     */
    public function getRealTotalHeight() {
        return $this->real_height;
    }

    /**
     * Returns the width of the image (user specified)
     */
    public function getTotalWidth() {
        return $this->width;
    }

    /**
     * Returns the height of the image (user specified)
     */
    public function getTotalHeight() {
        return $this->height;
    }

}