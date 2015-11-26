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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Router;

/**
 * Mailer service used to create mails.
 * @package BdE\MainBundle\Mail
 */
class MailerService
{
    private $_secret;
    private $_em;
    private $_twig;
    private $_router;
    private $_website;

    /**
     * MailerService constructor.
     * @param $_secret
     * @param EntityManager $_em
     * @param \Twig_Environment $_twig
     */
    public function __construct($_secret, $website, EntityManager $_em, \Twig_Environment $_twig, Router $router)
    {
        $this->_secret = $_secret;
        $this->_website = $website;
        $this->_em = $_em;
        $this->_twig = $_twig;
        $this->_router = $router;
    }

    public function generateMailFromBinData($data)
    {
        $data = $this->_decrypt_data($data);
        if (!$data) {
            throw new NotFoundHttpException("Your mail key is invalid !");
        }
        $participant = $this->_em->getRepository("CvaGestionMembreBundle:Etudiant")->find($data->p);
        $mail = $this->_em->getRepository("BdEMainBundle:Mail")->find($data->m);
        if (!$participant || !$mail) {
            throw new NotFoundHttpException("This mail does not exist");
        }
        return $this->generateMailFromData($mail, $participant);
    }

    private function _decrypt_data($data)
    {
        $data = preg_split('/\./i', $data);
        $encrypted = ($data[1]);
        if ($encrypted != (strtoupper(substr(md5(sha1($this->_get_secret_hash() . $data . $this->_get_secret_hash())), 0, 7))))
            return null;
        return intval($data[0]);
    }

    private function _get_secret_hash()
    {
        $md5_s = md5($this->_secret);
        $sha1_s = sha1($this->_secret);
        $md5_sha1_s = md5($sha1_s);
        $md5_md5_s = md5($md5_s);
        return
            sha1($md5_md5_s) . sha1($sha1_s . $md5_md5_s . $md5_s);
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
                'link' => $this->_website . $this->_router->generate("return_mail", array('key' => $this->_crypt_data($mail->getId(), $participant->getId())))
            )),
            'config' => array(
                'title' => $mail->getObject(),
                'company' => "BdE INSA Lyon",
                'participant' => $participant,
                'mail_id' => $this->_crypt_data($mail->getId(), $participant->getId())
            ),
            'why_this_mail' => ""
        ));
        return ['subject' => $subject, 'body' => $message];
    }

    private function _crypt_data($mail_id, $participant_id)
    {
        return $participant_id . '.' . strtoupper(substr(md5(sha1($this->_get_secret_hash() . $participant_id . $this->_get_secret_hash())), 0, 7));
    }

    private function _get_encryption_method()
    {
        $encryptionMethod = "AES-256-CBC";
        return $encryptionMethod;
    }
}