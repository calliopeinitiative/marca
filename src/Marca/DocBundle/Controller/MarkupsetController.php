<?php

namespace Marca\DocBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\DocBundle\Entity\Markupset;
use Marca\DocBundle\Form\MarkupsetType;

/**
 * Markupset controller.
 *
 * @Route("/markupset")
 */
class MarkupsetController extends Controller
{
    /**
     * Lists all Markupset entities.
     *
     * @Route("/", name="markupset")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getEm();
        $user = $this->getUser();
        
        $markupsets = $em->getRepository('MarcaDocBundle:Markupset')->findMarkupSetsByUser($user);
        //$markupsets = $user->getMarkupsets();
        return array('markupsets' => $markupsets);
    }

    /**
     * Finds and displays a Markupset entity.
     *
     * @Route("/{id}/show", name="markupset_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getEm();
        $markupset = $em->getRepository('MarcaDocBundle:Markupset')->find($id);

        if (!$markupset) {
            throw $this->createNotFoundException('Unable to find Markupset entity.');
        }

        return array('markupset'=> $markupset,);
    }

    /**
     * Displays a form to create a new Markupset entity.
     *
     * @Route("/new", name="markupset_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Markupset();
        $form   = $this->createForm(new MarkupsetType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Markupset entity.
     *
     * @Route("/create", name="markupset_create")
     * @Method("post")
     * @Template("MarcaDocBundle:Markupset:new.html.twig")
     */
    public function createAction()
    {
        $em = $this->getEm();
        $user = $this->getUser();
        $markupset  = new Markupset();
        $markupset->setOwner($user);
        $request = $this->getRequest();
        $form    = $this->createForm(new MarkupsetType(), $markupset);
        $form->bind($request);
        
        
        $entity  = new Markupset();
        $request = $this->getRequest();
        $form    = $this->createForm(new MarkupsetType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($markupset);
            $em->flush();

            return $this->redirect($this->generateUrl('markupset', array('id' => $markupset->getId())));
            
        }

        return array(
            'markupset' => $markupset,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Markupset entity.
     *
     * @Route("/{id}/edit", name="markupset_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getEm();

        $markupset = $em->getRepository('MarcaDocBundle:Markupset')->find($id);

        if (!$markupset) {
            throw $this->createNotFoundException('Unable to find Markupset entity.');
        }

        $editForm = $this->createForm(new MarkupsetType(), $markupset);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'markupset'      => $markupset,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Markupset entity.
     *
     * @Route("/{id}/update", name="markupset_update")
     * @Method("post")
     * @Template("MarcaDocBundle:Markupset:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getEm();

        $markupset = $em->getRepository('MarcaDocBundle:Markupset')->find($id);

        if (!$markupset) {
            throw $this->createNotFoundException('Unable to find Markupset entity.');
        }

        $editForm   = $this->createForm(new MarkupsetType(), $markupset);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($markupset);
            $em->flush();

            return $this->redirect($this->generateUrl('markupset', array('id' => $markupset->getid())));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Markupset entity.
     *
     * @Route("/{id}/delete", name="markupset_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $entity = $em->getRepository('MarcaDocBundle:Markupset')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Markupset entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('markupset'));
    }
    
    /**
     * Allow user to browse for new Markupsets
     * @Route("/find", name="find_markupset")
     * @Template("MarcaDocBundle:Markupset:findMarkupset.html.twig")
     */
    public function findMarkupsetAction()
    {
        $em = $this->getEm();
        $markupsets = $em->getRepository("MarcaDocBundle:Markupset")->findByShared(1);

        return array(
            'markupsets'=>$markupsets
        ); 
    }
    
    /**
     * Allow user to add shared tagset to his/her tagsets
     * @Route("/add/{id}", name="add_markupset")
     */
    public function addMarkupsetAction($id)
    {
        $user = $this->getUser();
        $em = $this->getEm();
        $markupset = $em->getRepository('MarcaDocBundle:Markupset')->find($id);
        $markupset->addUser($user);
        
        $em->persist($markupset);
        $em->flush();
        
        return $this->redirect($this->generateUrl('find_markupset'));
    }
    
    /**
     * Allow user to remove shared tagset from his/her tagsets
     * @Route("/remove/{id}", name="remove_markupset")
     */
    public function removeMarkupsetAction($id)
    {
        $user = $this->getUser();
        $em = $this->getEm();
        $markupset = $em->getRepository('MarcaDocBundle:Markupset')->find($id);
        $markupset->removeUser($user);
        
        $em->persist($markupset);
        $em->flush();
        
        return $this->redirect($this->generateUrl('find_markupset'));
    }
    
     /**
     * Add outside tags to a set
     * @Route("/add_tags/{id}", name="add_tags")
     * @Template("MarcaDocBundle:Markupset:addTagsMarkupset.html.twig")
     */
     public function addTagsAction($id)
     {
         $em = $this->getEm();
         $currentset = $em->getRepository('MarcaDocBundle:Markupset')->find($id);
         $user = $this->getUser();
         $allsets = $user->getMarkupsets();
         
         return array("currentset"=>$currentset, 
                      "allsets"=>$allsets);
     }
     
     /**
     * Add tag to a set
     * @Route("/add_tag_to_set/{set_id}/{tag_id}", name="add_tag_to_set")
     */
     public function addTagToSetAction($set_id, $tag_id)
     {
         $em = $this->getEm();
         $markupset = $em->getRepository('MarcaDocBundle:Markupset')->find($set_id);
         $tag = $em->getRepository('MarcaDocBundle:Markup')->find($tag_id);
         $tag->addMarkupset($markupset);
         
         $em->persist($tag);
         $em->flush();
         
         return $this->redirect($this->generateUrl('add_tags', array('id' => $markupset->getid())));
         
     }

     /**
     * Remove tag from a set
     * @Route("/remove_tag_from_set/{set_id}/{tag_id}", name="remove_tag_from_set")
     */
     public function removeTagFromSetAction($set_id, $tag_id)
     {
         $em = $this->getEm();
         $markupset = $em->getRepository('MarcaDocBundle:Markupset')->find($set_id);
         $tag = $em->getRepository('MarcaDocBundle:Markup')->find($tag_id);
         $tag->removeMarkupset($markupset);
         $markupset->removeMarkup($tag);
         
         $em->persist($tag);
         $em->persist($markupset);
         $em->flush();
         
         return $this->redirect($this->generateUrl('add_tags', array('id' => $markupset->getid())));
         
     }


     private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
