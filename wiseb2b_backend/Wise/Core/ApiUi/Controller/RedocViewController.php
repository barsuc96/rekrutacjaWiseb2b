<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller do wyświetlania dokumentacji API w formacie Redoc
 */
class RedocViewController extends AbstractController
{
    #[Route(
        path: '/ui-api/doc',
        name: 'app.redoc.api.ui.v2',
        methods: ['GET']
    )]
    public function redocViewAction()
    {
        return $this->render('@WiseCore/redoc.html.twig',
            [
                'route' => 'app.swagger.api.ui.v2',
                'title' => 'WiseB2B Ui Api 2.0 - Redoc view'
            ]
        );
    }
}
