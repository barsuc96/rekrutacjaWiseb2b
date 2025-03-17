<?php

declare(strict_types=1);

namespace  Wise\Security\ApiAdmin\Controller\Oauth2;

use Nelmio\ApiDocBundle\Annotation\Security as ApiSecurity;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Wise\Core\ApiAdmin\Controller\AdminApiBaseController;
use Wise\Core\Exception\CommonLogicException;
use Wise\Security\Service\Interfaces\SetOauthApiClientIdServiceInterface;

class PostTokenController extends AdminApiBaseController
{
    #[Pure]
    public function __construct(
        private readonly SetOauthApiClientIdServiceInterface $setOauthApiClientIdService,
    ) {
    }

    #[Route(
        path: '/token',
        name: 'authorizationToken',
        methods: ['POST']
    )]
    #[OA\Tag(
        name: 'AccessToken'
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: "#/components/schemas/PostTokenDto", type: "object")
    )]
    #[ApiSecurity(name: null)]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Zwrotka z tokenem do autoryzacji',
        content: new OA\JsonContent(ref: "#/components/schemas/PostTokenResponseDto", type: "object")
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: 'Niepoprawne dane wejściowe',
        content: new OA\JsonContent(ref: "#/components/schemas/InvalidInputDataResponseDto", type: "object")
    )]
    public function action(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $data['grant_type'] = "client_credentials";

        if(!isset($data['client_id']) || !isset($data['client_secret'])) {
            return new JsonResponse(json_encode(['message' => 'Fields: "client_id" and "client_secret" are required']), status: Response::HTTP_UNAUTHORIZED, json: true);
        }

        /** @var Response $response */
        $response = $this->container
            ->get('http_kernel')
            ->handle($this->prepareOAuthTokenRequest($data), HttpKernelInterface::SUB_REQUEST);

        ($this->setOauthApiClientIdService)($data['client_id']);

        return new JsonResponse($response->getContent(), json: true);
    }

    private function prepareOAuthTokenRequest(array $payload): Request
    {
        $request = new Request();
        $request->attributes->set('_controller', ['league.oauth2_server.controller.token', 'indexAction']);
        $request->server->add([
            'REQUEST_METHOD' => Request::METHOD_POST,
            'SERVER_NAME' => 'localhost',
            'SERVER_PORT' => '80',
            'HTTPS' => 'off',
            'REQUEST_URI' => '/token',
        ]);
        $request->headers->add(['content-type' => 'application/x-www-form-urlencoded']);
        $request->request->add($payload);

        return $request;
    }
}
