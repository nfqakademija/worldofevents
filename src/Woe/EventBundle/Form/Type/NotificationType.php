<?php

namespace Woe\EventBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Woe\EventBundle\Form\DataTransformer\DatetimeToDaysBeforeEventTransformer;

class NotificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $event = $options['event'];
        $transformer = new DatetimeToDaysBeforeEventTransformer($event);

        $builder
            ->add('email', 'email')
            ->add(
                $builder->create('date', 'integer')->addModelTransformer($transformer)
            )
            ->add('save', 'submit');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'data_class' => 'Woe\EventBundle\Entity\Notification',
            ))
            ->setRequired(array(
                'event',
            ))
            ->setAllowedTypes(array(
                'event' => 'Woe\EventBundle\Entity\Event',
            ));
    }

    public function getName()
    {
        return 'notification';
    }
}
