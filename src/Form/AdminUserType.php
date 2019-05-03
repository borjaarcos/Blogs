<?php
/**
 * Created by PhpStorm.
 * User: linux
 * Date: 20/02/19
 * Time: 18:33
 */

namespace App\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class AdminUserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', \Symfony\Component\Form\Extension\Core\Type\TextType::class,[

                'required'=>'required',
                'attr'=>[
                    'class'=>'form-username form-control',
                    'placeholder'=>'Username'
                ]
            ])
            ->add('email', EmailType::class,[
                'required'=>'required',
                'attr'=>[
                    'class'=>'form-email form-control',
                    'placeholder'=>'Email@email'
                ]
            ])
            ->add('plainpassword', RepeatedType::class,[
                'type'=>PasswordType::class,
                'required'=>'required',
                'first_options'=>[
                    'attr'=>[
                        'class'=>'form-password form-control',
                        'placeholder'=>'password'
                    ]
                ],
                'second_options'=>[
                    'attr'=>[
                        'class'=>'form-password form-control',
                        'placeholder'=>'repeat password'
                    ]
                ]
            ])
            ->add('isActive', \Symfony\Component\Form\Extension\Core\Type\CheckboxType::class,[

                'attr'=>[
                    'label'    => 'is_active',

                ]
            ])
            ->add('roles', ChoiceType::class,[
                'choices' => [
                    'ROLE_USER' => "ROLE_USER",
                    'ROLE_ADMIN' => "ROLE_ADMIN"
                ],

                'expanded'  => true,
                'multiple'  => true,
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class'=>'App\Entity\User']);
    }
}