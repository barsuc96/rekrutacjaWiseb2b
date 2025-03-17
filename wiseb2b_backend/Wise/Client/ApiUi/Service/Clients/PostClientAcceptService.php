<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Service\Clients;

use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Client\ApiUi\Service\Clients\Interfaces\PostClientAcceptServiceInterface;
use Wise\Client\Service\Client\AcceptClientParams;
use Wise\Client\Service\Client\Interfaces\AcceptClientServiceInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractPostUiApiService;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonModifyParams;

class PostClientAcceptService extends AbstractPostUiApiService implements PostClientAcceptServiceInterface
{
    /**
     * Klucz translacji — zwracany, gdy proces się powiedzie
     * @var string
     */
    protected string $messageSuccessTranslation = 'client.status_changed';


    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly TranslatorInterface $translator,
        private readonly AcceptClientServiceInterface $acceptClientService,
    ) {
        parent::__construct($sharedActionService, $acceptClientService);
    }

    /**
     * Metoda uzupełnia parametry dla serwisu
     * @param AbstractDto $dto
     * @return CommonModifyParams
     */
    protected function fillParams(AbstractDto $dto): CommonModifyParams
    {
        $params = new AcceptClientParams();
        $params->setClientId($dto->getId());

        return $params;
    }
}
