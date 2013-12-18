<?php

namespace Marca\TagBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\TagBundle\Entity\Tagset;
use Marca\TagBundle\Form\TagsetType;

/**
 * Tagset controller.
 *
 * @Route("/tagset")
 */
class TagsetController extends Controller
{
    /**
     * Lists all Tagset entities.
     *
     * @Route("/", name="tagset")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getEm();
        $user = $this->getUser();
        $tagsets = $em->getRepository('MarcaTagBundle:Tagset')->findTagsetByUser($user);

        return array('tagsets' => $tagsets);
    }

    /**
     * Finds and displays a Tagset entity.
     *
     * @Route("/{id}/show", name="tagset_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getEm();

        $tagset = $em->getRepository('MarcaTagBundle:Tagset')->find($id);

        if (!$tagset) {
            throw $this->createNotFoundException('Unable to find Tagset entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'tagset'      => $tagset,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Tagset entity.
     *
     * @Route("/new", name="tagset_new")
     * @Template()
     */
    public function newAction()
    {
        $tagset = new Tagset();
        $form   = $this->createForm(new TagsetType(), $tagset);

        return array(
            'tagset' => $tagset,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Tagset entity.
     *
     * @Route("/create", name="tagset_create")
     * @Method("post")
     * @Template("MarcaTagBundle:Tagset:new.html.twig")
     */
    public function createAction()
    {
        $em = $this->getEm();
        $user = $this->getUser();
        $tagset  = new Tagset();
        $tagset->setUser($user);
        $request = $this->getRequest();
        $form    = $this->createForm(new TagsetType(), $tagset);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($tagset);
            $em->flush();

            return $this->redirect($this->generateUrl('tagset', array('id' => $tagset->getId())));
            
        }

        return array(
            'tagset' => $tagset,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Tagset entity.
     *
     * @Route("/{id}/edit", name="tagset_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getEm();

        $tagset = $em->getRepository('MarcaTagBundle:Tagset')->find($id);

        if (!$tagset) {
            throw $this->createNotFoundException('Unable to find Tagset tagset.');
        }

        $editForm = $this->createForm(new TagsetType(), $tagset);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'tagset'      => $tagset,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Tagset entity.
     *
     * @Route("/{id}/update", name="tagset_update")
     * @Method("post")
     * @Template("MarcaTagBundle:Tagset:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getEm();

        $tagset = $em->getRepository('MarcaTagBundle:Tagset')->find($id);

        if (!$tagset) {
            throw $this->createNotFoundException('Unable to find Tagset entity.');
        }

        $editForm   = $this->createForm(new TagsetType(), $tagset);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($tagset);
            $em->flush();

            return $this->redirect($this->generateUrl('tagset'));
        }

        return array(
            'tagset'      => $tagset,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Tagset entity.
     *
     * @Route("/{id}/delete", name="tagset_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $tagset = $em->getRepository('MarcaTagBundle:Tagset')->find($id);

            if (!$tagset) {
                throw $this->createNotFoundException('Unable to find Tagset entity.');
            }

            $em->remove($tagset);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('tagset'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
