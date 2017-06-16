<?php

namespace triguk\AuthorizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use triguk\AuthorizationBundle\Entity\AuthScope;
use triguk\AuthorizationBundle\Form\AuthScopeType;

/**
 * AuthScope controller.
 *
 * @Route("/admin/authscope")
 */
class AuthScopeController extends AuthBaseController
{
    
    public function __construct()
    {
        $this->entityClass=AuthScope::class;
        $this->templatePrefix='authscope';
        $this->indexTitle='Список диапазонов';
        $this->showTitle='Просмотр масштаба';
        $this->editTitle='Редактирование масштаба';
        $this->setDefaultAdminPaths();        
    }
    
    /**
     * Lists all AuthScope entities.
     *
     * @Route("/", name="admin_authscope_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        return $this->baseIndexAction($request);
    }

    /**
     * Creates a new AuthScope entity.
     *
     * @Route("/new", name="admin_authscope_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        return $this->baseNewAction($request);

    }

    /**
     * Finds and displays a AuthScope entity.
     *
     * @Route("/{id}", name="admin_authscope_show")
     * @Method("GET")
     */
    public function showAction(AuthScope $authScope)
    {
        return $this->baseShowAction($authScope);
    }

    /**
     * Displays a form to edit an existing AuthScope entity.
     *
     * @Route("/{id}/edit", name="admin_authscope_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, AuthScope $authScope)
    {
        return $this->baseEditAction($request, $authScope);
    }

    /**
     * Deletes a AuthScope entity.
     *
     * @Route("/{id}", name="admin_authscope_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, AuthScope $authScope)
    {
        return $this->baseDeleteAction($request, $authScope);
    }


}
