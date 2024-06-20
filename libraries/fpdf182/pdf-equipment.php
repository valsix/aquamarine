<?php
require('fpdf.php');
ini_set('upload_max_filesize', '128M');
        ini_set('post_max_size', '128M');
        ini_set('memory_limit', '-1');
        ini_set('max_input_time', 520);
        ini_set('max_execution_time', 300);
        set_time_limit(0);
class PDF extends FPDF{


	var $widths;
	var $aligns;

	function SetWidths($w)
	{
    //Set the array of column widths
		$this->widths=$w;
	}

	function SetAligns($a)
	{
    //Set the array of column alignments
		$this->aligns=$a;
	}
	function hex2dec($couleur = "#000000"){
    $R = substr($couleur, 1, 2);
    $rouge = hexdec($R);
    $V = substr($couleur, 3, 2);
    $vert = hexdec($V);
    $B = substr($couleur, 5, 2);
    $bleu = hexdec($B);
    $tbl_couleur = array();
    $tbl_couleur['R']=$rouge;
    $tbl_couleur['V']=$vert;
    $tbl_couleur['B']=$bleu;
    return $tbl_couleur;
}

//conversion pixel -> millimeter at 72 dpi
function px2mm($px){
    return $px*25.4/72;
}

function txtentities($html){
    $trans = get_html_translation_table(HTML_ENTITIES);
    $trans = array_flip($trans);
    return strtr($html, $trans);
}
	function WriteHTML($html)
	{
    //HTML parser
    $html=strip_tags($html,"<b><u><i><a><img><p><br><strong><em><font><tr><blockquote>"); //supprime tous les tags sauf ceux reconnus
    $html=str_replace("\n",' ',$html); //remplace retour à la ligne par un espace
    $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE); //éclate la chaîne avec les balises
    foreach($a as $i=>$e)
    {
    	if($i%2==0)
    	{
            //Text
    		if($this->HREF)
    			$this->PutLink($this->HREF,$e);
    		else
    			$this->Write(5,stripslashes($this->txtentities($e)));
    	}
    	else
    	{
            //Tag
    		if($e[0]=='/')
    			$this->CloseTag(strtoupper(substr($e,1)));
    		else
    		{
                //Extract attributes
    			$a2=explode(' ',$e);
    			$tag=strtoupper(array_shift($a2));
    			$attr=array();
    			foreach($a2 as $v)
    			{
    				if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
    					$attr[strtoupper($a3[1])]=$a3[2];
    			}
    			$this->OpenTag($tag,$attr);
    		}
    	}
    }
}
			function OpenTag($tag, $attr)
			{
			    //Opening tag
				switch($tag){
					case 'STRONG':
					$this->SetStyle('B',true);
					break;
					case 'EM':
					$this->SetStyle('I',true);
					break;
					case 'B':
					case 'I':
					case 'U':
					$this->SetStyle($tag,true);
					break;
					case 'A':
					$this->HREF=$attr['HREF'];
					break;
					case 'IMG':
					if(isset($attr['SRC']) && (isset($attr['WIDTH']) || isset($attr['HEIGHT']))) {
						if(!isset($attr['WIDTH']))
							$attr['WIDTH'] = 0;
						if(!isset($attr['HEIGHT']))
							$attr['HEIGHT'] = 0;
						$this->Image($attr['SRC'], $this->GetX(), $this->GetY(), px2mm($attr['WIDTH']), px2mm($attr['HEIGHT']));
					}
					break;
					case 'TR':
					case 'BLOCKQUOTE':
					case 'BR':
					$this->Ln(5);
					break;
					case 'P':
					$this->Ln(10);
					break;
					case 'FONT':
					if (isset($attr['COLOR']) && $attr['COLOR']!='') {
						$coul=hex2dec($attr['COLOR']);
						$this->SetTextColor($coul['R'],$coul['V'],$coul['B']);
						$this->issetcolor=true;
					}
					if (isset($attr['FACE']) && in_array(strtolower($attr['FACE']), $this->fontlist)) {
						$this->SetFont(strtolower($attr['FACE']));
						$this->issetfont=true;
					}
					break;
				}
			}

		function CloseTag($tag)
		{
		    //Closing tag
			if($tag=='STRONG')
				$tag='B';
			if($tag=='EM')
				$tag='I';
			if($tag=='B' || $tag=='I' || $tag=='U')
				$this->SetStyle($tag,false);
			if($tag=='A')
				$this->HREF='';
			if($tag=='FONT'){
				if ($this->issetcolor==true) {
					$this->SetTextColor(0);
				}
				if ($this->issetfont) {
					$this->SetFont('arial');
					$this->issetfont=false;
				}
			}
		}
		function checkbox( $pdf, $checked = TRUE, $checkbox_size = 5 , $ori_font_family = 'Arial', $ori_font_size = '10', $ori_font_style = '' )
		{
			if($checked == TRUE)
				$check = "4";
			else
				$check = "";
			
			$h=5;
			// $pdf->Rect($x,$y,$w,$h);
			$pdf->SetFont('ZapfDingbats','', $ori_font_size);
			$pdf->MultiCell($x+$w, $checkbox_size, $check, 1, 0);
			$pdf->SetFont( $ori_font_family, $ori_font_style, $ori_font_size);
			$this->SetXY($x+$w,$y);
		}


	function setImageKey($key){
    	$this->imageKey = $key;
	}
	function Row($data)
	{
    //Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		$h=5*$nb;
    //Issue a page break first if needed
		$this->CheckPageBreak($h);
    //Draw the cells of the row
		for($i=0;$i<count($data);$i++)
		{
			$w=$this->widths[$i];
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
			$x=$this->GetX();
			$y=$this->GetY();
        //Draw the border
			$this->Rect($x,$y,$w,$h);
        //Print the text
			// writeHTML
			//modify functions for image 
	        if(!empty($this->imageKey) && in_array($i,$this->imageKey)){

				$ih = $h - 0.5;
				$iw = $w - 0.5;
				$ix = $x + 0.25;
				$iy = $y + 0.25;
				$this->MultiCell($w,5,$this->Image ($data[$i],$ix,$iy,$iw,$ih),0,$a);
	        }else{
	        	$this->MultiCell($w,5,$data[$i],0,$a);	
	        }
			
        //Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
		}
    //Go to the next line
		$this->Ln($h);
	}
	function _parsepng($file)
{
    //Extract info from a PNG file
    $f=fopen($file,'rb');
    if(!$f)
        $this->Error('Can\'t open image file: '.$file);
    //Check signature
    if(fread($f,8)!=chr(137).'PNG'.chr(13).chr(10).chr(26).chr(10))
        $this->Error('Not a PNG file: '.$file);
    //Read header chunk
    fread($f,4);
    if(fread($f,4)!='IHDR')
        $this->Error('Incorrect PNG file: '.$file);
    $w=$this->_readint($f);
    $h=$this->_readint($f);
    $bpc=ord(fread($f,1));
    if($bpc>8)
        $this->Error('16-bit depth not supported: '.$file);
    $ct=ord(fread($f,1));
    if($ct==0)
        $colspace='DeviceGray';
    elseif($ct==2)
        $colspace='DeviceRGB';
    elseif($ct==3)
        $colspace='Indexed';
    else {
        fclose($f);      // the only changes are 
        return 'alpha';  // made in those 2 lines
    }
    if(ord(fread($f,1))!=0)
        $this->Error('Unknown compression method: '.$file);
    if(ord(fread($f,1))!=0)
        $this->Error('Unknown filter method: '.$file);
    if(ord(fread($f,1))!=0)
        $this->Error('Interlacing not supported: '.$file);
    fread($f,4);
    $parms='/DecodeParms <</Predictor 15 /Colors '.($ct==2 ? 3 : 1).' /BitsPerComponent '.$bpc.' /Columns '.$w.'>>';
    //Scan chunks looking for palette, transparency and image data
    $pal='';
    $trns='';
    $data='';
    do
    {
        $n=$this->_readint($f);
        $type=fread($f,4);
        if($type=='PLTE')
        {
            //Read palette
            $pal=fread($f,$n);
            fread($f,4);
        }
        elseif($type=='tRNS')
        {
            //Read transparency info
            $t=fread($f,$n);
            if($ct==0)
                $trns=array(ord(substr($t,1,1)));
            elseif($ct==2)
                $trns=array(ord(substr($t,1,1)),ord(substr($t,3,1)),ord(substr($t,5,1)));
            else
            {
                $pos=strpos($t,chr(0));
                if($pos!==false)
                    $trns=array($pos);
            }
            fread($f,4);
        }
        elseif($type=='IDAT')
        {
            //Read image data block
            $data.=fread($f,$n);
            fread($f,4);
        }
        elseif($type=='IEND')
            break;
        else
            fread($f,$n+4);
    }
    while($n);
    if($colspace=='Indexed' && empty($pal))
        $this->Error('Missing palette in '.$file);
    fclose($f);
    return array('w'=>$w,'h'=>$h,'cs'=>$colspace,'bpc'=>$bpc,'f'=>'FlateDecode','parms'=>$parms,'pal'=>$pal,'trns'=>$trns,'data'=>$data);
}


	function Image($file, $x=null, $y=null, $w=0, $h=0, $type='', $link='', $isMask=false, $maskImg=0)
{
    //Put an image on the page
    if(!isset($this->images[$file]))
    {
        //First use of this image, get info
        if($type=='')
        {
            $pos=strrpos($file,'.');
            if(!$pos)
                $this->Error('Image file has no extension and no type was specified: '.$file);
            $type=substr($file,$pos+1);
        }
        $type=strtolower($type);
        if($type=='png'){
            $info=$this->_parsepng($file);
            if($info=='alpha')
                return $this->ImagePngWithAlpha($file,$x,$y,$w,$h,$link);
        }
        else
        {
            if($type=='jpeg')
                $type='jpg';
            $mtd='_parse'.$type;
            if(!method_exists($this,$mtd))
                $this->Error('Unsupported image type: '.$type);
            $info=$this->$mtd($file);
        }
        if($isMask){
            if(in_array($file,$this->tmpFiles))
                $info['cs']='DeviceGray'; //hack necessary as GD can't produce gray scale images
            if($info['cs']!='DeviceGray')
                $this->Error('Mask must be a gray scale image');
            if($this->PDFVersion<'1.4')
                $this->PDFVersion='1.4';
        }
        $info['i']=count($this->images)+1;
        if($maskImg>0)
            $info['masked'] = $maskImg;
        $this->images[$file]=$info;
    }
    else
        $info=$this->images[$file];
    //Automatic width and height calculation if needed
    if($w==0 && $h==0)
    {
        //Put image at 72 dpi
        $w=$info['w']/$this->k;
        $h=$info['h']/$this->k;
    }
    elseif($w==0)
        $w=$h*$info['w']/$info['h'];
    elseif($h==0)
        $h=$w*$info['h']/$info['w'];
    //Flowing mode
    if($y===null)
    {
        if($this->y+$h>$this->PageBreakTrigger && !$this->InHeader && !$this->InFooter && $this->AcceptPageBreak())
        {
            //Automatic page break
            $x2=$this->x;
            $this->AddPage($this->CurOrientation,$this->CurPageFormat);
            $this->x=$x2;
        }
        $y=$this->y;
        $this->y+=$h;
    }
    if($x===null)
        $x=$this->x;
    if(!$isMask)
        $this->_out(sprintf('q %.2F 0 0 %.2F %.2F %.2F cm /I%d Do Q',$w*$this->k,$h*$this->k,$x*$this->k,($this->h-($y+$h))*$this->k,$info['i']));
    if($link)
        // $this->Link($x,$y,$w,$h,$link);
    return $info['i'];
}
	function ImagePngWithAlpha($file,$x,$y,$w=0,$h=0,$link='')
{
    $tmp_alpha = tempnam('.', 'mska');
    $this->tmpFiles[] = $tmp_alpha;
    $tmp_plain = tempnam('.', 'mskp');
    $this->tmpFiles[] = $tmp_plain;
    
    list($wpx, $hpx) = getimagesize($file);
    $img = imagecreatefrompng($file);
    $alpha_img = imagecreate( $wpx, $hpx );
    
    // generate gray scale pallete
    for($c=0;$c<256;$c++) ImageColorAllocate($alpha_img, $c, $c, $c);
    
    // extract alpha channel
    $xpx=0;
    while ($xpx<$wpx){
        $ypx = 0;
        while ($ypx<$hpx){
            $color_index = imagecolorat($img, $xpx, $ypx);
            $alpha = 255-($color_index>>24)*255/127; // GD alpha component: 7 bit only, 0..127!
            imagesetpixel($alpha_img, $xpx, $ypx, $alpha);
        ++$ypx;
        }
        ++$xpx;
    }

    imagepng($alpha_img, $tmp_alpha);
    imagedestroy($alpha_img);
    
    // extract image without alpha channel
    $plain_img = imagecreatetruecolor ( $wpx, $hpx );
    imagecopy ($plain_img, $img, 0, 0, 0, 0, $wpx, $hpx );
    imagepng($plain_img, $tmp_plain);
    imagedestroy($plain_img);
    
    //first embed mask image (w, h, x, will be ignored)
    $maskImg = $this->Image($tmp_alpha, 0,0,0,0, 'PNG', '', true); 
    
    //embed image, masked with previously embedded mask
    $this->Image($tmp_plain,$x,$y,$w,$h,'PNG',$link, false, $maskImg);
} 
	function RowImage($data)
	{
    //Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));

		if(!empty($this->imageKey)){
			$widthimage = 0;
			for ($i=0; $i < count($this->imageKey); $i++) { 
				if($widthimage < $this->widths[$this->imageKey[$i]])
					$widthimage = $this->widths[$this->imageKey[$i]];
			}
			$h = $widthimage-8;
		} else {
			$h=5*$nb;
		}
    //Issue a page break first if needed
		$this->CheckPageBreak($h);
    //Draw the cells of the row
		for($i=0;$i<count($data);$i++)
		{
			$w=$this->widths[$i];
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
			$x=$this->GetX();
			$y=$this->GetY();
        //Draw the border
			$this->Rect($x,$y,$w,$h);
        //Print the text
			// writeHTML
			//modify functions for image 
	        if(!empty($this->imageKey) && in_array($i,$this->imageKey)){

				$ih = $h - 0.5;
				$iw = $w - 0.5;
				$ix = $x + 0.25;
				$iy = $y + 0.25;
				// $maskImg2 = $this->ImagePngWithAlpha($data[$i], $ix,$iy,$iw,$ih, '', '', true); 
				$this->MultiCell($w,5,$this->Image($data[$i],$ix,$iy,$iw,$ih),0,$a);
	        }else{
	        	$this->MultiCell($w,5,$data[$i],0,$a);	
	        }
			
        //Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
		}
    //Go to the next line
		$this->Ln($h);
	}
	function RowLeft($data)
	{
    //Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		$h=5*$nb;
    //Issue a page break first if needed
		$this->CheckPageBreak($h);
    //Draw the cells of the row
		for($i=0;$i<count($data);$i++)
		{
			$datas = explode('&&',$data[$i]);
			$ext ='L';
			if (!empty($datas[1])){
				$ext=$datas[1];
			}
			$w=$this->widths[$i];
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : $ext;
        //Save the current position
			$x=$this->GetX();
			$y=$this->GetY();
        //Draw the border
			$this->Rect($x,$y,$w,$h);
        //Print the text
			// writeHTML
			$this->MultiCell($w,5,$datas[0],0,$ext);
        //Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
		}
    //Go to the next line
		$this->Ln($h);
	}
	function RowRight($data)
	{
    //Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		$h=5*$nb;
    //Issue a page break first if needed
		$this->CheckPageBreak($h);
    //Draw the cells of the row
		for($i=0;$i<count($data);$i++)
		{
			$datas = explode('&&',$data[$i]);
			$ext ='R';
			if (!empty($datas[1])){
				$ext=$datas[1];
			}
			$w=$this->widths[$i];
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : $ext;
        //Save the current position
			$x=$this->GetX();
			$y=$this->GetY();
        //Draw the border
			$this->Rect($x,$y,$w,$h);
        //Print the text
			// writeHTML
			$this->MultiCell($w,5,$datas[0],0,$ext);
        //Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
		}
		// exit;
    //Go to the next line
		$this->Ln($h);

	}
	function RowWithCheck($data,$ori_font_family = 'Arial', $ori_font_size = '10', $ori_font_style = '')
	{
    //Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		$h=5*$nb;
    //Issue a page break first if needed
		$this->CheckPageBreak($h);
    //Draw the cells of the row
		for($i=0;$i<count($data);$i++)
		{
			$w=$this->widths[$i];
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
			$x=$this->GetX();
			$y=$this->GetY();
        //Draw the border
			$this->Rect($x,$y,$w,$h);
        //Print the text
			// writeHTML
			if($data[$i]=='checked'){
				$data[$i]=4;
				$this->SetFont('ZapfDingbats','', $ori_font_size);
					$this->MultiCell($w, 5, $data[$i], 0, 'C');
					$this->Ln();
			}else{
				$this->SetFont( $ori_font_family, $ori_font_style, $ori_font_size);
					$this->MultiCell($w, 5, $data[$i], 0, $a);
			}
			
			// $this->MultiCell($w, 5, $data[$i], 0, $a);

			// $this->SetFont( $ori_font_family, $ori_font_style, $ori_font_size);
			// $this->MultiCell($w,5,$data[$i],0,$a);
        //Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
		}
    //Go to the next line
		$this->Ln($h);
	}
	function RowCenter($data)
	{
    //Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		$h=5*$nb;
    //Issue a page break first if needed
		$this->CheckPageBreak($h);
    //Draw the cells of the row
		for($i=0;$i<count($data);$i++)
		{
			$w=$this->widths[$i];
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
        //Save the current position
			$x=$this->GetX();
			$y=$this->GetY();
        //Draw the border
			$this->Rect($x,$y,$w,$h);
        //Print the text
			// writeHTML
			$this->MultiCell($w,5,$data[$i],0,$a);
        //Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
		}
    //Go to the next line
		$this->Ln($h);

	}
	

	function CheckPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}
