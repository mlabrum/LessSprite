LessSprite
==========

Simplifies generating sprite images on PHP -only- environments, using LessPHP as parser and no need for Phyton/Ruby/Java scripts.

Simple collection 
-------

On your .less use the following function

    .ico {
        &.facebook {
            background: sprite('sprites', 'sprites/icon-facebook.png');
        }
        &.twitter {
            background: sprite('sprites', 'sprites/icon-twitter.png');
        }
    }
    
And no more than this, will be translated to:


    .ico.twitter {
        background: url(sprites.1369427150.png) no-repeat 0 0; display: block; width: 20px; height: 20px;
    }
    .ico.instagram {
        background: url(sprites.1369427150.png) no-repeat 0 -22px; display: block; width: 20px; height: 20px;
    }
    
Simple not? 
- Proxy Cache controled
- Auto "display: block"'ed
- Auto dimensions from image

Wrapped Sprite
-------

So your icon need to be fixed on center of a element?  Yes we can!

    .social-media {
        .column {
            position: relative;
            width: 40px;
            height: 40px;
        }
        .ico {
            &.facebook {
                background: sprite-wrap('sprites', 'sprites/icon-facebook.png');
            }
            &.twitter {
                background: sprite-wrap('sprites', 'sprites/icon-facebook.png');
            }
        }
    }

Wiil be the translated to:

    .social-media .column {
        position: relative;
        width: 40px;
        height: 40px;
    }
    .social-media .ico.facebook {
        background: url(sprites.1369427733.png) no-repeat 0 0; display: block; width: 20px; height: 20px; position: absolute; top: 50%; left: 50%; margin-top: -10px; margin-left: -10px;
    }
    .social-media .ico.twitter {
        background: url(sprites.1369427733.png) no-repeat 0 -22px; display: block; width: 20px; height: 20px; position: absolute; top: 50%; left: 50%; margin-top: -10px; margin-left: -10px;
    }