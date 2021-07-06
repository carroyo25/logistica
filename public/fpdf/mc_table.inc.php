<?php
require_once("fpdf.php");

class PDF_MC_Table extends FPDF {
 
    /**
     * The array of column widths
     * @var array
     * @access public
     */
    public $widths;
    
    /**
     * The array of column alignments or one alignment for all columns
     * @var array|string
     * @access public
     */
    public $aligns;
    
    /**
     * The cell height
     * @var number
     * @access public
     */
    public $cell_height = 3;
    
    /**
     * True if the table row should be colored
     * @var boolean
     * @access public
     */
    public $fill = false;
    
    /**
     * The page number
     * @var integer
     * @access public
     */
    public $table_page = 1;
    
    /**
     * Set the array of column widths
     *
     * @param array $w column widths array
     * @return void
     * @access public
     */
    function SetWidths($w) {
        $this->widths=$w;
    }
    
    /**
     * Set the array of column alignments or one alignment for all columns
     *
     * @param array|string $a column alignments array or one alignment for all columns
     * @return void
     * @access public
     */
    function SetAligns($a) {
        $this->aligns=$a;
    }
    
    /**
     * Set the cell height
     *
     * @param number $ch cell height
     * @return void
     * @access public
     */
    function SetCellHeight($ch) {
        $this->cell_height=$ch;
    }
    
    /**
     * Set if the row should be colored
     *
     * @param boolean $f true if the table row should be colored
     * @return void
     * @access public
     */
    function SetFill($f) {
        $this->fill=$f;
    }
    
    /**
     * Print a row if no page break is needed
     *
     * @param array $data contains the row data
     * @return boolean true if success, false if page break is needed
     * @access public
     */
    function Row($data) {
        // Calculate the height of the row
        $nb=0;
        for ($i=0;$i<count($data);$i++) {
            $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
        }
        $h=3.5*$nb;
        // Issue a page break first if needed and return so that you can add the table header again
        if ($this->CheckPageBreak($h)) {
            return false;
        } else {
            // Draw the cells of the row
            $style = $this->fill ? 'DF' : 'D';
            for ($i=0;$i<count($data);$i++) {
                $w=$this->widths[$i];
                $a='L';
                if (isset($this->aligns)) {
                    $a=is_array($this->aligns) ? $this->aligns[$i] : $this->aligns;
                }
                // Save the current position
                $x=$this->GetX();
                $y=$this->GetY();
                // Draw the border
                $this->Rect($x,$y,$w,$h,$style);
                // Print the text
                $this->MultiCell($w,$this->cell_height,$data[$i],0,$a);
                // Put the position to the right of the cell
                $this->SetXY($x+$w,$y);
            }
            //Go to the next line
            $this->Ln($h);
            return true;
        }
    }
    
    /**
     * Check if page break is needed
     *
     * @param number $h the height of the row
     * @return boolean true if a page break is needed
     * @access public
     */
    function CheckPageBreak($h)
    {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY()+3>$this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
            $this->table_page++;
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Computes the number of lines a MultiCell of width w will take
     *
     * @param number $w   cell width
     * @param string $txt cell text
     * @return integer number of lines a MultiCell of width w will take
     * @access public
     */
    function NbLines($w,$txt)
    {
        $cw=&$this->CurrentFont['cw'];
        if ($w==0) {
            $w=$this->w-$this->rMargin-$this->x;
        }
        $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
        $s=str_replace("\r",'',$txt);
        $nb=strlen($s);
        if ($nb>0 and $s[$nb-1]=="\n")
            $nb--;
        $sep=-1;
        $i=0;
        $j=0;
        $l=0;
        $nl=1;
        while ($i<$nb) {
            $c=$s[$i];
            if ($c=="\n") {
                $i++;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
                continue;
            }
            if ($c==' ') {
                $sep=$i;
            }
            $l+=$cw[$c];
            if ($l>$wmax) {
                if ($sep==-1) {
                    if ($i==$j) {
                        $i++;
                    }
                } else {
                    $i=$sep+1;
                }
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
            } else {
                $i++;
            }
        }
        return $nl;
    }

    function RoundedRect($x, $y, $w, $h, $r, $corners = '1234', $style = '')
   {
       $k = $this->k;
       $hp = $this->h;
       if($style=='F')
           $op='f';
       elseif($style=='FD' || $style=='DF')
           $op='B';
       else
           $op='S';
       $MyArc = 4/3 * (sqrt(2) - 1);
       $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));

       $xc = $x+$w-$r;
       $yc = $y+$r;
       $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));
       if (strpos($corners, '2')===false)
           $this->_out(sprintf('%.2F %.2F l', ($x+$w)*$k,($hp-$y)*$k ));
       else
           $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);

       $xc = $x+$w-$r;
       $yc = $y+$h-$r;
       $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
       if (strpos($corners, '3')===false)
           $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-($y+$h))*$k));
       else
           $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);

       $xc = $x+$r;
       $yc = $y+$h-$r;
       $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
       if (strpos($corners, '4')===false)
           $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-($y+$h))*$k));
       else
           $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);

       $xc = $x+$r ;
       $yc = $y+$r;
       $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
       if (strpos($corners, '1')===false)
       {
           $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$y)*$k ));
           $this->_out(sprintf('%.2F %.2F l',($x+$r)*$k,($hp-$y)*$k ));
       }
       else
           $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
       $this->_out($op);
   }

   function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
   {
       $h = $this->h;
       $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
           $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
   }
}
?>