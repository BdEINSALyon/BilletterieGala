<?php
/**
 * Created by PhpStorm.
 * User: pvienne
 * Date: 31/08/15
 * Time: 16:04
 */
namespace BdeReventBundle\Mail;

use BdeReventBundle\Entity\Mail;
use BdeReventBundle\Entity\Participant;
use Doctrine\ORM\EntityManager;
use Html2Text\Html2Text;
use Mailgun\Mailgun;
use Symfony\Component\Routing\Router;

/**
 * Mailer service used to create mails.
 * @package BdE\MainBundle\Mail
 */
class MailerService
{
    private $_service;
    private $_em;
    private $_twig;
    private $_router;
    private $_website;
    /**
     * @var Mailgun
     */
    private $mailgun;

    /**
     * MailerService constructor.
     * @param $_secret
     * @param EntityManager $_em
     * @param \Twig_Environment $_twig
     */
    public function __construct(TokenService $_service, $website, EntityManager $_em, \Twig_Environment $_twig, Router $router, $mailgun_api_key, $mailgun_domain)
    {
        $this->_service = $_service;
        $this->_website = $website;
        $this->_em = $_em;
        $this->_twig = $_twig;
        $this->_router = $router;
        $this->mailgun_domain = $mailgun_domain;
        $this->mailgun = new Mailgun($mailgun_api_key);
    }

    public function send(Participant $participant)
    {
        $mail = $this->_em->getRepository('BdeReventBundle:Mail')->findOneBy(array('type' => $participant->getType()));
        $content = $this->generateMailFromData($mail, $participant);
        $this->mailgun->sendMessage($this->mailgun_domain, array(
            'from' => 'accueil@gala.bde-insa-lyon.fr',
            'to' => $participant->getEmail(),
            'subject' => $content['subject'],
            'body-text' => Html2Text::convert($content['body']),
            'body-html' => $content['body']
        ));
    }

    public function generateMailFromData(Mail $mail, Participant $participant)
    {
        $twig = new \Twig_Environment(new \Twig_Loader_Array(
                ['mail.' . $mail->getId() => $mail->getMessage()]
            ),
            array(
                'autoescape' => false
            )
        );
        $subject = ($mail->getObject());
        $message = $this->_twig->render("@BdeRevent/Mail/mail.html.twig", array(
            'content' => $twig->render("mail." . $mail->getId(), array(
                'participant' => $participant,
                'link' => $this->_website . $this->_router->generate("return_mail", array('key' => $this->_service->crypt_data($participant->getId())))
            )),
            'config' => array()
        ));
        return ['subject' => $subject, 'body' => $message];
    }
}