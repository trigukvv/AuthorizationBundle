<?php

namespace triguk\AuthorizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use triguk\AuthorizationBundle\Entity\AuthRole;
use triguk\AuthorizationBundle\Form\AuthRoleType;

/**
 * AuthRole controller.
 *
 * @Route("/admin/authrole")
 */
class AuthRoleController extends AuthBaseController
{
    
    public function __construct()
    {
        $this->entityClass=AuthRole::class;
        $this->templatePrefix='authrole';
        $this->indexTitle='Список ролей';
        $this->showTitle='Просмотр роли';
        $this->editTitle='Редактирование роли';
        $this->setDefaultAdminPaths();
    }
            
    
    /**
     * Lists all AuthRole entities.
     *
     * @Route("/", name="admin_authrole_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        return $this->baseIndexAction($request);
    }
    /**
     * Creates a new AuthRole entity.
     *
     * @Route("/new", name="admin_authrole_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        return $this->baseNewAction($request);
    }

    /**
     * Finds and displays a AuthRole entity.
     *
     * @Route("/{id}", name="admin_authrole_show")
     * @Method("GET")
     */
    public function showAction(AuthRole $authRole)
    {
        return $this->baseShowAction($authRole);
    }

    /**
     * Displays a form to edit an existing AuthRole entity.
     *
     * @Route("/{id}/edit", name="admin_authrole_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, AuthRole $authRole)
    {
        return $this->baseEditAction($request, $authRole);
    }

    /**
     * Deletes a AuthRole entity.
     *
     * @Route("/{id}", name="admin_authrole_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, AuthRole $authRole)
    {
        return $this->baseDeleteAction($request, $authRole);
    }


}
