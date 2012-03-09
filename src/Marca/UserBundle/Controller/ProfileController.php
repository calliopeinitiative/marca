<?php

namespace Marca\UserBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\UserBundle\Entity\Profile;
use Marca\UserBundle\Form\ProfileType;

/**
 * Profile controller.
 *
 * @Route("/user_profile")
 */
class ProfileController extends Controller
{
    /**
     * Lists all Profile entities.
     *
     * @Route("/", name="user_profile")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
        $entities = $em->getRepository('MarcaUserBundle:Profile')->findAll();
        return array('entities' => $entities);
        } else {          
        $username = $this->get('security.context')->getToken()->getUsername();
        $userid = $em->getRepository('MarcaUserBundle:Profile')->findOneByUsername($username);
        if (!$userid){
        return $this->redirect($this->generateUrl('user_profile_new'));    
        }
        $userid = $userid->getId();  
        return $this->redirect($this->generateUrl('user_profile_edit', array('id' => $userid)));
        }
    }

    /**
     * Finds and displays a Profile entity.
     *
     * @Route("/{id}/show", name="user_profile_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('MarcaUserBundle:Profile')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Profile entity.');
        }
        return array('entity' => $entity);
    }

    /**
     * Displays a form to create a new Profile entity.
     *
     * @Route("/new", name="user_profile_new")
     * @Template()
     */
    public function newAction()
    {
        $username = $this->get('security.context')->getToken()->getUsername();
        
        $userManager = $this->get('fos_user.user_manager');
        $userEmail = $userManager->findUserByUsername($username)->getEmail();
        
        $entity = new Profile();
        $entity->setUsername($username);
        $entity->setEmail($userEmail);
        $form   = $this->createForm(new ProfileType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Profile entity.
     *
     * @Route("/create", name="user_profile_create")
     * @Method("post")
     * @Template("MarcaUserBundle:Profile:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Profile();
        $request = $this->getRequest();
        $form    = $this->createForm(new ProfileType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('user_profile_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Profile entity.
     *
     * @Route("/{id}/edit", name="user_profile_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MarcaUserBundle:Profile')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Profile entity.');
        }

        $editForm = $this->createForm(new ProfileType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Profile entity.
     *
     * @Route("/{id}/update", name="user_profile_update")
     * @Method("post")
     * @Template("MarcaUserBundle:Profile:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MarcaUserBundle:Profile')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Profile entity.');
        }

        $editForm   = $this->createForm(new ProfileType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('user_profile_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Profile entity.
     *
     * @Route("/{id}/delete", name="user_profile_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('MarcaUserBundle:Profile')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Profile entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('user_profile'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
