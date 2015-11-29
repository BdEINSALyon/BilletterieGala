<?php
/**
 * Created by PhpStorm.
 * User: pvienne
 * Date: 29/11/15
 * Time: 13:25
 */

namespace BdeReventBundle\Form;


use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormInterface;

class InviteForm extends Form
{

    public function __construct(FormConfigInterface $config, $number = 0)
    {
        parent::__construct($config);
        for ($i = 0; $i < $number; $i++)
            $this->add($i, new InviteParticipantType());
    }

    public function isValid()
    {
        if (!$this->isSubmitted()) {
            return false;
        }

        if ($this->isDisabled()) {
            return true;
        }

        if (count($this->getErrors(true)) > 0) {
            return false;
        }

        return true;
    }


    public function getErrors($deep = false, $flatten = true)
    {
        $errors = array();

        if (!$this->isSubmitted()) return $errors;

        // Copy the errors of nested forms to the $errors array
        if ($deep) {
            foreach ($this->getIterator() as $child) {

                /** @var FormInterface $child */
                $data = $child->getData();
                $check = $data['firstname'] == null && $data['lastname'] == null && $data['email'] == null;
                $check = $check || $data['firstname'] == "" && $data['lastname'] == "" && $data['email'] == "";

                if ($check) {
                    continue;
                }

                $childErrors = array();

                // Check FirstName
                if (strlen($data['firstname']) <= 0) {
                    $childErrors[] = new FormErrorIterator($child->get('firstname'), array(new FormError("Le prénom ne peut pas être vide !")));
                }

                // Check Lastname
                if (strlen($data['lastname']) <= 0) {
                    $childErrors[] = new FormErrorIterator($child->get('lastname'), array(new FormError("Le nom ne peut pas être vide !")));
                }

                // Check Email
                if (strlen($data['email']) <= 0) {
                    $childErrors[] = new FormErrorIterator($child->get('email'), array(new FormError("L'email ne peut pas être vide !")));
                }

                if (0 === count($childErrors)) {
                    continue;
                }

                if ($flatten) {
                    /** @var FormErrorIterator $error */
                    foreach ($childErrors as $error) {
                        if ($flatten == 2) {
                            $errors[$child->getName()] = $childErrors;
                        } else {
                            foreach ($error as $e) {
                                $errors[$child->getName() . $error->getForm()->getName()] = $e;
                            }
                        }
                    }
                } else {
                    $errors[$child->getName()] = new FormErrorIterator($child, $childErrors);
                }
            }
        }
        if ($flatten == 2) {
            return $errors;
        }

        return new FormErrorIterator($this, $errors);
    }


} 