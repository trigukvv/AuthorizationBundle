<?php

namespace triguk\AuthorizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use triguk\AuthorizationBundle\Entity\AuthScope;
use triguk\AuthorizationBundle\Form\AuthScopeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use triguk\AuthorizationBundle\Repository\SimplePaginatorInterface;

class AuthBaseController extends Controller
{
    protected $entityClass;
    
    protected $templatePrefix;
    
    protected $indexTitle="Список";
    
    protected $newTitle="Создание";
    
    protected $editTitle="Редактирование";
    
    protected $showTitle="Просмотр";
    
    protected $indexPath, $showPath, $editPath, $newPath, $deletePath;
    
    protected $entityProperties=
        [
            [
                "name"=>"name",
                "label"=>"Имя"
            ],
            [
                "name"=>"description",
                "label"=>"Описание"
            ]
        ];

            
    
    protected function setDefaultAdminPaths()
    {
        $this->indexPath="admin_{$this->templatePrefix}_index";  
        $this->showPath="admin_{$this->templatePrefix}_show";
        $this->editPath="admin_{$this->templatePrefix}_edit";
        $this->deletePath="admin_{$this->templatePrefix}_delete";
        $this->newPath="admin_{$this->templatePrefix}_new";        
        
    }

    protected function setDefaultPaths()
    {
        $this->indexPath="{$this->templatePrefix}_index";  
        $this->showPath="{$this->templatePrefix}_show";
        $this->editPath="{$this->templatePrefix}_edit";
        $this->deletePath="{$this->templatePrefix}_delete";
        $this->newPath="{$this->templatePrefix}_new";        
        
    }

    public function titleAction()
    {
        return $this->indexTitle();
    }
    protected function baseIndexAction(Request $request, $page=1,$items=null,$permission='listAll')
    {
        $this->denyAccessUnlessGranted($permission,$this->entityClass);
        
        $em = $this->getDoctrine()->getManager();
        $repository=$em->getRepository($this->entityClass);
        if (is_null($items))
        {
            if ($repository instanceof SimplePaginatorInterface)
            {
                $items=$repository->getSimplePaginator($page);
            }
            else
            {
                $items = $repository->findAll();
            }
        }
        return $this->render("trigukAuthorizationBundle:authtemplate:index.html.twig", array(
            "entityProperties"=>$this->entityProperties,
            "authObjects" => $items,
            "title"=>$this->indexTitle,
            "showPath"=>$this->showPath,
            "editPath"=>$this->editPath,
            "newPath"=>$this->newPath,
            "indexPath"=>$this->indexPath,
            'baseRoute'=> $this->generateUrl($request->get('_route')),
            
        ));
    }

    protected function getForm($item)
    {
        $formBuilder = $this->createFormBuilder($item);
        foreach ($this->entityProperties as $entityProperty)
        {
            $formBuilder->add($entityProperty['name'],null,array('label' => $entityProperty['label']));
        }
        $formBuilder->add("save",SubmitType::class, array('label' => 'Сохранить'));
        $form=$formBuilder->getForm();
        return $form;
    }
    
    protected function itemBeforeSave($item)
    {
        // By default do nothing with item.
    }
    
    protected function baseNewAction(Request $request)
    {
        $this->denyAccessUnlessGranted('create',$this->entityClass);
        $item = new $this->entityClass();
        //$form = $this->createForm("triguk\AuthorizationBundle\Form\AuthBaseFormType", $item);
        
        $form=$this->getForm($item);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $this->itemBeforeSave($item);
            $em->persist($item);
            $em->flush();

            return $this->redirectToRoute($this->showPath, array("id" => $item->getId()));
        }

        return $this->render("trigukAuthorizationBundle:authtemplate:new.html.twig", array(
            "authObject" => $item,
            "form" => $form->createView(),
            "title"=>$this->newTitle,
            "showPath"=>$this->showPath,
            "editPath"=>$this->editPath,
            "newPath"=>$this->newPath,
            "indexPath"=>$this->indexPath       
        ));
    }


    protected function baseShowAction($item)
    {
        $this->denyAccessUnlessGranted('read',$item);
        $deleteForm = $this->createDeleteForm($item);
        
        return $this->render("trigukAuthorizationBundle:authtemplate:show.html.twig", array(
            "entityProperties"=>$this->entityProperties,
            "authObject" => $item,
            "delete_form" => $deleteForm->createView(),
            "title"=>$this->showTitle,
            "showPath"=>$this->showPath,
            "editPath"=>$this->editPath,
            "newPath"=>$this->newPath,
            "indexPath"=>$this->indexPath               
        ));
    }


    protected function baseEditAction(Request $request, $item)
    {
        $this->denyAccessUnlessGranted('update',$item);
        $deleteForm = $this->createDeleteForm($item);
        //$editForm = $this->createForm("triguk\AuthorizationBundle\Form\AuthBaseFormType", $item);
        $editForm=$this->getForm($item);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $this->itemBeforeSave($item);
            $em->flush();

            return $this->redirectToRoute($this->editPath, array("id" => $item->getId()));
        }

        return $this->render("trigukAuthorizationBundle:authtemplate:edit.html.twig", array(
            "authScope" => $item,
            "edit_form" => $editForm->createView(),
            "delete_form" => $deleteForm->createView(),
            "title"=>$this->editTitle,
            "showPath"=>$this->showPath,
            "editPath"=>$this->editPath,
            "newPath"=>$this->newPath,
            "indexPath"=>$this->indexPath          
        ));
    }


    protected function baseDeleteAction(Request $request,  $item)
    {
        $this->denyAccessUnlessGranted('delete',$item);
        $form = $this->createDeleteForm($item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($item);
            $em->flush();
        }

        return $this->redirectToRoute($this->indexPath);
    }


    private function createDeleteForm($item)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl($this->deletePath, array("id" => $item->getId())))
            ->setMethod("DELETE")
            ->getForm()
        ;
    }
}
