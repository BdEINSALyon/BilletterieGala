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
     * @Route("/delete")
     * @Template()
     */
    public function deleteAction()
    {
        return array(
                // ...
            );    }

    /**
     * @Route("/{id}/edit")
     * @Template()
     */
    public function editAction($id)
    {
        return array(
                // ...
            );    }

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
