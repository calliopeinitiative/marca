<?php

namespace Marca\HomeBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\HomeBundle\Entity\Page;
use Marca\HomeBundle\Form\PageType;

/**
 * Page controller.
 *
 * @Route("/page")
 */
class PageController extends Controller
{
    /**
     * Lists all Page entities.
     *
     * @Route("/", name="page")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getEm();

        $pages = $em->getRepository('MarcaHomeBundle:Page')->findAll();

        return array('pages' => $pages);
    }

    /**
     * Finds and displays a Page entity.
     *
     * @Route("/{id}/show", name="page_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getEm();

        $page = $em->getRepository('MarcaHomeBundle:Page')->find($id);

        if (!$page) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'page'      => $page,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Page entity.
     *
     * @Route("/new", name="page_new")
     * @Template()
     */
    public function newAction()
    {
        $page = new Page();
        $page->setBody('<p></p>');
        $form   = $this->createForm(new PageType(), $page);

        return array(
            'page' => $page,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Page entity.
     *
     * @Route("/create", name="page_create")
     * @Method("post")
     * @Template("MarcaHomeBundle:Page:new.html.twig")
     */
    public function createAction()
    {
        $page  = new Page();
        $request = $this->getRequest();
        $form    = $this->createForm(new PageType(), $page);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($page);
            $em->flush();

            return $this->redirect($this->generateUrl('page_show', array('id' => $page->getId())));
            
        }

        return array(
            'page' => $page,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Page entity.
     *
     * @Route("/{id}/edit", name="page_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getEm();

        $page = $em->getRepository('MarcaHomeBundle:Page')->find($id);

        if (!$page) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $editForm = $this->createForm(new PageType(), $page);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'page'      => $page,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Page entity.
     *
     * @Route("/{id}/update", name="page_update")
     * @Method("post")
     * @Template("MarcaHomeBundle:Page:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getEm();

        $page = $em->getRepository('MarcaHomeBundle:Page')->find($id);

        if (!$page) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $editForm   = $this->createForm(new PageType(), $page);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($page);
            $em->flush();

            return $this->redirect($this->generateUrl('page_show', array('id' => $id)));
        }

        return array(
            'page'      => $page,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Page entity.
     *
     * @Route("/{id}/delete", name="page_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $page = $em->getRepository('MarcaHomeBundle:Page')->find($id);

            if (!$page) {
                throw $this->createNotFoundException('Unable to find Page entity.');
            }

            $em->remove($page);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('page'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
