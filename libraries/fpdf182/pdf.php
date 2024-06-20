<?php
require('fpdf.php');
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
function SetStyle($tag, $enable)
{
    //Modify style and select corresponding font
    $this->$tag+=($enable ? 1 : -1);
    $style='';
    foreach(array('B','I','U') as $s)
    {
        if($this->$s>0)
            $style.=$s;
    }
    $this->SetFont('',$style);
}
function PutLink($URL, $txt)
{
    //Put a hyperlink
    $this->SetTextColor(0,0,255);
    $this->SetStyle('U',true);
    $this->Write(5,$txt,$URL);
    $this->SetStyle('U',false);
    $this->SetTextColor(0);
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
	function Row($data,$height=5)
	{
    //Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		$h=$height*$nb;
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
				$this->MultiCell($w,$height,$this->Image ($data[$i],$ix,$iy,$iw,$ih),0,$a);
	        }else{
	        	$this->MultiCell($w,$height,$data[$i],0,$a);	
	        }
			
        //Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
		}
    //Go to the next line
		$this->Ln($h);
	}
	function RowBold($data,$height=5,$bold=array())
	{
    //Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		$h=$height*$nb;
    //Issue a page break first if needed
		$this->CheckPageBreak($h);
    //Draw the cells of the row
		for($i=0;$i<count($data);$i++)
		{
			if(in_array($i, $bold)){
				$this->SetFont('Arial', 'B', 8);
			}else{
				$this->SetFont('Arial', '', 8);
			}

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
				$this->MultiCell($w,$height,$this->Image ($data[$i],$ix,$iy,$iw,$ih),0,$a);
	        }else{
	        	$this->MultiCell($w,$height,$data[$i],0,$a);	
	        }
			
        //Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
		}
    //Go to the next line
		$this->Ln($h);
	}
	function RowImage($data,$height=5)
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
			$h = $widthimage;
		} else {
			$h=$height*$nb;
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
				$this->MultiCell($w,$height,$this->Image ($data[$i],$ix,$iy,$iw,$ih),0,$a);
	        }else{
	        	$this->MultiCell($w,$height,$data[$i],0,$a);	
	        }
			
        //Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
		}
    //Go to the next line
		$this->Ln($h);
	}
	function RowLeft($data,$height=5)
	{
    //Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],explode('&&',$data[$i])[0]));
		$h=$height*$nb;
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
			$this->MultiCell($w,$height,$datas[0],0,$ext);
        //Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
		}
    //Go to the next line
		$this->Ln($h);
	}
	function RowRight($data,$height=5)
	{
    //Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		$h=$height*$nb;
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
			$this->MultiCell($w,$height,$datas[0],0,$ext);
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
    $this->Image('images/logo_baru_min.jpg',10,6,(($this->w*90)/100));
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    // Move to the right
    $this->Cell(80);
    // Title
    // $this->Cell(30,10,'Title',0,0,'C');
    // Line break
    $this->Ln(40);
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
