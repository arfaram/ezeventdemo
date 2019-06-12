<?php
/**
 * Update ezmatrix field
 */

namespace EzSystems\DemoBundle\Command;

use eZ\Publish\API\Repository\Exceptions;
use EzSystems\EzPlatformMatrixFieldtype\FieldType\Value;
use EzSystems\EzPlatformMatrixFieldtype\FieldType\Value\Row;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ProductUpdateCommand extends ContainerAwareCommand
{

    /** @var int  */
    const ROOTUSERID = 14;


    protected function configure()
    {
        $this->setName('ezpublish:update_product');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws \eZ\Publish\API\Repository\Exceptions\BadStateException
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    protected function execute( InputInterface $input, OutputInterface $output )
    {
        $repository = $this->getContainer()->get( 'ezpublish.api.repository' );
        $contentService = $repository->getContentService();

        // This example for setting a current user is valid for 5.x and early versions of 6.x installs
// This is deprecated from 6.6, and you should use PermissionResolver::setCurrentUserReference() instead

        //$repository->setCurrentUser( $repository->getUserService()->loadUser( self::ROOTUSERID ) );

        $repository->getPermissionResolver()->setCurrentUserReference($repository->getUserService()->loadUser( self::ROOTUSERID));

        $contentId = 59;

        try
        {
            // create a content draft from the current published version
            $contentInfo = $contentService->loadContentInfo( $contentId );
            $contentDraft = $contentService->createContentDraft( $contentInfo );

            // instantiate a content update struct and set the new fields
            $contentUpdateStruct = $contentService->newContentUpdateStruct();
            $contentUpdateStruct->initialLanguageCode = 'eng-GB'; // set language for new version

            //// ! CHANGES WITH YOUR COLUMN IDENTIFIERS
            ///  ! YOUR FIELDTYPE IDENTIFIER
            //// ! Error Content fields did not validate => CHECK THE Minimum number of rows in the field definition

            $contentUpdateStruct->setField(
                'stores',
                new Value(
                    [
                        new Row(
                            [
                                'store_name' => 'somewhere',
                                'city' => 'Liverpool',
                                'address' => 'at the corner',
                                'zip_code' => '54321',
                                'homepage' => 'www.somewhere.com',
                            ]
                        )

                    ]
                )
            );

            // update and publish draft
            $contentDraft = $contentService->updateContent( $contentDraft->versionInfo, $contentUpdateStruct );
            $content = $contentService->publishVersion( $contentDraft->versionInfo );
            // print out the content
            print_r( $content );
        }
            // Content type or location not found
        catch ( Exceptions\NotFoundException $e )
        {
            $output->writeln( $e->getMessage() );
        }
            // Invalid field value
        catch ( Exceptions\ContentFieldValidationException $e )
        {
            $output->writeln( $e->getMessage() );
        }
            // Required field missing or empty
        catch ( Exceptions\ContentValidationException $e )
        {
            $output->writeln( $e->getMessage() );
        }
    }
}
