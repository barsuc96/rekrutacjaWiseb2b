<?php

declare(strict_types=1);

namespace Wise\Client\Repository\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;
use Wise\Client\Domain\Client\Client;
use Wise\Client\Domain\Client\ClientRepositoryInterface;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Core\Repository\AbstractRepository;
use Wise\Core\Repository\Doctrine\GlobalAddressRepositoryInterface;
use Wise\Core\Repository\Doctrine\GlobalBankAccountRepositoryInterface;

/**
 * @extends ServiceEntityRepositoryInterface<Client>
 *
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends AbstractRepository implements ClientRepositoryInterface
{
    // TODO: Przeciazyc Find aby wczytywał się cały łącznie z adresami, bankami itd...

    public const ENTITY_CLASS = Client::class;
    public const REGISTER_ADDRESS_ENTITY_FIELD_NAME = 'registerAddress';
    public const RETURN_BANK_ACCOUNT_ENTITY_FIELD_NAME = 'returnBankAccount';
    public const RETURN_CLIENT_REPRESENTATIVE_PERSON_FIRSTNAME_FIELD_NAME = 'clientRepresentative.personFirstname';
    public const RETURN_CLIENT_REPRESENTATIVE_PERSON_LASTNAME_FIELD_NAME = 'clientRepresentative.personLastname';

    public function __construct(
        ManagerRegistry $registry,
        private readonly GlobalAddressRepositoryInterface $globalAddressRepository,
        private readonly GlobalBankAccountRepositoryInterface $globalBankAccountRepository,
    ) {
        parent::__construct($registry);
    }

    /**
     * @param Client $entity
     * @param bool $flush
     *
     * @return Client
     * @throws ObjectNotFoundException
     */
    public function save(AbstractEntity $entity, bool $flush = false): AbstractEntity
    {
        $this->getEntityManager()->persist($entity);

        $this->globalAddressRepository->prepareAndSaveGlobalAddress(
            $entity,
            self::REGISTER_ADDRESS_ENTITY_FIELD_NAME
        );

        $this->globalBankAccountRepository->prepareAndSaveGlobalBankAccount(
            $entity,
            self::RETURN_BANK_ACCOUNT_ENTITY_FIELD_NAME
        );

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $entity;
    }

    public function findByQueryFiltersView(
        array $queryFilters,
        array $orderBy = null,
        $limit = null,
        $offset = null,
        ?array $fields = [],
        ?array $joins = [],
        ?array $aggregates = [],
    ): array {

        $fetchData = [];

        foreach ($fields as $fieldKey => $field) {

            if (str_starts_with($field,
                explode('.', self::RETURN_CLIENT_REPRESENTATIVE_PERSON_FIRSTNAME_FIELD_NAME)[0] . '.')) {
                $fetchData[] = self::RETURN_CLIENT_REPRESENTATIVE_PERSON_FIRSTNAME_FIELD_NAME;
                unset($fields[$fieldKey]);
            }

            if (str_starts_with($field,
                explode('.', self::RETURN_CLIENT_REPRESENTATIVE_PERSON_LASTNAME_FIELD_NAME)[0] . '.')) {
                $fetchData[] = self::RETURN_CLIENT_REPRESENTATIVE_PERSON_LASTNAME_FIELD_NAME;
                unset($fields[$fieldKey]);
            }

            if ($field === self::REGISTER_ADDRESS_ENTITY_FIELD_NAME) {
                $fetchData[] = self::REGISTER_ADDRESS_ENTITY_FIELD_NAME;
                unset($fields[$fieldKey]);
            }

            if ($field === self::RETURN_BANK_ACCOUNT_ENTITY_FIELD_NAME) {
                $fetchData[] = self::RETURN_BANK_ACCOUNT_ENTITY_FIELD_NAME;
                unset($fields[$fieldKey]);
            }
        }

        if(!in_array('id', $fields)){
            $fields['id'] = 'id';
        }

        $clients = parent::findByQueryFiltersView(
            $queryFilters,
            $orderBy,
            $limit,
            $offset,
            $fields,
            $joins,
            $aggregates
        );

        $this->fetchAggregates($clients, $aggregates);
        $clientsIds = array_column($clients, 'id');


        // Pobranie dodatkowych danych
        if (
            in_array(self::RETURN_CLIENT_REPRESENTATIVE_PERSON_FIRSTNAME_FIELD_NAME, $fetchData) ||
            in_array(self::RETURN_CLIENT_REPRESENTATIVE_PERSON_LASTNAME_FIELD_NAME, $fetchData)
        ) {
            $clientsRepresentative = $this->findClientRepresentativeByIds($clientsIds);
        }

        if (in_array(self::RETURN_BANK_ACCOUNT_ENTITY_FIELD_NAME, $fetchData)) {
            $globalBankAccounts = $this->globalBankAccountRepository->getGlobalBankAccountByEntityIds(
                entityName: static::ENTITY_CLASS,
                entityFieldName: self::RETURN_BANK_ACCOUNT_ENTITY_FIELD_NAME,
                entityIds: $clientsIds,
            );
        }


        /** @var Client $client */
        foreach ($clients as &$client) {
            if (in_array(self::REGISTER_ADDRESS_ENTITY_FIELD_NAME, $fetchData)) {
                //Pobieramy dane adresowe
                $globalAddress = $this->globalAddressRepository->getGlobalAddress(
                    static::ENTITY_CLASS,
                    self::REGISTER_ADDRESS_ENTITY_FIELD_NAME,
                    (int)($client['t0_id'] ?? $client['id'])
                );

                if ($globalAddress) {
                    $client[self::REGISTER_ADDRESS_ENTITY_FIELD_NAME] = $globalAddress;
                }
            }

            if (in_array(self::RETURN_BANK_ACCOUNT_ENTITY_FIELD_NAME, $fetchData)) {

                // Szukamy obiektu w $globalBankAccounts odpowiadającemu klientowi
                $result = array_filter($globalBankAccounts, function ($object) use ($client) {
                    return $object['entity_id'] === $client['id'];
                });
                $globalBankAccount = reset($result);

                if ($globalBankAccount) {
                    unset($globalBankAccount['entity_id']);
                    $client[self::RETURN_BANK_ACCOUNT_ENTITY_FIELD_NAME] = $globalBankAccount;
                }
            }

            if (
                in_array(self::RETURN_CLIENT_REPRESENTATIVE_PERSON_FIRSTNAME_FIELD_NAME, $fetchData) ||
                in_array(self::RETURN_CLIENT_REPRESENTATIVE_PERSON_LASTNAME_FIELD_NAME, $fetchData)
            ) {

                // Szukamy obiektu w $clientsRepresentative odpowiadającemu klientowi
                $result = array_filter($clientsRepresentative, function ($object) use ($client) {
                    return $object['id'] === $client['id'];
                });
                $clientRepresentative = reset($result);

                // Jeśli istnieje element
                if($clientRepresentative){
                    // Uzupełniamy klienta danymi
                    if (in_array(self::RETURN_CLIENT_REPRESENTATIVE_PERSON_FIRSTNAME_FIELD_NAME, $fetchData)) {
                        $client['personFirstname'] = $clientRepresentative[self::RETURN_CLIENT_REPRESENTATIVE_PERSON_FIRSTNAME_FIELD_NAME] ?? null;
                    }

                    if (in_array(self::RETURN_CLIENT_REPRESENTATIVE_PERSON_LASTNAME_FIELD_NAME, $fetchData)) {
                        $client['personLastname'] = $clientRepresentative[self::RETURN_CLIENT_REPRESENTATIVE_PERSON_LASTNAME_FIELD_NAME] ?? null;
                    }
                }


            }
        }

        return $clients;
    }

    private function fetchAggregates(&$clients, $aggregates): void
    {
        if (empty($aggregates)) {
            return;
        }

        $clientsAssociative = [];
        foreach ($clients as $client) {
            $clientsAssociative[$client['id']] = $client;
        }

        if (count($clientsAssociative) === 0) {
            return;
        }

        if (in_array('clientRepresentative', $aggregates)) {
            $this->fetchClientRepresentativeAggregate($clientsAssociative);
        }

        $clients = $clientsAssociative;
    }

    private function fetchClientRepresentativeAggregate(&$clientsAssociative): void
    {
        $clientsRepresentatives = $this->findClientRepresentativeByIds(array_keys($clientsAssociative));

        foreach ($clientsRepresentatives as $clientRepresentative) {
            $clientsAssociative[$clientRepresentative['id']] += [
                'personFirstname' => $clientRepresentative['clientRepresentative.personFirstname'],
                'personLastname' => $clientRepresentative['clientRepresentative.personLastname'],
            ];
        }
    }

    public function getAdditionalDataByIds(array $ids, bool $overwriteCache = false): array
    {
        return [];
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?object
    {
        /** @var Client $client */
        $client = parent::findOneBy($criteria, $orderBy);
        if ($client === null) {
            return null;
        }

        // Pobiera informacje o koncie bankowym i dodaje te informacje do klienta
        $bankAccount = $this->globalBankAccountRepository->getGlobalBankAccount(
            static::ENTITY_CLASS,
            self::RETURN_BANK_ACCOUNT_ENTITY_FIELD_NAME,
            $client->getId());
        $client->setReturnBankAccount($bankAccount);

        // Pobiera informacje o adresie i dodaje te informacje do klienta
        $address = $this->globalAddressRepository->getGlobalAddressAsAddress(
            static::ENTITY_CLASS,
            self::REGISTER_ADDRESS_ENTITY_FIELD_NAME,
            $client->getId());
        $client->setRegisterAddress($address);

        return $client;
    }

    public function findClientRepresentativeByIds(array $ids): array
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c.id, c.clientRepresentative.personFirstname, c.clientRepresentative.personLastname')
            ->where('c.id IN (:ids)')
            ->setParameter('ids', $ids);

        return $qb->getQuery()->getResult();
    }

    /**
     * Zwraca typ adresu określającego główny adres klienta
     * @return string
     */
    public function getRegisterAddressEntityFieldName(): string
    {
        return static::REGISTER_ADDRESS_ENTITY_FIELD_NAME;
    }
}
