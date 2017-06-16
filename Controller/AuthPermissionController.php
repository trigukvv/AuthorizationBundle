<?php

namespace triguk\AuthorizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use triguk\AuthorizationBundle\Entity\AuthPermission;
use triguk\AuthorizationBundle\Form\AuthPermissionType;

/**
 * AuthPermission controller.
 *
 * @Route("/admin/authpermission")
 */
class AuthPermissionController extends AuthBaseController
{
    
    public function __construct()
    {
        $this->entityClass=AuthPermission::class;
        $this->templatePrefix='authobject';
        $this->indexTitle='Список разрешений';
        $this->showTitle='Просмотр разрешения';
        $this->editTitle='Редактирование разрешения';
        $this->setDefaultAdminPaths();
    }
            
    
    /**
     * Lists all AuthPermission entities.
     *
     * @Route("/", name="admin_authpermission_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        return $this->baseIndexAction($request);
    }
    /**
     * Creates a new AuthPermission entity.
     *
     * @Route("/new", name="admin_authpermission_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        return $this->baseNewAction($request);
    }

    /**
     * Finds and displays a AuthPermission entity.
     *
     * @Route("/{id}", name="admin_authpermission_show")
     * @Method("GET")
     */
    public function showAction(AuthPermission $authPermission)
    {
        return $this->baseShowAction($authPermission);
    }

    /**
     * Displays a form to edit an existing AuthPermission entity.
     *
     * @Route("/{id}/edit", name="admin_authpermission_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, AuthPermission $authPermission)
    {
        return $this->baseEditAction($request, $authPermission);
    }

    /**
     * Deletes a AuthPermission entity.
     *
     * @Route("/{id}", name="admin_authpermission_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, AuthPermission $authPermission)
    {
        return $this->baseDeleteAction($request, $authPermission);
    }


}
