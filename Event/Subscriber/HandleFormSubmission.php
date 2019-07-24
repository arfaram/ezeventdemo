<?php

declare(strict_types=1);

namespace EzSystems\DemoBundle\Event\Subscriber;

use EzSystems\EzPlatformFormBuilder\Event\FormEvents;
use EzSystems\EzPlatformFormBuilder\Event\FormSubmitEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class HandleFormSubmission
 * @package EzSystems\DemoBundle\Event\Subscriber
 */
class HandleFormSubmission implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::FORM_SUBMIT => 'onFormSubmit',
        ];
    }

    /**
     * @param \EzSystems\EzPlatformFormBuilder\Event\FormSubmitEvent $event
     */
    public function onFormSubmit(FormSubmitEvent $event)
    {
        //dump($event->getData());   Access submitted form data
    }
}
