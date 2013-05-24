<?php

namespace LessSprite\Parser;

class SpriteWrapArgs extends SpriteArgs {

    /**
     * The top position to wrap
     * @var string
     */
    public $toppos;
    
    /**
     * The left position to wrap
     * @var string
     */
    public $leftpos;
    
    protected function readMapping() {
        $array = parent::readMapping();
        $array = array_merge($array, array('toppos', 'leftpos'));
        return $array;
    }

}

