<?php

namespace App\CustomModel;


class Pagination
{
    public $curpage;
    public $perpage;
    public $num_links = 5;
    public $numpage;
    public $numrows;
    public $start;
    public $startpage;
    public $endpage;
    public $paging_detail;

    public function __construct($curpage,$perpage,$numpage,$numrows,$start){
        $this->curpage = $curpage;
        $this->perpage = $perpage;
        $this->numpage = $numpage;
        $this->numrows = $numrows;
        $this->start = $start;

        $startpage=0;
        $endpage=0;
        if($numpage > $this->num_links){
            if($curpage > $this->num_links){
                if($curpage < $this->num_links)
			        $startpage = 1;
			    elseif($curpage >= ($numpage - floor($this->num_links / 2)) )
			        $startpage = $numpage - $this->num_links + 1;
			    elseif($curpage >= $this->num_links)
			        $startpage = $curpage  - floor($this->num_links/2);	
				$endpage = $startpage + $this->num_links -1;
            }
            else{
                $startpage = 1;
                $endpage = $this->num_links;
            }
        }
        else{
            $startpage = 1;
            $endpage = $numpage;
        }

        $this->startpage = $startpage;
        $this->endpage = $endpage;
        $this->paging_detail = "แสดง ".($start+1)." - ".(($start+$perpage) > $numrows ? $numrows :  ($start+$perpage))." จาก ".$numrows." รายการ";
    }

    public function renderHtml(){
        $html = '';
        if($this->curpage > 1){
            $html .= '<li ><a href="javascript:;" data-page="1" onclick="gotopage(this)">|&lt;</a></li>';
        }

        if($this->curpage > $this->num_links)
        {
            $html .='<li ><a href="javascript:;" data-page="'.($this->startpage-1).'" onclick="gotopage(this)">... <span class="sr-only"></span></a></li>';
        }

        for ($i = $this->startpage; $i <= $this->endpage; $i++){
            if($this->curpage == $i){
               $html .='<li class="active"><a href="javascript:;">'.$i.' <span class="sr-only">(current)</span></a></li>';
            }
            else
            {
                $html .='<li ><a href="javascript:;" data-page="'.$i.'" onclick="gotopage(this)">'.$i.' <span class="sr-only"></span></a></li>';
            }
           
        }
        
        if($this->curpage < ($this->numpage - floor($this->num_links / 2))){
            $html .='<li ><a href="javascript:;" data-page="'.($this->endpage+1).'" onclick="gotopage(this)">... <span class="sr-only"></span></a></li>';
        }
            
        if($this->curpage < $this->numpage){
            $html .= '<li ><a href="javascript:;" data-page="'.$this->numpage.'" onclick="gotopage(this)">&gt;|</a></li>';
        }
            
        
        return $html;
    }
}
