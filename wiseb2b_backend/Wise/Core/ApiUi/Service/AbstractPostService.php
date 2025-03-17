<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Service;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Wise\Core\ApiUi\ServiceInterface\ApiUiPostServiceInterface;
use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Exception\CommonApiException;
use Wise\Core\Exception\CommonLogicException;
use Wise\Core\Exception\CommonLogicException\UniqueConstraintViolationLogicException;
use Wise\Core\Exception\InvalidInputDataException;
use Exception;

/**
 * Klasa abstrakcyjna po której powinny dziedziczyć wszystkie PostSerwisy z UiApi
 * Finalna metoda process nie może być przeciążana, tu zawieramy wszelkie deseriailzacje requestu, walidacje i logowanie replikacji.
 * Metoda może być wywołana wyłącznie z Controllera Post{object}Controller przez klasę dziecziczącą po AbstractPostService.
 * Klasa dziedzicząca musi zawierać metodę post, która jest tu wywoływana do spersonalizowanego przetwarzania obiektu
 * @deprecated - zastąpiona przez \Wise\Core\Endpoint\Service\ApiUi\AbstractPostUiApiService
 */
abstract class AbstractPostService extends AbstractHttpService implements ApiUiPostServiceInterface
{
    public CommonModifyParams $serviceDto;

    final public function process(
        string $requestContent,
        string $dtoClass,
        array $additionalParameters = []
    ): JsonResponse {
        $requestContentDecoded = json_decode($requestContent);
        $this->serviceDto = new CommonModifyParams();

        /**
         * Usuwamy z requestu pole over_login_user_id, które jest przekazywane w celu obsługi przelogowania na innego użytkownika
         */
        if (property_exists($requestContentDecoded, 'over_login_user_id')) {
            unset($requestContentDecoded->over_login_user_id);
            $requestContent = json_encode($requestContentDecoded);
        }

        /**
         * Walidacja i konwersja danych wejściowych na klasę DTO
         */
        $dto = $this->prepareDto($requestContent, $dtoClass, $additionalParameters);

        /** Rozpoczęcie transakcji */
        $this->sharedActionService->repositoryManager->beginTransaction();

        /**
         * Wywołujemy tu metodę klasy dziedziczącej po AbstractPostService, ponieważ uniwersalna część kodu powinna już
         * zostać wykonana powyżej, a wspólny response jest zwracany poniżej.
         */
        try {
            $this->post($dto);
        } catch (CommonLogicException|CommonApiException $e) {
           $this->interpretException($e);
        } catch (UniqueConstraintViolationException $exception){
            $logicException = new UniqueConstraintViolationLogicException();
            if(str_contains($exception->getMessage(), 'Key (email, store_id)') || str_contains($exception->getMessage(), 'Key (login, store_id)')){
                $logicException->setTranslation('exceptions.user.unique_constraint_violation');
            }

            $this->interpretException($logicException);
        }

        /**
         * Obsługa transakcji w zależności od ustawionego końcowego statusu
         */
        $this->finishProcessing();

        $this->interpretNotifications();

        $this->prepareFieldsInfo();

        return $this->processJsonResponse();
    }

    protected function serviceDtoWrite(mixed $data, ?array $fieldMapping = []){
        $this->serviceDto->write($data, $fieldMapping);
        $this->fieldMapping = $fieldMapping;
    }

    /**
     * Walidacja i konwersja danych wejściowych na klasę DTO
     * @param bool|string $requestContent
     * @param string $dtoClass
     * @param array $additionalParameters
     * @return \Wise\Core\Dto\AbstractDto|\Wise\Core\Dto\AbstractResponseDto
     * @throws InvalidInputDataException
     */
    protected function prepareDto(bool|string $requestContent, string $dtoClass, array $additionalParameters)
    {
        try{
            return $this->sharedActionService->prepareDto($requestContent, $dtoClass, $additionalParameters);
        }catch (Exception $e){
            if(!($e instanceof CommonApiException)){
                throw new InvalidInputDataException(previous: $e);
            }
            throw $e;
        }
    }
}
