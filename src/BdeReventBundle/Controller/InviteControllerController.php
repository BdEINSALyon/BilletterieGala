<?php

namespace BdeReventBundle\Controller;

use BdeReventBundle\Entity\Participant;
use BdeReventBundle\Form\InviteForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class InviteControllerController extends Controller
{
    /**
     * @Route("/{key}/endPayment", name="end_payment")
     * @param Request $request
     * @param $key
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function endPaymentAction(Request $request, $key)
    {

        $id = $this->get("bde.revent.token_service")->decrypt_data($key);
        if ($id == null) {
            throw $this->createNotFoundException();
        }
        $em = $this->get("doctrine.orm.entity_manager");
        $participant = $em->getRepository("BdeReventBundle:Participant")
            ->find($id);
        if ($participant == null) {
            throw $this->createNotFoundException();
        }
        if ($participant->getUsed()) {
            throw $this->createNotFoundException();
        }

        $participant->setUsed(true);
        $participant->setGuestsByWeezevent($request->get('nbInvite'));
        $em->persist($participant);
        $em->flush();

        $response = new Response(json_encode(array('status' => 'ok')));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/{key}/ticket_office", name="return_mail")
     * @Template()
     */
    public function ticketsAction($key)
    {
        $id = $this->get("bde.revent.token_service")->decrypt_data($key);
        if ($id == null) {
            return $this->render('@BdeRevent/InviteController/deny.html.twig');
        }
        $participant = $this->get("doctrine.orm.entity_manager")->getRepository("BdeReventBundle:Participant")
            ->find($id);
        if ($participant == null) {
            return $this->render('@BdeRevent/InviteController/deny.html.twig');
        }
        if ($participant->getUsed()) {
            if ($participant->getType()->getCanInvite())
                $this->addFlash('info', 'Vous avez déjà achetez vos places, mais vous pouvez inviter vos proches !');
            return $this->redirectToRoute('invite', array('key' => $key));
        }
        return array(
            'participant' => $participant,
            'pjson' => json_encode(array(
                "id" => $participant->getId(),
                "firstname" => $participant->getFirstName(),
                "lastname" => $participant->getLastName(),
                "email" => $participant->getEmail(),
            )),
            'token' => $key
        );
    }

    /**
     * @Route("/{key}/invite", name="invite")
     * @Template()
     */
    public function inviteAction(Request $request, $key)
    {
        $id = $this->get("bde.revent.token_service")->decrypt_data($key);
        if ($id == null) {
            return $this->render('@BdeRevent/InviteController/deny.html.twig');
        }
        $em = $this->get("doctrine.orm.entity_manager");
        $participant = $em->getRepository("BdeReventBundle:Participant")
            ->find($id);
        if ($participant == null) {
            return $this->render('@BdeRevent/InviteController/deny.html.twig');
        }
        if (!$participant->getUsed())
            return $this->redirectToRoute('return_mail', array('key' => $key));
        if ($participant->getType()->getCanInvite()) {

            $max_invites = 7;
            $weezevent_invites = $participant->getGuestsByWeezevent();
            $max_invites -= $weezevent_invites;

            $addable_invites = $max_invites - $participant->getGuests()->count();

            $form = new InviteForm($this->createFormBuilder()->getFormConfig(), $addable_invites);

            if ($request->isMethod('POST')) {
                $form->handleRequest($request);
                if ($form->isValid()) {
                    $all_ok = true;
                    foreach ($form->getData() as $data) {
                        $check = $data['firstname'] . $data['lastname'] . $data['email'];
                        if ($check == "") {
                            continue; // Do not handle an empty invite
                        }

                        if ($em->getRepository('BdeReventBundle:Participant')->findOneBy(array('email' => $data['email']))) {
                            $this->addFlash('info', htmlspecialchars($data['firstname']) . " (" . $data['email'] . ") est déjà invité au gala");
                            continue;
                        }

                        $guest = new Participant();
                        $guest->setFirstName($data['firstname']);
                        $guest->setLastName($data['lastname']);
                        $guest->setEmail($data['email']);
                        /** @noinspection PhpUndefinedMethodInspection */
                        $guest->setType($em->getRepository("BdeReventBundle:Type")->findOneByName('Invité'));
                        $guest->setInvitedBy($participant);
                        $em->persist($guest);
                        $em->flush($guest);
                        $this->get('bde.main.mailer_service')->send($guest);
                        $this->addFlash('success', htmlspecialchars($data['firstname']) . " (" . $data['email'] . ") a été invité");
                    }
                    return $this->redirectToRoute("invite", array('key' => $key));
                }
            }

            $formView = $form->createView();
            return array(
                'participant' => $participant,
                'token' => $key,
                'invited' => $max_invites - $addable_invites + $weezevent_invites,
                'form' => $formView,
                'errors' => $form->getErrors(true, 2)
            );
        } else
            return $this->render('@BdeRevent/InviteController/can_not_invite.html.twig');
    }

}
