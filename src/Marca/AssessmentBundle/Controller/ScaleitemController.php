<?php

namespace Marca\AssessmentBundle\Controller;

use Marca\AssessmentBundle\Entity\Scaleitem;
use Marca\AssessmentBundle\Form\ScaleitemType;
use Marca\HomeBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Scaleitem controller.
 *
 * @Route("/scaleitem")
 */
class ScaleitemController extends Controller
{

    /**
     * Displays a form to create a new Scaleitem entity.
     *
     * @Route("/{scaleid}/new", name="scaleitem_new")
     */
    public function newAction($scaleid)
    {
        $scaleitem = new Scaleitem();
        $form   = $this->createForm(ScaleitemType::class, $scaleitem);

        return $this->render('MarcaAssessmentBundle:Scaleitem:new.html.twig', array(
            'scaleitem' => $scaleitem,
            'scaleid' => $scaleid,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new Scaleitem entity.
     *
     * @Route("/{scaleid}/create", name="scaleitem_create")
     * @Method("post")
     */
    public function createAction($scaleid, Request $request)
    {
        $em = $this->getEm();
        $scale = $em->getRepository('MarcaAssessmentBundle:Scale')->find($scaleid);
        
        $scaleitem  = new Scaleitem();
        $scaleitem->setScale($scale);
        $form    = $this->createForm(ScaleitemType::class, $scaleitem);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($scaleitem);
            $em->flush();

            return $this->redirect($this->generateUrl('scale_show', array('id' => $scaleid)));
            
        }

        return $this->render('MarcaAssessmentBundle:Scaleitem:new.html.twig', array(
            'scaleitem' => $scaleitem,
            'scaleid' => $scaleid,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Scaleitem entity.
     *
     * @Route("/{id}/edit", name="scaleitem_edit")
     */
    public function editAction($id)
    {
        $em = $this->getEm();

        $scaleitem = $em->getRepository('MarcaAssessmentBundle:Scaleitem')->find($id);

        if (!$scaleitem) {
            throw $this->createNotFoundException('Unable to find Scaleitem entity.');
        }

        $editForm = $this->createForm(ScaleitemType::class, $scaleitem);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MarcaAssessmentBundle:Scaleitem:edit.html.twig', array(
            'scaleitem'      => $scaleitem,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Scaleitem entity.
     *
     * @Route("/{id}/update", name="scaleitem_update")
     * @Method("post")
     */
    public function updateAction($id,  Request $request)
    {
        $em = $this->getEm();

        $scaleitem = $em->getRepository('MarcaAssessmentBundle:Scaleitem')->find($id);
        $scaleid = $scaleitem->getScale()->getId();



        if (!$scaleitem) {
            throw $this->createNotFoundException('Unable to find Scaleitem entity.');
        }

        $editForm   = $this->createForm(ScaleitemType::class, $scaleitem);
        $deleteForm = $this->createDeleteForm($id);


        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($scaleitem);
            $em->flush();

            return $this->redirect($this->generateUrl('scale_show', array('id' => $scaleid)));
        }

        return $this->render('MarcaAssessmentBundle:Scaleitem:edit.html.twig:', array(
            'scaleitem'      => $scaleitem,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Scaleitem entity.
     *
     * @Route("/{id}/delete", name="scaleitem_delete")
     * @Method("post")
     */
    public function deleteAction($id, Request $request)
    {
        $form = $this->createDeleteForm($id);

        $form->handleRequest($request);

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
            ->add('id', HiddenType::class)
            ->getForm()
        ;
    }
}
