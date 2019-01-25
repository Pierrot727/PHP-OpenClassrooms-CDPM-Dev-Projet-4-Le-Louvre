<?php

namespace App\Form;

use App\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', null, [
                'label' => 'Prénom'
            ] )
            ->add('lastname', null )
            ->add('country', null,[
                'label' => 'Pays'
            ] )
            ->add('birthday', BirthdayType::class, [
                'label' => 'Date de naissance'
            ] )
            //->add('price')
            ->add('reduction', null, [
                'label' => 'Cochez ici si vous avez une réduction'
            ] )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}
