<?php

declare(strict_types=1);

namespace EzSystems\DemoBundle\UserSetting\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CustomTabSettingsType
 * @package EzSystems\UserBundle\Form\Type\Dashboard
 */
class CustomTabSettingsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'pagination_limit',
                NumberType::class,
                [
                    'attr' => ['min' => 1],
                    'required' => true,
                    'block_name' => 'custom_block',
                    'label' => /** @Desc("Number of items displayed in the Custom table") */ 'settings.dashboard_custom_tab_pagination_limit.value.description',
                ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'user_settings',
        ]);
    }

}