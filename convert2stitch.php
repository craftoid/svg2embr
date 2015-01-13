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

//no empty explode
function eexplode($separator,$string){
	$array = explode($separator,$string);
	$newarr=array();
	foreach($array as $key => $val){
		if(strlen($val)>0){
			$newarr[]=$val;
		}
	}
	return $newarr;
}

//devide straight line into stitches
//input: start - end position, $length of stitches
function addL($x1, $x2, $y1, $y2, $len, &$vector){
	//$vector=array('x'=>array(), 'y'=>array());
	$linelen=sqrt(pow(($x2-$x1),2)+pow(($y2-$y1),2));
	$nStitches=ceil($linelen/$len);
	if($nStitches > 0){
		$stLenX=($x2-$x1)/$nStitches;
		$stLenY=($y2-$y1)/$nStitches;
		for($s=1;$s<=$nStitches;$s++){
			$vector['x'][]=$x1+$s*$stLenX; 
			$vector['y'][]=$y1+$s*$stLenY; 
		}
	}
	return $vector;
}

//calculate distanse between points in array 'x'=array , 'y'=array of y-s
//return array of distances
function calcDist($x,$y){
	$distarr=array();
	for($i=0;$i<count($x)-1;$i++){
		$distarr[]=sqrt(pow($x[$i+1]-$x[$i],2)+pow($y[$i+1]-$y[$i],2));
	}
	return $distarr;
}

//insert $value into $array at $position
//return new array
function array_insert($array, $value, $position){
	if (is_array($array)) {
		$array_out = $array; // so I don't mangle it during foreach
		foreach ($array as $key => $val) {
			if ($key < $position) { $array_out[$key] = $val; }
			else { $array_out[$key+1] = $val; }
		}
		$array_out[$position] = $value;

		return $array_out;
	}
	return false;
}

//convert bezier-curve into stitches
//input $x, $y P0,P1,P2,P3, stitchlength, $type=2 quadratic, =3 cubic
//adds to $vector
function addCurve($x, $y, &$vector, $len, $type){
	//t=[0,1]
	//cubic: B(t)=(1-t)^3P0 + 3(1-t)^2tP1+3(1-t)t^2P2+t^3P3 (line from P0 to P3, P1 and P2 are controlpoints
	//quadratic: B(t)=(1-t)^2P0 + 2(1-t)tP1+t^2P2 (line from P0 to P2, P1 is controlpoint	
	$xx=array();$yy=array();
	$xx[]=$x[$type]; $yy[]=$y[$type];

	$t=array(0.0,1.0);
	$more=true;
	$dist=array(2*$len);
	
	while($more){
		//add calculation points where long stitches
		$newTs=array();
		$newXs=array();
		$newYs=array();
		$newpos=array();
		for($i=0;$i<count($dist);$i++){
			if($dist[$i]>$len){
				$newT=0.5*($t[$i]+$t[$i+1]);
				$newTs[]=$newT;
				if($type==2){
				$newXs[]=pow(1-$newT,2)*$x[0]+2*(1-$newT)*$newT*$x[1]+pow($newT,2)*$x[2];
				$newYs[]=pow(1-$newT,2)*$y[0]+2*(1-$newT)*$newT*$y[1]+pow($newT,2)*$y[2];
				} elseif($type==3){
				$newXs[]=pow(1-$newT,3)*$x[0]+3*pow(1-$newT,2)*$newT*$x[1]+3*(1-$newT)*pow($newT,2)*$x[2]+pow($newT,3)*$x[3];
				$newYs[]=pow(1-$newT,3)*$y[0]+3*pow(1-$newT,2)*$newT*$y[1]+3*(1-$newT)*pow($newT,2)*$y[2]+pow($newT,3)*$y[3];			
				}
				$newpos[]=$i+1;
			}
		}

		$add=0;
		for($j=0;$j<count($newTs);$j++){
			$t=array_insert($t,$newTs[$j],$newpos[$j]+$add);
			$xx=array_insert($xx,$newXs[$j],$newpos[$j]+$add-1);
			$yy=array_insert($yy,$newYs[$j],$newpos[$j]+$add-1);
			$add++;
		}
		$dist=calcDist(array_insert($xx,$x[0],0),array_insert($yy,$y[0],0));
		
		if(max($dist)>$len) $more=true; else {
			if(count($dist)==2){// start end also shorter than len
				$dist01=calcDist(array($x[0],$x[$type]),array($y[0],$y[$type]));
				if($dist01<$len){
					$xx=$x[$type]; $yy=$y[$type];
				}
			}
			$more=false;
		}
	}

	$vector['x']=array_merge($vector['x'],$xx); 
	$vector['y']=array_merge($vector['y'],$yy); 
}

