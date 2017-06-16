<?php

namespace triguk\AuthorizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use triguk\AuthorizationBundle\Entity\AuthRole;
use triguk\AuthorizationBundle\Entity\AuthObject;
use triguk\AuthorizationBundle\Entity\AuthScope;
use triguk\AuthorizationBundle\Entity\AuthGrant;
use triguk\AuthorizationBundle\Formatter\AuthGrantFormatter;

#use triguk\AuthorizationBundle\Form\AuthGrantType;

/**
 * AuthGrant controller.
 *
 * @Route("/admin/authgrant")
 */
class AuthGrantController extends Controller
{
    protected $entityClass=AuthGrant::class;

    /**
     * Lists all AuthGrant entities.
     *
     * @Route("/", name="admin_authgrant_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        
        $this->denyAccessUnlessGranted('listAll',$this->entityClass);
        $manager = $this->getDoctrine()->getManager();

        $authGrants = $manager->getRepository(AuthGrant::class)->findAll();
        $formatter=$this->get('triguk.formatter.authgrantformatter');
        $authGrantList=$formatter->groupAuthGrants($authGrants);
        return $this->render('trigukAuthorizationBundle:authgrant:index.html.twig', array(
            'authGrants' => $authGrantList,
        ));
    }

    /**
     * Creates a new AuthGrant entity.
     *
     * @Route("/new", name="admin_authgrant_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        
        $this->denyAccessUnlessGranted('create',$this->entityClass);
        //$authGrant = new AuthGrant();
        $grants=[];
        $form = $this->createForm('triguk\AuthorizationBundle\Form\AuthGrantType', $grants);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            
            $formData=$form->getData();
            $role=$formData['authRole'];
            $object=$formData['authObject'];
            $scope=$formData['authScope'];
            $permissions=$formData['authPermission'];
            $repo=$this->getDoctrine()->getRepository(AuthGrant::class);
            //$repo->clearRoleObjectScopeGrants($role,$object,$scope);
            $repo->addRoleObjectScopeGrants($role,$object,$scope,$permissions);
            return $this->redirectToRoute('admin_authgrant_index');
        }

        return $this->render('trigukAuthorizationBundle:authgrant:new.html.twig', array(
            //'authGrant' => $authGrant,
            'form' => $form->createView(),
        ));
    }
    
    /**
     * Displays a form to edit an existing AuthGrant entity.
     *
     * @Route("/{role_id}/{object_id}/{scope_id}/edit", name="admin_authgrant_edit")
     * @ParamConverter("role", class="trigukAuthorizationBundle:AuthRole", options={"id" = "role_id"})
     * @ParamConverter("object", class="trigukAuthorizationBundle:AuthObject", options={"id" = "object_id"})
     * @ParamConverter("scope", class="trigukAuthorizationBundle:AuthScope", options={"id" = "scope_id"})
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request,AuthRole $role, AuthObject $object, AuthScope $scope)
    {
        $this->denyAccessUnlessGranted('update',$item);
        $repo=$this->getDoctrine()->getRepository(AuthGrant::class);
        $grants=$repo->findByRoleObjectScope($role,$object,$scope);
        $permissions=[];
        foreach ($grants as $grant) $permissions[]=$grant->getAuthPermission();       
            
        $items=['authRole'=>$role,'authObject'=>$object,'authScope'=>$scope,
            'authPermission'=>new ArrayCollection($permissions)];
        //var_dump([$role.'',$object.'',$scope.'']);
        $form = $this->createForm('triguk\AuthorizationBundle\Form\AuthGrantType', $items);  
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            
            $formData=$form->getData();
            $permissions=$formData['authPermission'];
            //$repo->clearRoleObjectScopeGrants($role,$object,$scope);
            $repo->syncRoleObjectScopeGrants($role,$object,$scope,$permissions);
            return $this->redirectToRoute('admin_authgrant_index');
        }
        
        return $this->render('trigukAuthorizationBundle:authgrant:edit.html.twig', array(
            //'authGrant' => $authGrant,
            'form' => $form->createView(),
        ));
               
    }

}
