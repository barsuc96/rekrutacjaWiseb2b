<?php

declare(strict_types=1);


namespace Wise\Core\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Testowy Controller dla developera, można tu zapinać i wywoływać różne testowe rzeczy.
 * Rzeczy które tu zostaną zapinane nie powinny być nigdy wywoływane w produkcji.
 */
class DeveloperController extends AbstractController
{
    public function __construct(
        protected SerializerInterface $serializer
    ) {
    }

    #[Route(
        path: '/api/test',
        name: 'app.developer.test',
        methods: ['GET']
    )]
    public function indexAction()
    {
        if($_ENV['APP_ENV'] !== 'dev') {
            throw $this->createNotFoundException();
        }


        return 'test';
    }
}