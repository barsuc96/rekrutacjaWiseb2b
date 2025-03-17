<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Service;

use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Wise\Core\ApiAdmin\Enum\ResponseStatusEnum;
use Wise\Core\ApiUi\Dto\CommonGetItemResponseDto;
use Wise\Core\ApiUi\Dto\CommonGetUiApiDto;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\Interface\PayloadBagUIAPISerializerServiceInterface;
use Wise\Core\ApiUi\ServiceInterface\ApiUiGetDetailsServiceInterface;
use Wise\Core\Entity\PayloadBag\PayloadBag;
use Wise\Core\Enum\ResponseMessageStyle;
use Wise\Core\Exception\CommonLogicException;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Core\Exception\ObjectValidationException;
use Wise\Core\Helper\String\StringHelper;
use Wise\Core\Service\OverLoginUserParams;

/**
 * Klasa abstrakcyjna dla serwisów typu GET obiekt,
 *
 * @deprecated - zastąpiona przez \Wise\Core\Endpoint\Service\ApiUi\AbstractGetDetailsUiApiService
 */
abstract class AbstractGetDetailsService implements ApiUiGetDetailsServiceInterface
{
    protected bool $showMessage = true;
    protected bool $showModal = false;

    public function __construct(
        private readonly UiApiShareMethodsHelper $shareMethodsHelper,
    ) {}

    /**
     * TODO: W przyszłości możemy zrefaktorować controlery i przekazywać do serwisów dto:
     *  https://gist.github.com/cierzniak/5ce0449980d0212747dab3d4b134326a
     *
     * @param Request $request
     * @param string $dtoClass
     * @param array|null $attributes
     * @return JsonResponse
     * @throws ExceptionInterface
     * @throws ObjectValidationException
     */
    final public function process(Request $request, string $dtoClass, ?array $attributes = []): JsonResponse
    {
        $parameters = $request->query;
        $this->supportSwitchUser($request);

        $normalizer = new ObjectNormalizer();

        /** @var CommonGetUiApiDto $dto */
        $dto = $normalizer->denormalize($parameters->all(), $dtoClass);
        $this->shareMethodsHelper->getObjectValidator()->validate($dto);

        // Przerobienie parametrów z snake_case na camelCase
        $parametersAdjusted = new InputBag();
        foreach ($parameters->all() as $key => $parameterValue) {
            $parametersAdjusted->add([StringHelper::snakeToCamel($key) => $parameterValue]);
        }

        try{

            $objects = $this->get($parametersAdjusted, $attributes);

        }catch (ObjectNotFoundException $e){
            return $this->shareMethodsHelper->prepareObjectNotFoundResponse(
                fieldsInfo: [],
                status: ResponseStatusEnum::FAILED->value,
                showMessage: $this->showMessage,
                showModal: $this->showModal,
                message: ($e->getTranslationKey() !== null) ? $this->shareMethodsHelper->translate($e->getTranslationKey(), $e->getTranslationParams()) : $e->getMessage(),
                messageStyle: ResponseMessageStyle::FAILED->value
            );
        }catch (CommonLogicException $e){
            return $this->shareMethodsHelper->prepareProcessErrorResponse(
                fieldsInfo: [],
                status: ResponseStatusEnum::FAILED->value,
                showMessage: $this->showMessage,
                showModal: $this->showModal,
                message: ($e->getTranslationKey() !== null) ? $this->shareMethodsHelper->translate($e->getTranslationKey(), $e->getTranslationParams()) : $e->getMessage(),
                messageStyle: ResponseMessageStyle::FAILED->value
            );
        }


        return (new CommonGetItemResponseDto(
            $objects
        ))->jsonSerialize();
    }

    protected function supportSwitchUser(Request $request){

        // Pobieramy parametr switch_user_by_id z url lub nagłówka
        $switchUserById = $request->query->get('switch_user_by_id') ?? $request->headers->get('switch_user_by_id');
        $switchUserById = ($switchUserById !== null) ? (int)$switchUserById : null;

        $overLoginUserParams = new OverLoginUserParams();
        $overLoginUserParams->setUserId($switchUserById);

        ($this->shareMethodsHelper->coreAutoOverloginUserService)($overLoginUserParams);

        $request->query->remove('switch_user_by_id');
    }

    /**
     * Metoda serializuje obiekt PayloadBag i przygotowuje dane do zwrócenia
     * @param PayloadBag|array $payloadBag - obiekt PayloadBag
     * @param string|null $language - język w jakim mają być zwrócone dane (ponieważ serializator obsługuje pole typu Translation)
     * @param PayloadBagUIAPISerializerServiceInterface $payloadBagUIAPISerializerService - serwis serializujący PayloadBag (przekazywany jako parametr, aby uniknąć zależności cyklicznych)
     * @param int|null $elementId - id elementu
     * @return array
     * @throws ExceptionInterface
     * @throws \ReflectionException
     */
    protected function preparePayloadData(PayloadBag|array $payloadBag, ?string $language, PayloadBagUIAPISerializerServiceInterface $payloadBagUIAPISerializerService, ?int $elementId = null): array
    {
        // Deserializacja PayloadBag
        $deserializedPayloadBag = $payloadBagUIAPISerializerService->deserializeSinglePayloadBag($payloadBag, $language, strval($elementId));
        unset($deserializedPayloadBag['id']);

        return $deserializedPayloadBag;
    }
}
