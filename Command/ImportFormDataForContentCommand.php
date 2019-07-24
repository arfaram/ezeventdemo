<?php

namespace EzSystems\DemoBundle\Command;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\Exceptions;
use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\API\Repository\Values\Content\Field;
use EzSystems\EzPlatformFormBuilder\FieldType\Type;
use EzSystems\EzPlatformFormBuilder\FormSubmission\FormSubmissionService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *  Format your data as below $data example. Be sure that you have the FieldType "form" added to your content and using same form fields identifiers.
 *  In this example the content with the id = 238 contains a form fieldtype
 *  Form Fields added here are:
 *  - Name (single_line)
 *  - Email (email)
 *  - Interest (checkbox_list)
 *  - Country (country) - A custom symfony FormType. More infos about adding custom FormType can you find it here: https://doc.ezplatform.com/en/latest/guide/extending_form_builder/#extending-form-fields
 *
 *  You can check the list of defaults identifiers in "ezplatform-form-builder/src/bundle/Resources/config/field_definitions.yml"
 *
 * Register this class as a service in services.yml
 *
 * Class ImportFormDataForContentCommand
 * @package EzSystems\DemoBundle\Command
 */
class ImportFormDataForContentCommand extends ContainerAwareCommand
{
    public $data = [
        'values' => [
            [
                'identifier' => 'single_line',
                'name' => 'Name',
                'value' => 'John Reiter',
            ],
            [
                'identifier' => 'email',
                'name' => 'Email',
                'value' => 'me@ez.no',
            ],
            [
                'identifier' => 'checkbox_list',
                'name' => 'Interest',
                'value' => ['Adventure', 'Food'],
            ],
            [
                'identifier' => 'country',
                'name' => 'Country',
                'value' => 'DE',
            ],

        ],
        'contentId' => 238,
        'languageCode' => 'eng-GB',
    ];

    /**
     * @var \EzSystems\EzPlatformFormBuilder\FormSubmission\FormSubmissionService
     */
    private $formSubmissionService;
    /**
     * @var \eZ\Publish\API\Repository\ContentService
     */
    private $contentService;
    /**
     * @var \EzSystems\EzPlatformFormBuilder\FieldType\Type
     */
    private $formFieldType;


    public function __construct(
        FormSubmissionService $formSubmissionService,
        ContentService $contentService,
        Type $formFieldType
    )
    {
        parent::__construct();
        $this->formSubmissionService = $formSubmissionService;
        $this->contentService = $contentService;
        $this->formFieldType = $formFieldType;
    }

    protected function configure()
    {
        $this->setName('ezplatform:import_form_data_for_content');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int|void|null
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    protected function execute( InputInterface $input, OutputInterface $output )
    {
        $contentId = $this->data['contentId'];

        try
        {
            $content = $this->contentService->loadContent( $contentId );

            $formField = $this->getFormFieldType($content);

            if (empty($formField)) {
                return;
            }

            /** @var \EzSystems\EzPlatformFormBuilder\FieldType\Value $formFieldValue */
            $formFieldValue = $formField->value;

            if (empty($formFieldValue->getForm())) {
                return;
            }

            $submission = $this->formSubmissionService->create(
                $content->contentInfo,
                $this->data['languageCode'],
                $formFieldValue->getFormValue(),
                $this->data['values']
            );

        }
        // Content type or location not found
        catch ( Exceptions\NotFoundException $e )
        {
            $output->writeln( $e->getMessage() );
        }

    }

    /**
     * @param \eZ\Publish\API\Repository\Values\Content\Content|null $content
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Field|null
     */
    protected function getFormFieldType(?Content $content): ?Field
    {
        if (empty($content)) {
            return null;
        }

        foreach ($content->getFieldsByLanguage() as $field) {
            if ($field->fieldTypeIdentifier === $this->formFieldType->getFieldTypeIdentifier()) {
                return $field;
            }
        }

        return null;
    }
}
