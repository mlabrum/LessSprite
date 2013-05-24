<?php

namespace LessSprite\Parser;

class Padding {

    /**
     * Top Padding
     * @var int 
     */
    public $top = 0;

    /**
     * Right Padding
     * @var int 
     */
    public $right = 0;

    /**
     * Bottom Padding
     * @var int
     */
    public $bottom = 0;

    /**
     * left Padding
     * @var int
     */
    public $left = 0;

    /**
     * Converts LessPHP padding into an object
     * @param array $args
     */
    public function __construct($args) {
        if (!empty($args)) {
            if ($args[0] == "list") {
                if (isset($args[2])) {
                    $props = $args[2];
                    switch (count($props)) {

                        // (top+bottom) and (right+left) paddings are defined
                        case 2:
                            $this->top = $this->bottom = $props[0][1];
                            $this->left = $this->right = $props[1][1];
                            break;

                        // top, (right+left) and bottom paddings
                        case 3:
                            $this->top = $props[0][1];
                            $this->left = $this->right = $props[1][1];
                            $this->bottom = $props[2][1];
                            break;

                        // top right bottom left are defined
                        case 4:
                            $this->top = $props[0][1];
                            $this->right = $props[1][1];
                            $this->bottom = $props[2][1];
                            $this->left = $props[3][1];
                            break;
                    }
                }

                // All paddings are exactly the same
            } elseif ($args[0] == "number") {
                $this->top = $this->bottom = $this->left = $this->right = (int) $args[1];
            } elseif (is_array($args[0])) {
                $props = $args[0];
                switch (count($props)) {

                    // (top+bottom) and (right+left) paddings are defined
                    case 2:
                        $this->top = $this->bottom = $props[0];
                        $this->left = $this->right = $props[1];
                        break;

                    // top, (right+left) and bottom paddings
                    case 3:
                        $this->top = $props[0];
                        $this->left = $this->right = $props[1];
                        $this->bottom = $props[2];
                        break;

                    // top right bottom left are defined
                    case 4:
                        $this->top = $props[0];
                        $this->right = $props[1];
                        $this->bottom = $props[2];
                        $this->left = $props[3];
                        break;
                }
            }
        }
    }

}