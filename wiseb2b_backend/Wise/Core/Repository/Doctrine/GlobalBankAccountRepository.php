<?php

declare(strict_types=1);

namespace Wise\Core\Repository\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Model\BankAccount;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Repository\AbstractRepository;
use Wise\I18n\Domain\Country\CountryServiceInterface;
use Wise\Core\Exception\ObjectNotFoundException;

/**
 * @extends ServiceEntityRepository<GlobalBankAccount>
 *
 * @method GlobalBankAccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method GlobalBankAccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method GlobalBankAccount[]    findAll()
 * @method GlobalBankAccount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GlobalBankAccountRepository extends AbstractRepository implements GlobalBankAccountRepositoryInterface
{
    protected const ENTITY_CLASS = GlobalBankAccount::class;

    public function __construct(
        ManagerRegistry $registry,
        private readonly CountryServiceInterface $countryService
    ) {
        parent::__construct($registry);
    }

    public function getGlobalBankAccount(
        string $entityName,
        string $entityFieldName,
        int $entityId
    ): array {
        $globalBankAccount = $this->findByQueryFiltersView(
            [
                new QueryFilter('entityName', $this->getMappedEntityName($entityName)),
                new QueryFilter('filedName', $entityFieldName),
                new QueryFilter('entityId', $entityId),
            ]
        );

        if ($globalBankAccount) {
            return [
                'owner_name' => $globalBankAccount[0]['ownerName'],
                'account' => $globalBankAccount[0]['account'],
                'bank_country_id' => $globalBankAccount[0]['bankCountryId'],
                'bank_address' => $globalBankAccount[0]['bankAddress'],
                'bank_name' => $globalBankAccount[0]['bankName'],
            ];
        }

        return [];
    }

    public function getGlobalBankAccountByEntityIds(
        string $entityName,
        string $entityFieldName,
        array $entityIds
    ): array {
        $globalBankAccounts = $this->findByQueryFiltersView(
            [
                new QueryFilter('entityName', $this->getMappedEntityName($entityName)),
                new QueryFilter('filedName', $entityFieldName),
                new QueryFilter('entityId', $entityIds, QueryFilter::COMPARATOR_IN),
            ]
        );

        $result = [];

        foreach ($globalBankAccounts as $globalBankAccount){
            $result[] = [
                'entity_id' => $globalBankAccount['entityId'],
                'owner_name' => $globalBankAccount['ownerName'],
                'account' => $globalBankAccount['account'],
                'bank_country_id' => $globalBankAccount['bankCountryId'],
                'bank_address' => $globalBankAccount['bankAddress'],
                'bank_name' => $globalBankAccount['bankName'],
            ];
        }

        return $result;
    }

    /**
     * @throws ObjectNotFoundException
     */
    public function prepareAndSaveGlobalBankAccount(
        AbstractEntity $entity,
        $entityFieldName,
        $isActive = true
    ): void {
        $bankAccount = null;

        //Tworzymy nazwę metody którą chcemy odpalić
        $getMethod = 'get'.ucfirst($entityFieldName);

        //Sprawdzamy czy stworzona metoda istnieje na danej encji, jeśli tak to odpalmy
        if (method_exists($entity, $getMethod)) {
            $bankAccount = $entity->$getMethod();
        }

        if ($bankAccount instanceof BankAccount) {
            $globalBankAccount = $this->findOneBy([
                'entityName' => $this->getMappedEntityName($entity::class),
                'filedName' => $entityFieldName,
                'entityId' => $entity->getId()
            ]);

            if ($globalBankAccount === null) {
                $globalBankAccount = new GlobalBankAccount();
            }

            $country = $this->countryService->getOrCreateCountry(null, $bankAccount->getBankCountryId());

            $globalBankAccount
                ->setEntityName($this->getMappedEntityName($entity::class))
                ->setEntityId($entity->getId())
                ->setFiledName($entityFieldName)
                ->setOwnerName($bankAccount->getOwnerName())
                ->setAccount($bankAccount->getAccount())
                ->setBankCountryId($country->getId())
                ->setBankAddress($bankAccount->getBankAddress())
                ->setBankName($bankAccount->getBankName())
                ->setIsActive($isActive)
            ;

            $this->save($globalBankAccount);
        }
    }

    protected function getMappedEntityName(string $entityName): string
    {
        return $this->_em->getClassMetadata($entityName)->getTableName();
    }
}