function Header()
{
    // Logo
    $this->Image('images/header-logo.jpg',(($this->w*3)/100),(($this->w*1)/100),(($this->w*12)/100), (($this->w*12)/100));
    // Arial bold 15
    $panjang = (($pdf->w * 80) / 100);
	// ECHO $pdf->w;exit;
	$this->SetFont('Arial', 'B', 18);
	$this->Cell($panjang, 24, 'EQUIPMENT LIST', 0, 0, 'C');
    // Line break
    $this->Ln(30);
}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-10);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number

     $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',$this->GetY(),0,'C');
    //  $this->SetY(-50);
    // $this->Image('images/footer-min.png',10,$this->GetY(),$this->w);
   
}
function NbLines($w,$txt)
{
    //Computes the number of lines a MultiCell of width w will take
	$cw=&$this->CurrentFont['cw'];
	if($w==0)
		$w=$this->w-$this->rMargin-$this->x;
	$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
	$s=str_replace("\r",'',$txt);
	$nb=strlen($s);
	if($nb>0 and $s[$nb-1]=="\n")
		$nb--;
	$sep=-1;
	$i=0;
	$j=0;
	$l=0;
	$nl=1;
	while($i<$nb)
	{
		$c=$s[$i];
		if($c=="\n")
		{
			$i++;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
			continue;
		}
		if($c==' ')
			$sep=$i;
		$l+=$cw[$c];
		if($l>$wmax)
		{
			if($sep==-1)
			{
				if($i==$j)
					$i++;
			}
			else
				$i=$sep+1;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
		}
		else
			$i++;
	}
	return $nl;
}

}
?>
