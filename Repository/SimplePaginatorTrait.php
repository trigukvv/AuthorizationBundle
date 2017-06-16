<?php

namespace triguk\AuthorizationBundle\Repository;


use Doctrine\ORM\Tools\Pagination\Paginator;


trait SimplePaginatorTrait
{
    public function getSimplePaginator($page=1,$pageSize=20)
    {
        $query = $this
            ->createQueryBuilder('a')
            ->setFirstResult($pageSize * ($page-1))
            ->setMaxResults($pageSize);  
        $paginator = new Paginator($query);
        if ($page>1)
        {
            $paginator
                ->getQuery()
                ->setFirstResult($pageSize * ($page-1)) 
                ->setMaxResults($pageSize);
        }   
        return $paginator;
    }
}