//add stitches to vector
//coordarr=array of coordinatearrays
//typearr=array of curvetypes (0,1,2,3)
//lentemp = stitchlength converted to original size
function addStitches($coordArr, $typeArr, $lentemp, &$vector){

	$nFlags=count($typeArr);
		
	for($i=0;$i<$nFlags;$i++){
		$type=$typeArr[$i];
		$typetemp=$type;
		if($typetemp==3) $typetemp=2;
		switch($typetemp){
			case 0: //start of new path (jumpstitch) 
				$vector['path'][]=count($vector['x']);
				$vector['x'][]=$coordArr[$i][0]; $vector['y'][]=$coordArr[$i][1];
				break;
			case 1: //line element
				$nPos=count($coordArr[$i])/2;
				$lasty=end($vector['y']); $lastx=end($vector['x']);
				for($j=0;$j<$nPos;$j++) {
					$thisx=$coordArr[$i][$j*2]; $thisy=$coordArr[$i][$j*2+1];
					if($lentemp>0){
						addL($lastx,$thisx,$lasty,$thisy,$lentemp,$vector);
					} else {
						$vector['x'][]=$thisx; $vector['y'][]=$thisy;
					}
					$lastx=$thisx; $lasty=$thisy;
					}
				break;
			case 2: //bezier curve
				$nPos=count($coordArr[$i])/($type*2);
				$lasty=end($vector['y']); $lastx=end($vector['x']);

				for($j=0;$j<$nPos;$j++) {
					$xs=array($lastx); $ys=array($lasty);
					for($k=0;$k<$type;$k++){
						$xs[]=$coordArr[$i][$j*(2*$type)+(2*$k)]; 
						$ys[]=$coordArr[$i][$j*(2*$type)+(2*$k)+1];
					}

					if($lentemp>0) addCurve($xs,$ys,$vector,$lentemp, $type); else {
						$vector['x'][]=end($xs); $vector['y'][]=end($ys);
					}
					$lastx=end($xs); $lasty=end($ys);
				}
				
				break;
			case 4:// arc
			
				break;
		}
	}
}

function transform($xyarr,$tf_arr){
	$nTF=count($tf_arr)/6;

	for($i=$nTF-1;$i>=0;$i--){
		$a=$tf_arr[$i*6]; $b=$tf_arr[$i*6+1]; $c=$tf_arr[$i*6+2];$d=$tf_arr[$i*6+3];$e=$tf_arr[$i*6+4];$f=$tf_arr[$i*6+5]; 
		for($p=0;$p<count($xyarr)/2;$p++){
			//xnew=a*x+c*y+e,  ynew=b*x+d*y+f
			$xo=$xyarr[$p*2]; $yo=$xyarr[$p*2+1];
			$xyarr[$p*2]=$a*$xo+$c*$yo+$e;
			$xyarr[$p*2+1]=$b*$xo+$d*$yo+$f;
		}
	}
	
	return $xyarr;
}

