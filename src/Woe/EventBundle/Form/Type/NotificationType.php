<?php

namespace Woe\EventBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class NotificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email')
            ->add('days', 'integer', array('mapped' => false))
            ->add('save', 'submit');
    }

    public function getName()
    {
        return 'notification';
    }
}