<?php

namespace BdeReventBundle\Controller;

use BdeReventBundle\Entity\Participant;
use BdeReventBundle\Form\ParticipantType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ParticipantsManagerController
 * @package BdeReventBundle\Controller
 * @Route("/participants")
 */
class ParticipantsManagerController extends Controller
{
    /**
     * @Route("/", name="participants_index")
     * @Template()
     */
    public function indexAction()
    {
        return array(
            'participants'=>$this->get('doctrine.orm.entity_manager')->getRepository('BdeReventBundle:Participant')->findAll()
        );
    }

    /**
     * @Route("/new")
     * @Template()
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm(new ParticipantType());

        $form->handleRequest($request);

        if ($form->isValid()) {
            $participant = $form->getData();
            $em = $this->get('doctrine.orm.default_entity_manager');
            $em->persist($participant);
            $em->flush($participant);
            return $this->redirectToRoute('participants_index');
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/{id}/delete")
     * @Template()
     */
    public function deleteAction(Request $request, $id)
    {

        $participant = $this->get('doctrine.orm.entity_manager')->getRepository('BdeReventBundle:Participant')->find($id);
        if($participant == null) {
            throw $this->createNotFoundException('Participant introuvable!');
        }

        if($request->isMethod('POST')){
            $em = $this->get('doctrine.orm.default_entity_manager');
            $em->remove($participant);
            $em->flush($participant);
            $this->addFlash('success', 'Le participant a été supprimé');
            return $this->redirectToRoute('participants_index');

        }

        return array(
                // ...
            );    }

    /**
     * @Route("/{id}/edit")
     * @Template()
     * @param $request
     * @param $id
     * @return array
     */
    public function editAction(Request $request, $id)
    {

        $participant = $this->get('doctrine.orm.entity_manager')->getRepository('BdeReventBundle:Participant')->find($id);
        if($participant == null) {
            throw $this->createNotFoundException('Participant introuvable!');
        }

        $form = $this->createForm(new ParticipantType(), $participant);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $participant = $form->getData();
            $em = $this->get('doctrine.orm.default_entity_manager');
            $em->persist($participant);
            $em->flush($participant);
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/{id}/resend")
     * @Template()
     */
    public function resendMailAction($id)
    {
        return array(
                // ...
            );    }

}