//convert vector path (svg format string) to stitches (points xy)
//$len = (max) stitchlength in 1/10mm
//$combine true/false separated paths to one path
//$unitsPrCm = rounded numbers for smallest unit PCS 60/cm, EXP 100/cm?
function svg2vector($filecontent, $len, $combine, $unitsPrCm){

	$nElem=array('M'=>2,'L'=>2,'H'=>1,'V'=>1,'C'=>6, 'S'=>4,'Q'=>4,'T'=>2, 'A'=>7, 'Z'=>0);
	$vector=array('x'=>array(), 'y'=>array(), 'path'=>array(), 'color'=>array());//path=position for new path (jumpstitch)
	$lastx=0; $lasty=0;//to keep track when relative paths
	$coordArr=array();
	$typeArr=array(); //0=pathstart, 1=line, 2=quadratic bezier, 3=cubic bezier
	
	$size=getSvgPageSize($filecontent);
	$svgPathTF=extractSvgPath($filecontent);
	$svgPath=$svgPathTF['paths'];
	$svgTF=$svgPathTF['transforms'];
	$vector['color']=$svgPathTF['colors'];
	$nPaths=count($svgPath);
	
	for($p=0;$p<$nPaths;$p++){
		//puts path into code- and number-elements
		$svgPath[$p]=str_replace(',',' ',$svgPath[$p]);
		$splitPath=preg_split('/([aAcChHlLmMqQsStTvVzZ])/',$svgPath[$p],-1,PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);
		$lastx=0; $lasty=0;
		if(strtoupper(end($splitPath))=='Z') $splitPath[]='';
		
		//transforms?
		$transform=false;
		if(strlen($svgTF[$p])>0) {
			$transform=true;
			$num_tf=eexplode(' ',$svgTF[$p]);
		}
		
		//sort path into coordArr (array of coordinate arrays - convert to absolute coord only) 
		//and typeArr (array of type 0 (startpoint), 1(straight-line), 2 (quadratic bezier), 3 (cubic bezier))
		for($i=1;$i<count($splitPath);$i=$i+2){
			$type=strtoupper($splitPath[$i-1]);
			$arr=eexplode(' ',$splitPath[$i]);
			
			if($splitPath[$i-1]==$type) $rel=false; else $rel=true;

			if($type=='Z') $nReps=1; else $nReps=count($arr)/$nElem[$type];
			
			$temparr=array();
			
			for($j=0;$j<$nReps;$j++){
				$addx=0;$addy=0;
				if($rel){
					$addx=$lastx;$addy=$lasty;
				}
	
				switch($type){
				case 'M':
					$lastx=$arr[$j*2]+$addx; $lasty=$arr[$j*2+1]+$addy;
					$temparr[]=$lastx; $temparr[]=$lasty;
					if($j==0) {
						$typeArr[]=0;
						if($nReps>1){
							if($transform) $temparr=transform($temparr,$num_tf);
							$coordArr[]=$temparr;
							$temparr=array(); //start with new array for L
							$typeArr[]=1;
						}
					}
					break;
				case 'Z':
					$mpos=end(array_keys($typeArr,0));
					$typeArr[]=1;
					$temparr[]=$coordArr[$mpos][0]; $temparr[]=$coordArr[$mpos][1];//first element in last path (m)
					break;
				case 'L':
					if($j==0) $typeArr[]=1;
					$lastx=$arr[$j*2]+$addx; $lasty=$arr[$j*2+1]+$addy;
					$temparr[]=$lastx; $temparr[]=$lasty;
					break;
				case 'H':
					if($j==0) $typeArr[]=1;
					$lastx=$arr[$j]+$addx;
					$temparr[]=$lastx; $temparr[]=$lasty;
					break;
				case 'V':
					if($j==0) $typeArr[]=1;
					$ypos[]=$arr[$j]+$addy;
					$temparr[]=$lastx; $temparr[]=$lasty;
					break;
				case 'Q':
					if($j==0) $typeArr[]=2;
					$lastx=$arr[$j*4+2]+$addx; $lasty=$arr[$j*4+3]+$addy;
					for($s=0;$s<2;$s++){
						$temparr[]=$arr[$j*4+$s*2]+$addx;
						$temparr[]=$arr[$j*4+$s*2+1]+$addy;
					}
					break;
				case 'C':
					if($j==0) $typeArr[]=3;
					$lastx=$arr[$j*6+4]+$addx; $lasty=$arr[$j*6+5]+$addy;
					for($s=0;$s<3;$s++){
						$temparr[]=$arr[$j*6+$s*2]+$addx;
						$temparr[]=$arr[$j*6+$s*2+1]+$addy;
					}
					break;
				
				case 'A':
					//not yet implemented - line only
					//rx ry x-axis-rotation large-arc-flag sweep-flag end-x end-y
					break;
				}

			}
			if($transform && $type!='Z') $temparr=transform($temparr,$num_tf);
			$coordArr[]=$temparr;
		} //end of organizing path
	}//end of path-loop

	$xn=true;
	$xpos=array(); $ypos=array();
	for($k=0;$k<count($coordArr);$k++){
		for($l=0;$l<count($coordArr[$k]);$l++){
			if($xn){
				$xpos[]=$coordArr[$k][$l]; 
				$xn=false;
			} else {
				$ypos[]=$coordArr[$k][$l];
				$xn=true;
			}
		}
	}
	
	$senter=array(round($size[0]/2),round($size[1]/2));
	$len=$len*$unitsPrCm/10;

	addStitches($coordArr, $typeArr, $len, $vector);

	if($combine==0){
		$outpaths=$vector['path'];
		$outcolors=$vector['color'];
	} else {
		$outpaths=$vector['path'][0];
		$outcolors=$vector['color'][0];
	}
	
	$out_vector=array('x'=>array(), 'y'=>array(), 'path'=>$outpaths, 'color'=>$outcolors, 'center'=>$senter);

	for($t=0;$t<count($vector['x']);$t++){
		$out_vector['x'][]=round($vector['x'][$t])-$senter[0];
		$out_vector['y'][]=$senter[1]*2-abs(round($vector['y'][$t]))-$senter[1];
	}
	unset($vector);

	return $out_vector;
}

