<?php

namespace App\Form;

use App\Entity\Registration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('video', FileType::class, [
                'label' => 'Vidéo',
                'mapped' => false,
                'required' => true,
                'attr' => ['accept' => 'video/*'],
//                'constraints' => [
//                    new File([
////                        'maxSize' => '1024k',
//                        'mimeTypes' => [
//                            'video/mp4',
//                        ],
//                        'mimeTypesMessage' => 'Veuillez importer une vidéo valide',
//                    ])
//                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider'
            ])
            ->setMethod('POST')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Registration::class,
        ]);
    }
}
