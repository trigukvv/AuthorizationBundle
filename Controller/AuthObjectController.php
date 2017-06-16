<?php

namespace triguk\AuthorizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use triguk\AuthorizationBundle\Entity\AuthObject;
use triguk\AuthorizationBundle\Form\AuthObjectType;

/**
 * AuthObject controller.
 *
 * @Route("/admin/authobject")
 */
class AuthObjectController extends AuthBaseController
{
    
    public function __construct()
    {
        $this->entityClass=AuthObject::class;
        $this->templatePrefix='authobject';
        $this->indexTitle='Список объектов веб-приложения';
        $this->newTitle='Создание объекта';
        $this->showTitle='Просмотр объекта';
        $this->editTitle='Редактирование объекта';
        $this->setDefaultAdminPaths();
        $this->entityProperties[]=
            [
                "name"=>"titleSingular",
                "label"=>"Подпись (ед. ч.)"
            ];
        $this->entityProperties[]=
            [
                "name"=>"titlePlural",
                "label"=>"Подпись (мн. ч.)"
            ];
        $this->entityProperties[]=
            [
                "name"=>"route",
                "label"=>"Маршрут"
            ];

    }
        
    
    
    /**
     * Lists all AuthObject entities.
     *
     * @Route("/", name="admin_authobject_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        return $this->baseIndexAction($request);
    }

    /**
     * Creates a new AuthObject entity.
     *
     * @Route("/new", name="admin_authobject_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        return $this->baseNewAction($request);
    }

    /**
     * Finds and displays a AuthObject entity.
     *
     * @Route("/{id}", name="admin_authobject_show")
     * @Method("GET")
     */
    public function showAction(AuthObject $authObject)
    {
        return $this->baseShowAction($authObject);
    }

    /**
     * Displays a form to edit an existing AuthObject entity.
     *
     * @Route("/{id}/edit", name="admin_authobject_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, AuthObject $authObject)
    {
        return $this->baseEditAction($request,$authObject);
    }

    /**
     * Deletes a AuthObject entity.
     *
     * @Route("/{id}", name="admin_authobject_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, AuthObject $authObject)
    {
        return $this->baseDeleteAction($request,$authObject);
    }


}
