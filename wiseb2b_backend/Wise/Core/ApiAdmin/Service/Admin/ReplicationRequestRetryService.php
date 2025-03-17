<?php

declare(strict_types=1);


namespace Wise\Core\ApiAdmin\Service\Admin;

use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Wise\Core\ApiAdmin\Dto\RequestDataDto\DeleteSingleObjectAdminApiRequestDataDto;
use Wise\Core\ApiAdmin\Dto\RequestDataDto\PutRequestDataDto;
use Wise\Core\ApiAdmin\Enum\ResponseStatusEnum;
use Wise\Core\ApiAdmin\Service\AbstractDeleteAdminApiService;
use Wise\Core\ApiAdmin\Service\AbstractDeleteService;
use Wise\Core\ApiAdmin\Service\AbstractPutAdminApiService;
use Wise\Core\ApiAdmin\Service\AbstractPutService;
use Wise\Core\ApiAdmin\ServiceInterface\Admin\ReplicationRequestRetryServiceInterface;
use Wise\Core\Domain\Admin\ReplicationRequest\ReplicationRequest;
use Wise\Core\Helper\Service\ChooseServiceByReplicationEndpointHelper;
use Wise\Core\Repository\Doctrine\ReplicationRequestRepositoryInterface;
use Wise\Core\Service\Interfaces\Admin\ReplicationServiceInterface;

class ReplicationRequestRetryService implements ReplicationRequestRetryServiceInterface
{
    public function __construct(
        protected readonly SerializerInterface $serializer,
        protected readonly DenormalizerInterface $denormalizer,
        protected readonly DecoderInterface $decoder,
        protected readonly RouterInterface $router,
        protected readonly ReplicationServiceInterface $replicationService,
        protected readonly ChooseServiceByReplicationEndpointHelper $chooseServiceByReplicationEndpointHelper,
        private readonly ReplicationRequestRepositoryInterface $replicationRequestRepository,
    ) {
    }

    public function retry(string $requestUuid, ?string $bodyJSON = null, ?string $headersJSON = null): JsonResponse
    {
        $replicationRequest = $this->replicationService->fetchRequest(uuid: $requestUuid);

        if ($replicationRequest->getResponseStatus() == ResponseStatusEnum::WAITING->value)
        {
            $replicationRequest->setResponseStatus(ResponseStatusEnum::IN_PROGRESS->value);
            $this->replicationRequestRepository->save($replicationRequest);
        }

        if ($replicationRequest instanceof ReplicationRequest) {
            $this->replicationService->setIdRequest($replicationRequest->getId());

            $service = $this->chooseServiceByReplicationEndpointHelper->chooseServiceByClass(
                $replicationRequest->getApiService(),
            );

            if($headersJSON !== null) {
                $headers = $this->decoder->decode($headersJSON, 'json');
            }else{
                $headers = $this->decoder->decode($replicationRequest->getRequestHeaders(), 'json');
            }

            $headers['x-request-uuid'] = $replicationRequest->getUuid();

            if($bodyJSON !== null){
                $replicationRequest->setRequestBody($bodyJSON);
            }

            switch ($replicationRequest->getMethod()) {
                case Request::METHOD_PATCH:
                    if($service instanceof AbstractPutService) {
                        /** @var AbstractPutService $service */
                        $response = $service->process(
                            $headers,
                            $replicationRequest->getRequestBody(),
                            $replicationRequest->getDtoClass(),
                            true,
                        );
                    } else if($service instanceof AbstractPutAdminApiService){

                        $requestDataDto = new PutRequestDataDto();
                        $requestDataDto->setRequestContent($replicationRequest->getRequestBody());
                        $requestDataDto->setClearRequestContent($replicationRequest->getRequestBody());
                        $requestDataDto->setHeaders(new HeaderBag($headers));
                        $requestDataDto->setRequestDtoClass($replicationRequest->getDtoClass());
                        $requestDataDto->setIsPatch(true);


                        $response = $service->process(requestDataDto: $requestDataDto);
                    }

                    break;
                case Request::METHOD_PUT:
                    if($service instanceof AbstractPutService) {
                        /** @var AbstractPutService $service */
                        $response = $service->process(
                            $headers,
                            $replicationRequest->getRequestBody(),
                            $replicationRequest->getDtoClass(),
                            false,
                        );
                    } else if($service instanceof AbstractPutAdminApiService){
                        $requestDataDto = new PutRequestDataDto();
                        $requestDataDto->setRequestContent($replicationRequest->getRequestBody());
                        $requestDataDto->setClearRequestContent($replicationRequest->getRequestBody());
                        $requestDataDto->setHeaders(new HeaderBag($headers));
                        $requestDataDto->setRequestDtoClass($replicationRequest->getDtoClass());

                        $response = $service->process(requestDataDto: $requestDataDto);
                    }

                    break;
                case Request::METHOD_DELETE:
                    $attributes = $this->decoder->decode($replicationRequest->getRequestAttributes(), 'json');
                    if($service instanceof AbstractDeleteService) {
                        /** @var AbstractDeleteService $service */
                        $response = $service->process(
                            $headers,
                            $attributes,
                            $replicationRequest->getDtoClass(),
                        );
                    } else if($service instanceof AbstractDeleteAdminApiService){
                        $requestDataDto = new DeleteSingleObjectAdminApiRequestDataDto();
                        $requestDataDto->setParameters(new InputBag($attributes));
                        $requestDataDto->setHeaders($headers);
                        $requestDataDto->setParametersDtoClass($replicationRequest->getDtoClass());

                        $response = $service->process(requestDataDto: $requestDataDto);
                    }

                    break;
                default:
                    break;
            }
        } else {
            throw new \Exception('Replication request not found');
        }

        return $response;
    }
}