//extract transformation matrix from svg-element
function getTransformMatrix($str){
	
	$outin=explode('"',$str);
	$str=$outin[1];
	$tf_string='';
	$parts=explode(')',$str);
	for($i=0;$i<count($parts);$i++){
		$elem=explode('(',$parts[$i]);
		if(count($elem)==2){
			$tf_type=trim($elem[0]);
			$tf_num=eexplode(',',$elem[1]);
			switch($tf_type){
				case 'matrix':
					for($j=0;$j<count($tf_num);$j++){
						$tf_string=$tf_string.' '.$tf_num[$j];
					}
					break;
				case 'translate':
					if(count($tf_num)==1) $tf_num[]=$tf_num[0];
					$tf_string=$tf_string.' 1 0 0 1 '.$tf_num[0].' '.$tf_num[1];
					break;
				case 'scale':
					if(count($tf_num)==1) $tf_num[]=$tf_num[0];
					$tf_string=$tf_string.' '.$tf_num[0].' 0 0 '.$tf_num[1].' 0 0';
					break;
				case 'rotate':
					$a=deg2rad($tf_num[0]);
					$ca=cos($a); $sa=sin($a);
					$rotstr=' '.$ca.' '.$sa.' -'.$sa.' '.$ca.' 0 0';
					if(count($tf_num)>1){
						$tf_string=$tf_string.' 1 0 0 1 '.$tf_num[1].' '.$tf_num[2];
						$tf_string=$tf_string.$rotstr;
						$tf_string=$tf_string.' 1 0 0 1 -'.$tf_num[1].' -'.$tf_num[2];
					} else $tf_string=$tf_string.$rotstr;
					break;
				case 'skewX':
					$a=deg2rad($tf_num[0]);
					$ta=tan($a);
					$tf_string=$tf_string.' 1 0 '.$ta.' 1 0 0';
					break;
				case 'skewY':
					$a=deg2rad($tf_num[0]);
					$ta=tan($a);
					$tf_string=$tf_string.' 1 '.$ta.' 0 1 0 0';
					break;
					
			}
		}
	}
	
	return $tf_string;

}

