<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\Controller\Endpoint;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller do wyświetlania dokumentacji w formacie Redoc
 */
class RedocViewController extends AbstractController
{
    #[Route(
        path: '/admin-api/doc',
        name: 'app.redoc.api.admin.v2',
        methods: ['GET']
    )]
    public function redocViewAction(): Response
    {
        return $this->render('@WiseCore/redoc.html.twig',
            [
                'route' => 'app.swagger.api.admin.v2',
                'title' => 'WiseB2B ApiAdmin Api 2.0 - Redoc view'
            ]
        );
    }
}
