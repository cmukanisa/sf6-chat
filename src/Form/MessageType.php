<?php

namespace App\Form;

use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class,[
                "label" => "...",
                "attr"  => [
                    "placeholder"   => "type a message",
                    "class"         => "border border-primary"
                ],
                "constraints"   => [new NotBlank(),new Length(min:2)],
            ])
            // ->add('senderText')
            // ->add('createdAt')
            // ->add('updatedAt')
            // ->add('deletedAt')
            // ->add('slug')
            // ->add('sender')
            // ->add('thread')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
