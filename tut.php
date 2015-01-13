<?php
/*
Copyright: Ellen Wasbø 2013 http://svg2embr.wasbo.net

    This file is part of svg2embroidery.

    svg2embroidery is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    svg2embroidery is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with svg2embroidery.  If not, see <http://www.gnu.org/licenses/>.
*/


$nImg=0;

$textArr=array();

$folder=$_GET['name'];

$title='';



switch ($_GET['name']){

	case "trace":

		$nImg=5;

		$title='Vectorize raster-image';

		$textArr[]='You can use Inkscape to trace the outlines of an image. It\'s an advantage if the image is as clean as possible before the tracing starts. 

					Use for example Photoshop to edit the photo first to make it look sewable. In this example I will use a simple black and white image. 

					Import to Inkscape the image you want to vectorize and click the image to select it.

					</br></br>

					If you\'d like to trace the lines of a line drawing, you\'d rather use the <a href="http://online.rapidresizer.com/tracer.php" target="_blank">RapidResizer</a> online tool.';

		$textArr[]='Path -> Trace bitmap... (Ctrl+Alt+B) to find the paths of the image. Another dialogbox opens. If you have a black and white image I would 

					use the brightness cutoff. The value of the treshold would probably not be very important. Keep the default settings. If you have a greyscale image you might play with the 

					settings. You also might read <a href="http://www.inkscape.org/doc/tracing/tutorial-tracing.html\" target="_blank">this</a> 

					to learn more about how to use this tool.';

		$textArr[]='When I press update I can see nothing in the preview window, but if I press OK I do find the vectors.';

		$textArr[]='If I hide or delete the original image and visualize the path with strokes and no fill (Shift+Ctrl+F) the paths are visible.</br></br>

					These paths can now be converted to embroidery, but you should check on the stitch order (path order and direction). Take a look at 

					the <a href="index.php?page=tut&name=order">path-order tutorial</a> to see how you can check and change the stitch order.</br></br>

					If you would like to fill the paths with e.g. satin fill take a look at the <a href="index.php?page=tut&name=fill">fill tutorial</a> ';

	break;

	case "order":

		$nImg=5;

		$title='Control the stitch order';

		$textArr[]='Here I try to collect different tools that can be used to control the path order and direction to get the stitch order right.

			</br></br>

			Use the extension Visualize Path - > Number Nodes to view the order of the nodes.';

		$textArr[]='If you have a closed path you can select the node you would like to be the starting point and break the path at this node. This node will 

			then become two nodes: the first and the last node.';

		$textArr[]='Another option to visualize the direction of the path is to define the start/end marker on the stroke. These are only visible for paths that 

			are not closed. In this figure we have the opened the closed path from last figure so it is now an open path and thus the start/end marker are visible.';

		$textArr[]='The direction of a path can be reversed by Path -> Reverse.';
		$textArr[]='The svg file is an xml file and I frequently use the XML Editor (Shift+Ctrl+X) to control the order of the paths. You can also use the Layers to 
			control this, but if a layer contain several paths you might need this editor to control the order. The nice thing about 
			layers is that you can hide selected layers. So a combination of layers and controling the paths in the xml editor give you a good overview. I often edit
			the ids for the paths to more easily reorder them in the xml editor.';

	break;

	case "size":

		$nImg=2;

		$title='Control the image size and scaling';

		$textArr[]='svg2embroidery convert from svg to exp. The exp file format use a resolution of 1/10mm. If you want a 10x10 cm embroidery, define your svg 

		document size (Shift+Ctrl+D) as 1000x1000 px and create your embroidery within this page.';

		$textArr[]='';

	break;

	case "zigzag":

		$nImg=5;

		$title='Create regular zigzag';

		$textArr[]='You can use Inkscape to convert any curve to a zigzagged curve. Start by drawing one zigzag and copy this to clipboard by Ctrl+C. 

			Select the path that you want to convert to a zigzag path.';

		$textArr[]='Shift+Ctrl+7 to go to the path effect editor. Add the \'Pattern Along Path\' effect. Under \'Pattern source\' press the \'Paste Path\' 

			button. Select \'Repeated, stretched\' from \'Pattern copies\'.';

		$textArr[]='Scroll down to \'Fuse nearby ends\' and increase the number from zero to f.x. 0.1 to make the copies of the zigzag pattern fuse to a 

			continuous zigzag path.';

		$textArr[]='Now the original path (the circle) is still a circle, but with an repeated pattern effect. Shift+Ctrl+C to convert the zigzag effect 

			to a zigzag path.';

		$textArr[]='The line elements are now probably defined as bezier curves. This will work fine, but I have a feeling that this will slow down the 

			system so I use to convert all curves to straight lines. Ctrl+A to select all nodes. Convert to straight line elements by pressing the \'Make 

			the selected segments lines\'. Some extra nodes within the zigzag might occur. Delete these.';

	break;

	case "satin":

		$nImg=8;

		$title='How to create satin stitch';

		$textArr[]='This tutorial is based on the netting extension from this <a href="http://dp48069596.lolipop.jp/inkscape_script.html" target="_blank">

			web site</a>. Scroll down to find and download the netting.py and netting.inx. This extension will draw a path of straight lines through the 

			nodes of the selected path in this manner: first, last, second, second last, third.... To install an Inkscape extension follow 

			<a href="http://code.google.com/p/jessyink/wiki/Installation" target="_blank">these instructions</a>. If you would like to know more 

			on this effect read <a href="http://www.inkscapeforum.com/viewtopic.php?f=5&t=4783" target="_blank">this forum post</a>.

			</br></br>

			I want to satin stitch the border of this leaf and start by copying the inner part and reduce the size slightly. Make sure that the path is unclosed at the tip of the leaf. If the path is a closed path you need to

			 select the node where the stitching should start and find the "Break path at selected nodes"-button in the upper left corner of your screen.';

		$textArr[]='Then I use the Add Nodes extension where I can set the maximum segment length for the nodes around this path i.e. the spacing between 

			the zigzag turns. 1px correspond to 1/10mm so I try 3px spacing to get approx 3 zigzag turns per mm.';

		$textArr[]='';

		$textArr[]='Here is the resulting nodes.';

		$textArr[]='Now I copy-paste the path and resize the copy to be slightly larger than the outer part of the leaf. The two paths now need to be 

			combined (Ctrl + K).';

		$textArr[]='Zoom in to the end nodes and join the two combined paths on one side. This can be done by selected the two end nodes (press Shift to 

			select more than one element) and press the "Join selected end nodes with a new segment"-button on the upper left corner of your screen.';

		$textArr[]='Now you are ready for the netting effect. You will be asked for "seroke with". That is just a misspelled "stroke with" and I use 

			0.5-1 px to see what I\'m doing.';

		$textArr[]='The resulting satin stitch.';

	break;

	case "fill":

		$nImg=1;

		$title='How to create filled shapes';

		$textArr[]='The Path Effect Hatches is very useful for creating filled shapes. If you want to keep the original outline in addition to the fill you need

			to start by copying the path. Select the path, open the Path Effect Editor (Shift + Ctrl + 7) and add the Hatches effect. A long list of (fun) paramters 

			pops up, but we will for now set most of them to zero. To adjust the direction and spacing of the filling you have to select the node tool and play around 

			with the little yellow circles and diamonds. A better description of this effect can be found in this <a href="http://wiki.evilmadscientist.com/Drawing_a_smiley_face" 

			target="_blank">tutorial</a> from the <a href="http://egg-bot.com" target="_blank">Eggbot project</a>. This project is about a robot that can draw paths on round objects 

			like eggs for example and use Inkscape to digitize the pen-paths. This project offer many ideas for digitizing embroideries using Inkscape too as it\'s a bit 

			of the same story. For creating filled regions I specifically recommend <a href="http://wiki.evilmadscientist.com/Creating_filled_regions" target="_blank">this tutorial</a>.';

	break;

	default:



	break;

	}



function genCode($textArr,$folder){

	$code='';

	for($i=0;$i<count($textArr);$i++){

		$code.='<div style="font-size:12px;"></br>'

			.$textArr[$i].

			'</br></br></div>

			<div>	

			<img src="'.$folder.'/img'.$i.'.png" border=0>

			</div>';

	}

	$code.='</br>';

	return $code;

}



$htmlCode='

	<h2>'.$title.'</h2><hr>

	'.genCode($textArr,$folder);



	?>