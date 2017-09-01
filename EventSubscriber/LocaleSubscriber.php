<?php

namespace FormBundle\EventSubscriber;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class LocaleSubscriber implements EventSubscriberInterface {

    use ContainerAwareTrait;

    private $defaultLocale;

    public function __construct($defaultLocale = 'fr') {
        $this->defaultLocale = $defaultLocale;
    }

    public function onKernelRequest(GetResponseEvent $event) {

        $request = $event->getRequest();
        if (!$request->hasPreviousSession()) {
            return;
        }

        // try to see if the locale has been set as a _locale routing parameter
        $prefLanguage = $request->getPreferredLanguage();

        if ($prefLanguage == 'fr' | $prefLanguage == 'en') {
            $request->setLocale($request->getPreferredLanguage(['en', 'fr']));
            dump($request->getLocale());
        } else {
            $request->setLocale($request->getSession()->get('_locale', $request->getPreferredLanguage(['fr'])));
        }
    }

    public static function getSubscribedEvents() {
        return array(
            // must be registered after the default Locale listener
            KernelEvents::REQUEST => array(array('onKernelRequest', 15)),
        );
    }

}
