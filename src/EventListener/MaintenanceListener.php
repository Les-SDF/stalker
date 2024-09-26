<?php

namespace App\EventListener;

use App\Service\FlashMessageHelperInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Service\Attribute\Required;

final class MaintenanceListener
{


    public function __construct(private bool $maintenanceMode ,private RouterInterface $router, private FlashMessageHelperInterface $flashMessageHelper)
    {
    }

    #[AsEventListener(event: KernelEvents::REQUEST)]
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if ($this->maintenanceMode) {

            if ($request->get('_route') !== 'maintenance_page') {
                $response = new RedirectResponse($this->router->generate('maintenance_page'));
                $this->flashMessageHelper->addFlashMessage("n'essayer pas d'acceder à la page " . $request->get('_route') . " le site est sous maintenance !!!!!");
                $event->setResponse($response);
            }
        }elseif ($request->get('_route') === 'maintenance_page') {
            $response = new RedirectResponse($this->router->generate('homepage'));
            $event->setResponse($response);
        }
    }
}
