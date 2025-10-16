<?php

namespace Agenceweb\MercanetBnpParibas\Form\Type;

use Agenceweb\MercanetBnpParibasPlugin\Legacy\Mercanet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class MercanetBnpParibasConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('environment', ChoiceType::class, [
                'choices' => [
                    'mercanet_bnp_paribas.production' => Mercanet::PRODUCTION,
                    'mercanet_bnp_paribas.test' => Mercanet::TEST,
                    'mercanet_bnp_paribas.simulation' => Mercanet::SIMULATION,
                ],
                'label' => 'mercanet_bnp_paribas.environment',
            ])
            ->add('secret_key', TextType::class, [
                'label' => 'mercanet_bnp_paribas.secure_key',
                'constraints' => [
                    new NotBlank([
                        'message' => 'mercanet_bnp_paribas.secure_key.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('merchant_id', TextType::class, [
                'label' => 'mercanet_bnp_paribas.merchant_id',
                'constraints' => [
                    new NotBlank([
                        'message' => 'mercanet_bnp_paribas.merchant_id.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('key_version', TextType::class, [
                'label' => 'mercanet_bnp_paribas.key_version',
                'constraints' => [
                    new NotBlank([
                        'message' => 'mercanet_bnp_paribas.key_version.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $data = $event->getData();
                $data['payum.http_client'] = '@agenceweb.mercanet_bnp_paribas.bridge.mercanet_bnp_paribas_bridge';
                $event->setData($data);
            })
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'mercanet_bnp_paribas_gateway_configuration_type';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // $resolver->setDefaults([
        //     'data_class' => GatewayConfig::class,
        // ]);
    }
}