//read xml (svg) file and return array of paths (m .... z)
function extractSvgPath($filecont){
	$paths=array();
	$transforms=array();
	$colors=array();
	
	//<g=g,/g>=eg,<path=p
	$tagArr=array('tag'=>array(), 'pos'=>array());
	$next=TRUE;
	$offset=0;
	while($next!=FALSE){
		$nextG=strpos($filecont,'<g',$offset);
		$nextEG=strpos($filecont,'/g>',$offset);
		$nextP=strpos($filecont,'<path',$offset);
		if($nextG!=FALSE || $nextEG!=FALSE || $nextP!=FALSE){
			$nextArr=array();
			if($nextG!=FALSE) $nextArr[]=$nextG;
			if($nextEG!=FALSE) $nextArr[]=$nextEG;
			if($nextP!=FALSE) $nextArr[]=$nextP;
			$mini=min($nextArr);
			$tagArr['pos'][]=$mini;
			if($mini==$nextG) $tagArr['tag'][]='g';
			if($mini==$nextEG) $tagArr['tag'][]='eg';
			if($mini==$nextP) $tagArr['tag'][]='p';
			$offset=$mini+1;
		} else $next=FALSE;
	}
	
	//for each group: find a transform
	//for each path: find the path + transform
	//add group transform to path transform if path is in group (or nested group)
	
	$ntags=count($tagArr['tag']);
	$tfArr=array();
	for($i=0;$i<$ntags;$i++){
		if($i<$ntags-1)	$substr=substr($filecont,$tagArr['pos'][$i],$tagArr['pos'][$i+1]-$tagArr['pos'][$i]); else $substr=substr($filecont,$tagArr['pos'][$i]);
		switch($tagArr['tag'][$i]){
			case 'g':
				//search for transform, add to $tfArr also if empty
				$tfpos=strpos($substr,' transform="');
				if($tfpos!=FALSE){
					$tf=substr($substr,$tfpos);
					$tfArr[]=getTransformMatrix($tf);
				} else $tfArr[]='';
				break;
			case 'eg':
				//remove last element of $tfArr
				$pop=array_pop($tfArr);
				break;
			case 'p':
				//extract path, transform and color, link to transform string of $tfArr
				$dpos=strpos($substr,' d=');
				if($dpos!=FALSE){
					$t=explode('"',substr($substr,$dpos));
					$test=stripos($t[1],'a');//avoid arc paths - not yet implemented
					if ($test==FALSE) {
						if(strlen($t[1])>0){
							$paths[]=$t[1];
							$tfpos=strpos($substr,' transform="');
							if($tfpos!=FALSE){
								$tf=substr($substr,$tfpos);
								$tfArr[]=getTransformMatrix($tf);
							} else $tfArr[]='';
							$nTf=count($tfArr);
							$trns='';
							for($n=0;$n<$nTf;$n++) $trns=$trns.$tfArr[$n];
							$transforms[]=$trns;
							$pop=array_pop($tfArr);
							$colpos=strpos($substr, 'stroke:#');
							if($colpos!=FALSE) $colors[]=substr($substr,$colpos+8,6); else $colors[]='000000';
						}
					}
				}
				break;
		}
	}
	return array('paths'=>$paths,'transforms'=>$transforms,'colors'=>$colors);
}

function getSvgPageSize($filecont){
	$size=array();
	
	$wPos=strpos($filecont,'width="',0);
	$t=explode('"',substr($filecont,$wPos));
	$size[]=(int)$t[1];
	$hPos=strpos($filecont,'height="',0);
	$t=explode('"',substr($filecont,$hPos));
	$size[]=(int)$t[1];
	
	return $size;
}
?>