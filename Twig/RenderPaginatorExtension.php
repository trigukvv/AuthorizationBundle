<?php

namespace triguk\AuthorizationBundle\Twig;
use Doctrine\ORM\Tools\Pagination\Paginator;



class RenderPaginatorExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('renderPaginator', array($this, 'renderPaginator'),array('is_safe' => array('html'))),
        );
    }
    protected function addPaginatorEntry(&$entries,$caption='1',$pageNumber=1,$enabled=true,$active=false)
    {
        $entries[]=
        [
            'caption'=>$caption,
            'pageNumber'=>$pageNumber,
            'enabled'=>$enabled,
            'active'=>$active
        ];        
    }
    protected function buildPaginatorEntries(Paginator $paginator)
    {
        $pageSize=$paginator->getQuery()->getMaxResults();
        $page=$paginator->getQuery()->getFirstResult()/ $pageSize+1;
        $lastPage=ceil(count($paginator)/$pageSize);
        if ($lastPage==1) return [];
        $entries=[];
        $this->addPaginatorEntry(
            $entries,
            '&laquo;',  //$caption
            ($page-1)>1? $page-1: 1,    //$pageNumber
            ($page-1)>1,//$enabled
            false       //$active
        );
        $this->addPaginatorEntry(
            $entries,
            '&raquo;',          //$caption
            $lastPage>$page? ($page+1):$lastPage,          //$pageNumber
            $lastPage>$page,    //$enabled    
            false               //$active
        );
        $leftDotsPresent=false;
        $rightDotsPresent=false;
        for ($i=1;$i<=$lastPage;$i++)
        {
            if (($i>1) && ($i<($page-2)) )
            {
                if (!$leftDotsPresent) 
                {
                    $this->addPaginatorEntry(
                        $entries,
                        "...",        //$caption
                        $i,          //$pageNumber
                        false,       //$enabled    
                        false    //$active
                    );       
                    $leftDotsPresent=true;  
                }
            }
            else
            if (($i>($page+2)) && ($i<$lastPage))
            {
                if (!$rightDotsPresent) 
                {
                    $this->addPaginatorEntry(
                        $entries,
                        "...",        //$caption
                        $i,          //$pageNumber
                        false,       //$enabled    
                        false    //$active
                    );       
                    $rightDotsPresent=true;  
                }        
            }
            else
            {
                $this->addPaginatorEntry(
                    $entries,
                    "$i",        //$caption
                    $i,          //$pageNumber
                    true,       //$enabled    
                    $page==$i    //$active
                );
            }
        
        }
        /*
        if ($lastPage>1)
        {
            $this->addPaginatorEntry(
                $entries,
                '2',        //$caption
                2,          //$pageNumber
                true,       //$enabled    
                $page==2    //$active
            );            
        }
        if ($page>5)
        {
            $this->addPaginatorEntry(
                $entries,
                '...',        //$caption
                1,          //$pageNumber
                false,       //$enabled    
                false    //$active
            );
        }
        $startPage=$page>=5? $page-2 : 3;
        $endPage=  ($page+2)<=($lastPage-2)? $page+2 : ($lastPage-2);
        for ($i=$startPage;$i<=$endPage;$i++)      
        {
            $this->addPaginatorEntry(
                $entries,
                (string)$i,        //$caption
                $i,          //$pageNumber
                true,       //$enabled    
                $page==$i    //$active
            );                   
        }
        
        if ($page< ($lastPage-4))
        {
            $this->addPaginatorEntry(
                $entries,
                '...',        //$caption
                1,          //$pageNumber
                false,       //$enabled    
                false    //$active
            );
        }
        if ($lastPage>4)
        {
            $this->addPaginatorEntry(
                $entries,
                (string)($lastPage-1),  //$caption
                ($lastPage-1),          //$pageNumber
                true,                   //$enabled    
                $page==($lastPage-1)    //$active
            );
        }
        if ($lastPage>3)
        {
            $this->addPaginatorEntry(
                $entries,
                (string)($lastPage),  //$caption
                ($lastPage),          //$pageNumber
                true,                   //$enabled    
                $page==($lastPage)    //$active
            );
        }  
        */

   
        return $entries;     
    }

    public function renderPaginator( $paginator, $baseRoute)
    {
        if (!$paginator instanceof Paginator) return '';
        $pageSize=$paginator->getQuery()->getMaxResults();
        $lastPage=ceil(count($paginator)/$pageSize);
        if ($lastPage==1) return '';
        $entries=$this->buildPaginatorEntries($paginator);
        if (count($entries)==0) return '';
        $result='';
         $result.='<nav aria-label="..."><ul class="pagination">';
        $correctedRoute =substr($baseRoute,-1)=='/'? substr($baseRoute,0,-1):$baseRoute;
        foreach ($entries as $entry)
        {
            $result.='<li';
            if (!$entry['enabled']) $result.=' class="disabled"';
            if ($entry['active'])  $result.=' class="active"';
            $result.='><a href="'.$correctedRoute.'/'.$entry['pageNumber'].'">'.$entry['caption'].'</a></li>';
        }
        $result.='</ul></nav>';
        return $result;
    }
}
