<?php

namespace BdeReventBundle\Controller;

use BdeReventBundle\Entity\Participant;
use BdeReventBundle\Form\ParticipantType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
            $this->get('bde.main.mailer_service')->send($participant);
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
     * @Route("/export")
     */
    public function exportAction()
    {
        $response = new StreamedResponse();
        $response->setCallback(function () {

            $handle = fopen('php://output', 'w+');

            // Add the header of the CSV file
            fputcsv($handle, array('id', 'firstname', 'lastname', 'email', 'invited_by', 'invited_by_firstname', 'invited_by_lastname', 'invited_by_email'), ';');

            // Add the data queried from database
            foreach ($this->get("doctrine.orm.entity_manager")->getRepository("BdeReventBundle:Participant")->findBy(array('used' => true)) as $p) {
                $v = array();
                $v[] = $p->getId();
                $v[] = $p->getFirstName();
                $v[] = $p->getLastName();
                $v[] = $p->getEmail();
                if ($p->getInvitedBy() != null) {
                    $v[] = $p->getInvitedBy()->getId();
                    $v[] = $p->getInvitedBy()->getFirstName();
                    $v[] = $p->getInvitedBy()->getLastName();
                    $v[] = $p->getInvitedBy()->getEmail();
                } else {
                    $v[] = '';
                    $v[] = '';
                    $v[] = '';
                    $v[] = '';
                }
                fputcsv(
                    $handle, // The file pointer
                    $v, // The fields
                    ';' // The delimiter
                );
            }
            fclose($handle);
        });

        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="export.csv"');

        return $response;
    }

    /**
     * @Route("/{id}/resend")
     * @Template()
     */
    public function resendMailAction($id)
    {

        $participant = $this->get('doctrine.orm.entity_manager')->getRepository('BdeReventBundle:Participant')->find($id);
        if ($participant == null) {
            throw $this->createNotFoundException('Participant introuvable!');
        }
        $this->get('bde.main.mailer_service')->send($participant);
        return $this->redirectToRoute('participants_index');
    }

    /**
     * @Route("/import")
     * @Template()
     */
    public function importAction(Request $request)
    {
        if ($request->isMethod("POST")) {
            $em = $this->get("doctrine.orm.entity_manager");
            $status = 1;
            try {
                $data = ($request->get("data"));
                foreach ($data as $guest) {
                    $participant = new Participant();
                    $participant->setType($em->getRepository("BdeReventBundle:Type")->findOneByName('Diplômé'));
                    $participant->setEmail($guest['email']);
                    $participant->setFirstName($guest['firstname']);
                    $participant->setLastName($guest['lastname']);
                    $em->persist($participant);
                    $em->flush($participant);
                    $this->get('bde.main.mailer_service')->send($participant);
                }
            } catch (Exception $e) {
                $status = 0;
            }
            echo $status;
            exit;
        }
        return array();
    }

}
