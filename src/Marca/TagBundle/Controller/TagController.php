<?php

namespace Marca\TagBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Marca\TagBundle\Entity\Tag;
use Marca\TagBundle\Form\TagType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Tag controller.
 *
 * @Route("/tag")
 */
class TagController extends Controller
{
    /**
     * Lists all Tag entities.
     *
     * @Route("/", name="tag")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getEm();
        $user = $this->getUser();
        $tags = $em->getRepository('MarcaTagBundle:Tag')->findTagsByUser($user);
        $tagsets = $em->getRepository('MarcaTagBundle:Tagset')->findTagsetByUser($user);
        $tagsetid = 0;

        return array('tags' => $tags, 'tagsets' => $tagsets, 'tagsetid'=> $tagsetid);
    }
    
    
    /**
     * Lists all Tag entities.
     *
     * @Route("/{id}/tagset", name="tag_bytagset")
     * @Template("MarcaTagBundle:Tag:index.html.twig")
     */
    public function tagsetAction($id)
    {
        $em = $this->getEm();
        $user = $this->getUser();;
        $tags = $em->getRepository('MarcaTagBundle:Tag')->findTagsByTagset($id);
        $tagsets = $em->getRepository('MarcaTagBundle:Tagset')->findTagsetByUser($user);
        $tagsetid = $id;

        return array('tags' => $tags, 'tagsets' => $tagsets, 'tagsetid'=> $tagsetid );
    }    

    /**
     * Finds and displays a Tag entity.
     *
     * @Route("/{id}/show", name="tag_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getEm();

        $tag = $em->getRepository('MarcaTagBundle:Tag')->find($id);

        if (!$tag) {
            throw $this->createNotFoundException('Unable to find Tag entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'tag'      => $tag,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Tag entity.
     *
     * @Route("/{tagsetid}/new", name="tag_new")
     * @Template()
     */
    public function newAction($tagsetid)
    {
        $em = $this->getEm();
        $tagset = $em->getRepository('MarcaTagBundle:Tagset')->find($tagsetid);

        $tag = new Tag();
        $tag->setTagset($tagset);

        $form   = $this->createForm(new TagType(), $tag);

        return array(
            'tag' => $tag,
            'tagset' => $tagset,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Tag entity.
     *
     * @Route("/{tagsetid}/create", name="tag_create")
     * @Method("post")
     * @Template("MarcaTagBundle:Tag:new.html.twig")
     */
    public function createAction($tagsetid)
    {
        $em = $this->getEm();
        $tagset = $em->getRepository('MarcaTagBundle:Tagset')->find($tagsetid);
        $user = $this->getUser();
        $tag  = new Tag();
        $tag->setUser($user);
        $tag->addTagset($tagset);
        $request = $this->getRequest();
        $form    = $this->createForm(new TagType(), $tag);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($tag);
            $em->flush();
            $this->get('session')->getFlashBag()->add('update',$tagsetid);
            return $this->redirect($this->generateUrl('tagset'));
            
        }

        return array(
            'tag' => $tag,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Tag entity.
     *
     * @Route("/{id}/{tagsetid}/edit", name="tag_edit")
     * @Template()
     */
    public function editAction($id, $tagsetid)
    {
        $em = $this->getEm();

        $tag = $em->getRepository('MarcaTagBundle:Tag')->find($id);
        $tagset = $em->getRepository('MarcaTagBundle:Tagset')->find($tagsetid);
        if (!$tag) {
            throw $this->createNotFoundException('Unable to find Tag entity.');
        }

        $editForm = $this->createForm(new TagType(), $tag);
        $deleteForm = $this->createDeleteForm($id);


        return array(
            'tag'      => $tag,
            'tagset'      => $tagset,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Tag entity.
     *
     * @Route("/{id}/{tagsetid}/update", name="tag_update")
     * @Method("post")
     * @Template("MarcaTagBundle:Tag:edit.html.twig")
     */
    public function updateAction($id, $tagsetid)
    {
        $em = $this->getEm();

        $tag = $em->getRepository('MarcaTagBundle:Tag')->find($id);

        if (!$tag) {
            throw $this->createNotFoundException('Unable to find Tag entity.');
        }

        $editForm   = $this->createForm(new TagType(), $tag);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($tag);
            $em->flush();

            $this->get('session')->getFlashBag()->add('update',$tagsetid);
            return $this->redirect($this->generateUrl('tagset'));
        }

        return array(
            'tag'      => $tag,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Tag entity.
     *
     * @Route("/{id}/delete", name="tag_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $tag = $em->getRepository('MarcaTagBundle:Tag')->find($id);

            if (!$tag) {
                throw $this->createNotFoundException('Unable to find Tag entity.');
            }

            $em->remove($tag);
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
