<?php

declare(strict_types=1);

namespace EzSystems\DemoBundle\UserSetting\Setting;

use EzSystems\DemoBundle\UserSetting\Form\DataTransformer\CustomTabSettingsTransformer;
use EzSystems\DemoBundle\UserSetting\Form\Type\CustomTabSettingsType;
use EzSystems\EzPlatformUser\UserSetting\FormMapperInterface;
use EzSystems\EzPlatformUser\UserSetting\ValueDefinitionInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;
use EzSystems\EzPlatformAdminUi\UserSetting as AdminUiUserSettings;
//use Symfony\Component\Form\Extension\Core\Type\NumberType;


/**
 * Class CustomTabSettings
 * @package EzSystems\DemoBundle\UserSetting\Setting
 */
class CustomTabSettings implements ValueDefinitionInterface, FormMapperInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var int
     */
    private $customTabPaginationLimit;

    /**
     * CustomTabSettings constructor.
     * @param TranslatorInterface $translator
     * @param int $customTabPaginationLimit
     * @param array $customTabContentTypes
     */
    public function __construct(
        TranslatorInterface $translator,
        int $customTabPaginationLimit
    )
    {
        $this->translator = $translator;
        $this->customTabPaginationLimit = $customTabPaginationLimit;
    }


    /**
     * @param FormBuilderInterface $formBuilder
     * @param AdminUiUserSettings\ValueDefinitionInterface $value
     * @return FormBuilderInterface
     */
    public function mapFieldForm(FormBuilderInterface $formBuilder, AdminUiUserSettings\ValueDefinitionInterface $value): FormBuilderInterface
    {
          //it works!
//        $form = $formBuilder->create(
//            'value',
//            NumberType::class,
//            [
//                'attr' => ['min' => 1],
//                'required' => true,
//                'block_name' => 'custom_block',
//            ]
//        );

        //or use a FormType Class

        $form = $formBuilder->create(
            'value',
            CustomTabSettingsType::class,
            [
                'label' => false
            ]
        );

        $form->addModelTransformer(new CustomTabSettingsTransformer());

        return $form;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->translator->trans(
        /** @Desc("Dashboard custom block items") */
            'settings.dashboard_custom_tab_pagination_limit.value.title',
            [],
            'user_settings'
        );
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->getTranslatedDescription();
    }

    /**
     * @return string
     */
    private function getTranslatedDescription(): string
    {
        return $this->translator->trans(
        /** @Desc("Number of items displayed in the Custom table") */
            'settings.dashboard_custom_tab_pagination_limit.value.description',
            [],
            'user_settings'
        );
    }

    /**
     *
     * @param string $storageValue
     * @return string either from the Storage or default value
     */
    public function getDisplayValue(string $storageValue): string
    {
        return $storageValue;

    }

    /**
     * @return string
     */
    public function getDefaultValue(): string
    {
        return (string)$this->customTabPaginationLimit;

    }
}