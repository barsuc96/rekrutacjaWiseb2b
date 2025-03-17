<?php

namespace Wise\Agreement\Domain\Contract\Listener;


use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Wise\Agreement\Domain\Contract\Event\ContractContentHasChangedEvent;
use Wise\Agreement\Domain\Contract\Exception\ChangeContractContentOnActiveContractAgreementException;
use Wise\Agreement\Service\ContractAgreement\Interfaces\ListContractAgreementServiceInterface;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Model\Translation;
use Wise\Core\Model\Translations;
use Wise\Core\Service\CommonListParams;

#[AsEventListener(event: 'contract.content.has.changed')]
class BlockChangeContentWhenContractHaveAgreementsListener
{
    public function __construct(
        private readonly ListContractAgreementServiceInterface $listContractAgreementService
    ){}

    public function __invoke(ContractContentHasChangedEvent $event): void
    {
        // Jeżeli umowa nie ma id, to nie ma sensu sprawdzać zatwierdzeń (ponieważ jest to nowa umowa)
        if($event->getId() === null){
            return;
        }

        // Pobieramy wszystkie zatwierdzenia dla umowy
        $agreeContracts = $this->getContractAgreements($event->getId());

        // Jeżeli umowa ma zatwierdzenia, to nie można zmieniać treści umowy
        if(!empty($agreeContracts) && $this->hasChangedContent($event)){
            throw new ChangeContractContentOnActiveContractAgreementException();
        }
    }

    /**
     * Pobranie zatwierdzeń dla umowy
     * @param int $contractId
     * @return array
     */
    protected function getContractAgreements(int $contractId): array
    {
        $params = new CommonListParams();
        $params
            ->setFilters([
                new QueryFilter('contractId', $contractId),
                new QueryFilter('agreeDate', null, QueryFilter::COMPARATOR_IS_NOT_NULL),
                new QueryFilter('disagreeDate', null, QueryFilter::COMPARATOR_IS_NULL)
            ])
            ->setFields([]);


        return ($this->listContractAgreementService)($params)->read();
    }

    /**
     * Sprawdzenie czy treść została zmieniona
     * @param ContractContentHasChangedEvent $event
     * @return bool
     */
    protected function hasChangedContent(ContractContentHasChangedEvent $event): bool
    {
        if(empty($event->getNewContent())){
            return false;
        }

        if($event->getOldContent() === null && !empty($event->getNewContent())){
            return false;
        }

        if($event->getOldContent() instanceof Translations){
            $oldArray = [];
            /** @var Translation $value */
            foreach ($event->getOldContent() as $value){
                $oldArray[] = [
                    'language' => $value->getLanguage(),
                    'translation' => $value->getTranslation()
                ];
            }

            $oldJContentJson = json_encode($oldArray);
            $currentContentJson = json_encode($event->getNewContent());
            if($oldJContentJson !== $currentContentJson){
                return true;
            }
        }

        if(is_array($event->getOldContent()) && is_array($event->getNewContent())){
            return $event->getOldContent() !== $event->getNewContent();
        }


        return false;
    }
}
