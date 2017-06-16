<?php

namespace triguk\AuthorizationBundle\Repository;


interface SimplePaginatorInterface
{
    public function getSimplePaginator($page,$pageSize);
}
