<?php

namespace Marca\TagBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Marca\TagBundle\Entity\Tagset;
use Marca\TagBundle\Form\TagsetType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Tagset controller.
 *
 * @Route("/tagset")
 */
class TagsetController extends Controller
{
    /**
     * Lists all Tagset entities.
     * @Route("/", name="tagset")
     */
    public function indexAction()
    {
        $em = $this->getEm();
        $user = $this->getUser();
        $tagsets = $em->getRepository('MarcaTagBundle:Tagset')->findTagsetByUser($user);

        return $this->render('MarcaTagBundle:Tagset:index.html.twig',array(
            'tagsets' => $tagsets
        ));
    }

    /**
     * Finds and displays a Tagset entity.
     *
     * @Route("/{id}/show", name="tagset_show")
     */
    public function showAction($id)
    {
        $em = $this->getEm();

        $tagset = $em->getRepository('MarcaTagBundle:Tagset')->find($id);

        if (!$tagset) {
            throw $this->createNotFoundException('Unable to find Tagset entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MarcaTagBundle:Tagset:show.html.twig',array(
            'tagset'      => $tagset,
            'delete_form' => $deleteForm->createView(),
        ));
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
        $form   = $this->createForm(TagsetType::class, $tagset);

        return $this->render('MarcaTagBundle:Tagset:new.html.twig',array(
            'tagset' => $tagset,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new Tagset entity.
     *
     * @Route("/create", name="tagset_create")
     * @Method("post")
     * @Template("MarcaTagBundle:Tagset:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $em = $this->getEm();
        $user = $this->getUser();
        $tagset  = new Tagset();
        $tagset->setUser($user);
        $form    = $this->createForm(TagsetType::class, $tagset);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($tagset);
            $em->flush();
            return $this->redirect($this->generateUrl('tagset', array('id' => $tagset->getId())));
        }

        return $this->render('MarcaTagBundle:Tagset:new.html.twig',array(
            'tagset' => $tagset,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Tagset entity.
     *
     * @Route("/{id}/edit", name="tagset_edit")
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getEm();

        $tagset = $em->getRepository('MarcaTagBundle:Tagset')->find($id);

        if (!$tagset) {
            throw $this->createNotFoundException('Unable to find Tagset tagset.');
        }

        $editForm = $this->createForm(TagsetType::class, $tagset);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MarcaTagBundle:Tagset:edit.html.twig',array(
            'tagset'      => $tagset,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Tagset entity.
     * @Route("/{id}/update", name="tagset_update")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getEm();

        $tagset = $em->getRepository('MarcaTagBundle:Tagset')->find($id);

        if (!$tagset) {
            throw $this->createNotFoundException('Unable to find Tagset entity.');
        }

        $editForm   = $this->createForm(TagsetType::class, $tagset);
        $deleteForm = $this->createDeleteForm($id);

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($tagset);
            $em->flush();

            return $this->redirect($this->generateUrl('tagset'));
        }

        return $this->render('MarcaTagBundle:Tagset:edit.html.twig',array(
            'tagset'      => $tagset,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Tagset entity.
     * @Route("/{id}/delete", name="tagset_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);

        $form->handleRequest($request);

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
            ->add('id', HiddenType::class)
            ->getForm()
        ;
    }
}
