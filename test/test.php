<?php

// Load the composer autoloader
require_once("../vendor/autoload.php");

// Initialize less
$less = new lessc;

// Initialize the plugin
$lessPlugin = new LessSprite\Plugin();

$lessPlugin->image_base_path = __DIR__;
$lessPlugin->http_base_path = "images/";

// Register the spriting method
$lessPlugin->register($less);


echo "<html><head><style>";
echo $less->compile("
	
	#test-normal-sprite{
	
		.tick-icon{
			background: sprite('default', 'images/icon_48_tick.gif'); 
			width: 48px;
			height: 48px;
		}
		
		.application-view-icon{
			background: sprite('default', 'images/application_view_list.png'); 
			width: 16px;
			height: 16px;
		}
		
		.tick-icon, .application-view-icon{
			background-size: sprite-background-size('default');
		}

	}

	#test-sprite-pad{
		.tick-icon{
			padding: sprite-pad(0px 0px 0px 20px); // Pad 20px left
			background: sprite('default-pad', 'images/icon_48_tick.gif'); 
			width: 68px;
			height: 48px;
		}
		
		.application-view-icon{
			padding: sprite-pad(10px 0px 10px 0px); // Pad 10px bottom and 10px top
			background: sprite('default-pad', 'images/application_view_list.png'); 
			width: 16px;
			height: 36px;
		}
		
		.tick-icon, .application-view-icon{
			background-size: sprite-background-size('default-pad');
		}
	}

	#test-sprite-2x{
		.other-icon{
			background: sprite('default-2x', 'images/mark@2x.png', 16px, 16px); 
			width: 16px;
			height: 16px;
		}
		
		.other-icon2{
			background: sprite('default-2x', 'images/cloud@2x.png', 16px, 16px); 
			width: 16px;
			height: 16px;
		}

		.other-icon, .other-icon2{
			background-size: sprite-background-size('default-2x');
		}

	}

	
");

echo "</style></head><body>";

?>
	<div id="test-normal-sprite">
		Normal Sprites
		<div class="tick-icon"></div>
		<div class="application-view-icon"></div>
	</div>
	<br style="reset:both"/>
	<div id="test-sprite-pad">
		Padded Sprites
		<div class="tick-icon"></div>
		<div class="application-view-icon"></div>
	</div>
	<div id="test-sprite-2x">
		2x Sprites
		<div class="other-icon"></div>
		<br/>
		<div class="other-icon2"></div>
	</div>
	
</body>
</html>