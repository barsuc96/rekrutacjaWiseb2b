<?php

namespace Wise\Core\ApiClient\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RedocViewController extends AbstractController
{
    #[Route(
        path: '/client-api/doc',
        name: 'app.redoc.api.client.v2',
        methods: ['GET']
    )]
    public function redocViewAction()
    {
        return $this->render('@WiseCore/redoc.html.twig',
            [
                'route' => 'app.swagger.api.client.v2',
                'title' => 'WiseB2B Client Api 2.0 - Redoc view'
            ]
        );
    }
}
