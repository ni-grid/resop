<?php

namespace App\Form\Type;

use App\Entity\Organization;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    private array $availableSkillSets;

    private const DEFAULT_OCCUPATIONS = [
        'Compétences pédiatriques',
        'Infirmier.e',
        'Médecin',
        'Ambulancier.e',
        'Aide soignant.e',
        'Infirmier.e anesthésiste',
        'Sage femme',
        'Pharmacien',
        'Autre personnel de santé',
        'Pompier',
        'Gendarme / Policier',
        'Logisticien',
    ];

    public function __construct(array $availableSkillSets = [])
    {
        $this->availableSkillSets = $availableSkillSets;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $occupationChoices = (array) array_combine(self::DEFAULT_OCCUPATIONS, self::DEFAULT_OCCUPATIONS);
        $occupationChoices += ['Autre :' => '-'];
        $builder
            ->add('identificationNumber', HiddenType::class)
            ->add('organization', EntityType::class, [
                'class' => Organization::class,
                'choice_label' => 'name',
            ])
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('phoneNumber', TextType::class)
            ->add('emailAddress', EmailType::class)
            ->add('occupation', ChoiceType::class, [
                'choices' => $occupationChoices,
                'required' => false,
                'expanded' => true,
                'placeholder' => false,
            ])
            ->add('otherOccupation', TextType::class, [
                'required' => false,
                'mapped' => false,
            ])
            ->add('organizationOccupation', TextType::class)
            ->add('vulnerable', ChoiceType::class, [
                'choices' => array_flip([
                    1 => 'Je fais partie des personnes vulnérables',
                    0 => 'Je ne fais PAS partie des personnes vulnérables',
                ]),
                'expanded' => true,
            ])
            ->add('fullyEquipped', CheckboxType::class, ['required' => false])
            ->add('skillSet', ChoiceType::class, [
                'choices' => array_flip($this->availableSkillSets),
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('submit', SubmitType::class)
        ;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            if (isset($data['occupation']) && '-' === $data['occupation']) {
                $data['occupation'] = $data['otherOccupation'];
            }
        });

        // Prevent null value
        $builder->get('occupation')
            ->addModelTransformer(new CallbackTransformer(
                static function ($transformed) {
                    return $transformed;
                },
                static function ($reverseTransformed) {
                    if (empty($reverseTransformed)) {
                        return '';
                    }

                    return $reverseTransformed;
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
