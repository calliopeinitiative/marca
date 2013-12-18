<?php

namespace Marca\AssessmentBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\AssessmentBundle\Entity\Scaleitem;
use Marca\AssessmentBundle\Form\ScaleitemType;

/**
 * Scaleitem controller.
 *
 * @Route("/scaleitem")
 */
class ScaleitemController extends Controller
{
    /**
     * Lists all Scaleitem entities.
     *
     * @Route("/", name="scaleitem")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getEm();

        $scaleitems = $em->getRepository('MarcaAssessmentBundle:Scaleitem')->findAll();

        return array('scaleitems' => $scaleitems);
    }

    /**
     * Finds and displays a Scaleitem entity.
     *
     * @Route("/{id}/show", name="scaleitem_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getEm();

        $scaleitem = $em->getRepository('MarcaAssessmentBundle:Scaleitem')->find($id);

        if (!$scaleitem) {
            throw $this->createNotFoundException('Unable to find Scaleitem entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'scaleitem'      => $scaleitem,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Scaleitem entity.
     *
     * @Route("/{scaleid}/new", name="scaleitem_new")
     * @Template()
     */
    public function newAction($scaleid)
    {
        $scaleitem = new Scaleitem();
        $form   = $this->createForm(new ScaleitemType(), $scaleitem);

        return array(
            'scaleitem' => $scaleitem,
            'scaleid' => $scaleid,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Scaleitem entity.
     *
     * @Route("/{scaleid}/create", name="scaleitem_create")
     * @Method("post")
     * @Template("MarcaAssessmentBundle:Scaleitem:new.html.twig")
     */
    public function createAction($scaleid)
    {
        $em = $this->getEm();
        $scale = $em->getRepository('MarcaAssessmentBundle:Scale')->find($scaleid);
        
        $scaleitem  = new Scaleitem();
        $scaleitem->setScale($scale);
        $request = $this->getRequest();
        $form    = $this->createForm(new ScaleitemType(), $scaleitem);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($scaleitem);
            $em->flush();

            return $this->redirect($this->generateUrl('scale_show', array('id' => $scaleid)));
            
        }

        return array(
            'scaleitem' => $scaleitem,
            'scaleid' => $scaleid,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Scaleitem entity.
     *
     * @Route("/{id}/edit", name="scaleitem_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getEm();

        $scaleitem = $em->getRepository('MarcaAssessmentBundle:Scaleitem')->find($id);

        if (!$scaleitem) {
            throw $this->createNotFoundException('Unable to find Scaleitem entity.');
        }

        $editForm = $this->createForm(new ScaleitemType(), $scaleitem);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'scaleitem'      => $scaleitem,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Scaleitem entity.
     *
     * @Route("/{id}/update", name="scaleitem_update")
     * @Method("post")
     * @Template("MarcaAssessmentBundle:Scaleitem:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getEm();

        $scaleitem = $em->getRepository('MarcaAssessmentBundle:Scaleitem')->find($id);
        $scaleid = $scaleitem->getScale()->getId();



        if (!$scaleitem) {
            throw $this->createNotFoundException('Unable to find Scaleitem entity.');
        }

        $editForm   = $this->createForm(new ScaleitemType(), $scaleitem);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($scaleitem);
            $em->flush();

            return $this->redirect($this->generateUrl('scale_show', array('id' => $scaleid)));
        }

        return array(
            'scaleitem'      => $scaleitem,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Scaleitem entity.
     *
     * @Route("/{id}/delete", name="scaleitem_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $scaleitem = $em->getRepository('MarcaAssessmentBundle:Scaleitem')->find($id);
            $scaleid = $scaleitem->getScale()->getId();

            if (!$scaleitem) {
                throw $this->createNotFoundException('Unable to find Scaleitem entity.');
            }

            $em->remove($scaleitem);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('scale_show', array('id' => $scaleid)));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
