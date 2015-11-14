<?php

namespace DTR\DTRBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DTR\DTRBundle\Entity\Participation;
use DTR\DTRBundle\Form\ParticipationType;

/**
 * Participation controller.
 *
 * @Route("/participation")
 */
class ParticipationController extends Controller
{

    /**
     * Lists all Participation entities.
     *
     * @Route("/", name="participation")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DTRBundle:Participation')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Participation entity.
     *
     * @Route("/", name="participation_create")
     * @Method("POST")
     * @Template("DTRBundle:Participation:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Participation();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('participation_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Participation entity.
     *
     * @param Participation $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Participation $entity)
    {
        $form = $this->createForm(new ParticipationType(), $entity, array(
            'action' => $this->generateUrl('participation_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Participation entity.
     *
     * @Route("/new", name="participation_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Participation();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Participation entity.
     *
     * @Route("/{id}", name="participation_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DTRBundle:Participation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Participation entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Participation entity.
     *
     * @Route("/{id}/edit", name="participation_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DTRBundle:Participation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Participation entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Participation entity.
    *
    * @param Participation $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Participation $entity)
    {
        $form = $this->createForm(new ParticipationType(), $entity, array(
            'action' => $this->generateUrl('participation_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Participation entity.
     *
     * @Route("/{id}", name="participation_update")
     * @Method("PUT")
     * @Template("DTRBundle:Participation:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DTRBundle:Participation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Participation entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('participation_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Participation entity.
     *
     * @Route("/{id}", name="participation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DTRBundle:Participation')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Participation entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('participation'));
    }

    /**
     * Creates a form to delete a Participation entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('participation_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
