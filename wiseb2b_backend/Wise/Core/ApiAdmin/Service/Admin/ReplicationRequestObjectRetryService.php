<?php

declare(strict_types=1);


namespace Wise\Core\ApiAdmin\Service\Admin;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Wise\Core\ApiAdmin\Dto\CommonObjectIdResponseDto;
use Wise\Core\ApiAdmin\Service\AbstractDeleteService;
use Wise\Core\ApiAdmin\Service\AbstractPutService;
use Wise\Core\ApiAdmin\ServiceInterface\Admin\ReplicationRequestObjectRetryServiceInterface;
use Wise\Core\DataTransformer\CommonDataTransformer;
use Wise\Core\Helper\Service\ChooseServiceByReplicationEndpointHelper;
use Wise\Core\Service\Admin\ReplicationService;
use Wise\Core\Service\Interfaces\Admin\ReplicationServiceInterface;
use Wise\Core\Validator\ObjectValidator;

class ReplicationRequestObjectRetryService implements ReplicationRequestObjectRetryServiceInterface
{
    public function __construct(
        protected readonly SerializerInterface $serializer,
        protected readonly DenormalizerInterface $denormalizer,
        protected readonly DecoderInterface $decoder,
        protected readonly RouterInterface $router,
        protected readonly ReplicationServiceInterface $replicationService,
        protected readonly ChooseServiceByReplicationEndpointHelper $chooseServiceByReplicationEndpointHelper,
        protected readonly ObjectValidator $objectValidator,
    ) {
    }

    public function retry(?string $requestUuid, int $objectId, ?string $bodyJSON = null, ?int $requestId = null): array
    {
        $replicationObject = $this->replicationService->fetchObject(id: $objectId);
        $replicationRequest = $this->replicationService->fetchRequest(id: $replicationObject->getIdRequest());

        if ($requestId === null && $replicationRequest->getUuid() !== $requestUuid) {
            throw new Exception('Request UUID does not match with the object.');
        }

        $this->replicationService->setIdRequest($replicationRequest->getId());
        $this->replicationService->setIdObject($objectId);


        $service = $this->chooseServiceByReplicationEndpointHelper->chooseServiceByClass(
            $replicationRequest->getApiService(),
        );

        if($bodyJSON !== null){
            $replicationObject->setObject($bodyJSON);
        }

        $response = null;
        switch ($replicationRequest->getMethod()) {
            case Request::METHOD_PATCH:
                $object = $this->serializer->deserialize(
                    $replicationObject->getObject(),
                    $replicationObject->getObjectClass(),
                    'json',
                );

                $this->objectValidator->validate($object);

                $response = $service->put(
                    $object,
                    true
                );

                break;
            case Request::METHOD_PUT:
                $object = $this->serializer->deserialize(
                    $replicationObject->getObject(),
                    $replicationObject->getObjectClass(),
                    'json',
                );

                $this->objectValidator->validate($object);

                $response = $service->put(
                    $object,
                    false
                );

                break;
            case Request::METHOD_DELETE:
                /** @var AbstractDeleteService $service */
                $response = $service->delete(
                    $this->serializer->deserialize(
                        $replicationObject->getObject(),
                        $replicationObject->getObjectClass(),
                        'json',
                    )
                );
                break;
            default:
                break;
        }

        return CommonDataTransformer::transformToArray($response);
    }
}
