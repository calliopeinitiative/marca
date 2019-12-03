<?php

namespace Marca\PortfolioBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Marca\PortfolioBundle\Entity\Portitem;
use Marca\PortfolioBundle\Entity\Portset;
use Marca\PortfolioBundle\Form\PortsetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Portset controller.
 *
 * @Route("/portset")
 */
class PortsetController extends Controller
{
    /**
     * Lists all Portset entities.
     *
     * @Route("/", name="portset")
     */
    public function indexAction()
    {
        $em = $this->getEm();

        $portsets = $em->getRepository('MarcaPortfolioBundle:Portset')->findAll();

        return $this->render('MarcaPortfolioBundle:Portset:index.html.twig', array(
            'portsets' => $portsets
        ));
    }

    /**
     * Finds and displays a Portset entity.
     *
     * @Route("/{id}/show", name="portset_show")
     */
    public function showAction($id)
    {
        $em = $this->getEm();

        $portset = $em->getRepository('MarcaPortfolioBundle:Portset')->find($id);
        $portitems = $em->getRepository('MarcaPortfolioBundle:Portitem')->findByPortset($portset);
        if (!$portset) {
            throw $this->createNotFoundException('Unable to find Portset entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MarcaPortfolioBundle:Portset:show.html.twig', array(
            'portset'      => $portset,
            'portitems'    => $portitems,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to create a new Portset entity.
     *
     * @Route("/new", name="portset_new")
     */
    public function newAction()
    {
        $user = $this->getUser();
        $portset = new Portset();
        $portset->setDescription('<p></p>');
        $portset->setUser($user);

        $form   = $this->createForm(new PortsetType(), $portset);

        return $this->render('MarcaPortfolioBundle:Portset:new.html.twig', array(
            'portset' => $portset,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new Portset entity.
     *
     * @Route("/create", name="portset_create", methods={"POST"})
     */
    public function createAction()
    {
        $em = $this->getEm();
        $user = $this->getUser();
        $portset  = new Portset();
        $portset->setUser($user);
        
        $portitem = new Portitem();
        $portitem->setPortset($portset);
        
        $request = $this->getRequest();
        $form    = $this->createForm(new PortsetType(), $portset);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($portset);
            $em->persist($portitem);
            $em->flush();

            return $this->redirect($this->generateUrl('portset_show', array('id' => $portset->getId())));
            
        }
        return $this->render('MarcaPortfolioBundle:Portset:new.html.twig', array(
            'portset' => $portset,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Portset entity.
     *
     * @Route("/{id}/edit", name="portset_edit")
     */
    public function editAction($id)
    {
        $em = $this->getEm();

        $portset = $em->getRepository('MarcaPortfolioBundle:Portset')->find($id);

        if (!$portset) {
            throw $this->createNotFoundException('Unable to find Portset entity.');
        }

        $editForm = $this->createForm(new PortsetType(), $portset);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MarcaPortfolioBundle:Portset:edit.html.twig', array(
            'portset'      => $portset,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Portset entity.
     *
     * @Route("/{id}/update", name="portset_update", methods={"POST"})
     */
    public function updateAction($id)
    {
        $em = $this->getEm();

        $portset = $em->getRepository('MarcaPortfolioBundle:Portset')->find($id);

        if (!$portset) {
            throw $this->createNotFoundException('Unable to find Portset entity.');
        }

        $editForm   = $this->createForm(new PortsetType(), $portset);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($portset);
            $em->flush();

            return $this->redirect($this->generateUrl('portset_show', array('id' => $id)));
        }

        return $this->render('MarcaPortfolioBundle:Portset:edit.html.twig', array(
            'portset'      => $portset,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Portset entity.
     *
     * @Route("/{id}/delete", name="portset_delete", methods={"DELETE"})
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $portset = $em->getRepository('MarcaPortfolioBundle:Portset')->find($id);

            if (!$portset) {
                throw $this->createNotFoundException('Unable to find Portset entity.');
            }

            $em->remove($portset);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('portset'));
    }

    /**
     * Creates a form to delete a Journal entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('portset_delete', array('id' => $id)))
            ->setMethod('POST')
            ->add('submit', SubmitType::class, array('label' => 'Yes','attr' => array('class' => 'btn btn-danger'),))
            ->getForm()
            ;
    }
}
