<?php
/**
 * Created by PhpStorm.
 * User: linux
 * Date: 05/02/19
 * Time: 17:23
 */

namespace App\Form;

use App\Entity\User;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
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
                ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class'=>'App\Entity\User']);
    }
    public function buildFormEdit(FormBuilderInterface $builder, array $options)
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
            ->add('roles', CheckboxType::class,[
                'required'=>'required',
                'attr'=>[
                    'label'    => 'ROLE_ADMIN',
                    'label'    => 'ROLE_USER',
                    'required' => true
                ]
            ]);
    }
}