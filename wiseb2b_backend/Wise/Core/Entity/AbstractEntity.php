<?php

declare(strict_types=1);

namespace Wise\Core\Entity;

use DateTime;
use DateTimeInterface;
use ReflectionProperty;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Wise\Core\DataTransformer\CommonDataTransformer;
use Wise\Core\DataTransformer\PropertyIsInitializedInterface;
use Wise\Core\Entity\PayloadBag\AbstractPayload;
use Wise\Core\Entity\PayloadBag\PayloadBag;
use Wise\Core\Model\MergableInterface;
use Wise\Core\Model\ValidatableInterface;

/**
 * Bazowy obiekt encji dla admin api, po nim powinny dziedziczyć wszystkie encje replikowane. Zawiera podstawowe pola
 * i indeksy na bazie. Zawiera również automatyczne metody przypisujące dany dodania i modyfikacji - niezwykle ważne
 * przy logowaniu w Admin Api
 */
abstract class AbstractEntity implements PropertyIsInitializedInterface, MergableInterface, ValidatableInterface
{
    protected const CACHE_TAG_PREFIX  = '';

    /**
     * Identyfikator encji
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * Czy encja jest aktywna
     * @var bool|null
     */
    protected ?bool $isActive = null;

    /**
     * Data utworzenia encji
     * @var DateTimeInterface|null
     */
    protected ?\DateTimeInterface $sysInsertDate = null;

    /**
     * Data ostatniej aktualizacji encji
     * @var DateTimeInterface|null
     */
    protected ?\DateTimeInterface $sysUpdateDate = null;

    /** Hash zawartości encji. Wykorzystywany do weryfikacji zmian w encji */
    protected ?string $entityHash = null;

    /**
     * Informacja, w jakiej kolejności mają zostać zwrócone dane
     * @var int
     */
    protected int $sortOrder = 0;

    /**
     * Kontener struktur payload (dodatkowe informacje zapisywane w ramach encji, dla których nie chcemy tworzyć dodatkowych pól)
     * @var PayloadBag|null
     */
    #[Ignore]
    protected ?PayloadBag $payloadBag;

    /**
     * Flagi procesu
     * @var array
     */
    #[Ignore]
    protected array $processFlags = [];


    // TODO: Do omówienia czy to ma sens biznesowy
    #[Ignore]
    public function setUpdateDate()
    {
        $this->sysUpdateDate = new DateTime();
    }

    // TODO: Do omówienia czy to ma sens biznesowy
    #[Ignore]
    public function setInsertDate()
    {
        $this->sysInsertDate = new DateTime();
        $this->sysUpdateDate = $this->sysInsertDate;
    }

    #[Ignore]
    public static function getCacheTag(int $id): string
    {
        if(!empty(static::CACHE_TAG_PREFIX )){
            return static::CACHE_TAG_PREFIX . '_' . $id;
        }

        $className = (new \ReflectionClass(static::class))->getShortName();

        return $className. '_' .$id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSysInsertDate(): ?\DateTimeInterface
    {
        return $this->sysInsertDate;
    }

    public function getSysUpdateDate(): ?\DateTimeInterface
    {
        return $this->sysUpdateDate;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): AbstractEntity
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @param string|null $entityHash
     * @return self
     */
    public function setEntityHash(?string $entityHash): self
    {
        $this->entityHash = $entityHash;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEntityHash(): ?string
    {
        return $this->entityHash;
    }

    /**
	 * @return int
	 */
	public function getSortOrder(): int {
		return $this->sortOrder;
	}

	/**
	 * @param int $sortOrder
	 * @return self
	 */
	public function setSortOrder(int $sortOrder): self {
		$this->sortOrder = $sortOrder;
		return $this;
	}

    /**
     * Sprawdzenie czy dany atrybut naszego obiektu DTO został zdefiniowany, przydatne po deserializacji requestu
     * @throws ReflectionException
     */
    #[Ignore]
    public function isInitialized(string $property): bool
    {
        $rp = new ReflectionProperty(static::class, $property);

        return $rp->isInitialized($this);
    }

    /**
     * Walidacja biznesowa obiektu
     * @return bool
     * @throws ValidationFailedException
     */
    public function validate(): bool
    {
        $newHash = $this->hashCalculate();

        if ($this->entityHash !== $newHash && $this->id !== null) {
            $this->entityHasChanged($newHash);
        }

        return true;
    }

    /**
     * Utworzenie obiektu z arraya
     * @param array $data
     * @return $this
     * @deprecated Dołączanie danych encji przez wewnętrzne metody jest niezalecane, użyj {@see \Wise\Core\Service\Merge\MergeService}
     */
    public function create(array $data): self
    {

        $object = CommonDataTransformer::transformFromArray($data, static::class);

        // TODO: weryfikacja czy obiekt jest odpowiedniej klasy
        //if ($object instanceof self::class) { // ta składnia z jakiegoś powodu nie działa
        return $object;
        //} else {
        //    throw new ObjectTransformingException('Cannot transform array data to object: ' . self::class);
        //}
    }

    // TODO: tutaj metoda merge która tak jak create przyjmuje na wejście tablicę danych i merguje je z obiektem
    // TODO: zrobić aby interface wymagał implementacji tej metody
    /** @deprecated Dołączanie danych encji przez wewnętrzne metody jest niezalecane, użyj {@see \Wise\Core\Service\Merge\MergeService} */
    public function merge(array $data): void
    {
        foreach ($data as $key => $value) {
            //jeżeli property istnieje
            if (property_exists($this, $key)) {
                // Jeżeli mamy typ prosty to przypisujemy jej wartość seterem
                if (in_array($propertyType = $this->getPropertyDeclaredClass($key), [
                    'int',
                    'string',
                    'bool',
                    'float'
                ])) {
                    // jeżeli jest to wartość prosta (int, string, bool) to po prostu przypisujemy

                    //Tworzymy nazwę metody którą chcemy odpalić
                    $setMethod = 'set' . ucfirst($key);

                    //Sprawdzamy czy stworzona metoda istnieje na danej encji, jeśli tak to odpalmy
                    if (method_exists($this, $setMethod)) {
                        $this->$setMethod($value);
                    }
                } elseif (in_array(MergableInterface::class, class_implements($propertyType))) {
                    // jeżeli jest mergowalne to mergujemy
                    if (is_null($this->$key)) {
                        $object = new $propertyType();
                    } else {
                        $object = $this->$key;
                    }
                    $object->merge($data[$key]);

                    //Tworzymy nazwę metody którą chcemy odpalić
                    $setMethod = 'set' . ucfirst($key);

                    //Sprawdzamy czy stworzona metoda istnieje na danej encji, jeśli tak to odpalmy
                    if (method_exists($this, $setMethod)) {
                        $this->$setMethod($object);
                    }
                } elseif (in_array(DateTimeInterface::class, class_implements($propertyType), true)) {
                    $setMethod = 'set' . ucfirst($key);

                    if (method_exists($this, $setMethod)) {
                        $this->$setMethod($data[$key]);
                    }
                } else {
                    throw new \Exception("Nieobsługiwany typ danych: $propertyType");
                }
            } else {
                throw new \Exception("Property '$key' doesn't exist");
            }
        }
    }

    protected
    function getPropertyDeclaredClass(
        string $property
    ): string {
        $reflection = new \ReflectionClass(get_class($this));
        $propertyInstance = $reflection->getProperty($property);
        $type = $propertyInstance->getType()->getName();

        return $type;
    }

    /**
     * Rzutuje this na json'a i liczy z niego hash
     * @return string
     */
    #[Ignore]
    protected function hashCalculate(): string
    {
        /**
         *  ---- Don't check update date when calculating hash
         *
         * it causes entityHasChange event call even if entity hasn't changed,
         * only update date has been updated because of some
         * field was updated for the same value as before
         */
        /**
         * Don't check entity hash when calculating hash
         *
         * 1. new entity has no hash - so it is calculated with null in hash
         * 2. same entry saved and fetched from database has some hash - so it is calculated with some value in hash
         *      even if there is no change, entityHasChanged event is called
         */

        $clone = clone $this;
        $clone->entityHash = null;
        $clone->sysUpdateDate = null;

        $stringToHash = serialize($clone);

        return hash('sha256', $stringToHash, false, ['seed' => 1]);
    }

    /**
     * Rzutuje this na json'a i liczy z niego hash
     * @param string $newHash
     * @return void
     */
    protected function entityHasChanged(string $newHash): void
    {
        $this->setEntityHash($newHash);
    }

    /**
     * Zwraca kontener PayloadBag
     * @return PayloadBag|null
     */
    #[Ignore]
    public function getPayloadBag(): ?PayloadBag
    {
        if(!$this->isInitialized('payloadBag') || empty($this->payloadBag)){
            return new PayloadBag();
        }

        return $this->payloadBag;
    }

    /**
     * Nadpisuje kontener PayloadBag
     * @param PayloadBag|null $payloadBag
     * @return $this
     */
    #[Ignore]
    public function setPayloadBag(?PayloadBag $payloadBag): self
    {
        $this->payloadBag = $payloadBag;

        return $this;
    }

    /**
     * Dodaje lub nadpisuje payload w kontenerze PayloadBag
     * Funkcja jest ptorzebna, bo jeśli użyjemy bezpośrednio set na PayloadBag, to Doctrin nie zauważy zmiany i nie zapisze nowej postaci PayloadBag. Dlatego musimy zrobić kopię i ustawić nową insancje PayloadBag
     * @param AbstractPayload|null $payload
     * @return $this
     */
    #[Ignore]
    public function setPayload(?AbstractPayload $payload): self
    {
        $this->setPayloadBag($this->getPayloadBag()->set($payload));

        return $this;
    }

    #[Ignore]
    public function setProcessFlag(string $processFlag): void
    {
        if (!$this->checkProcessFlag($processFlag)) {
            $this->processFlags[] = $processFlag;
        }
    }

    #[Ignore]
    public function setProcessFlags(array $processFlags): self
    {
        foreach ($processFlags as $processFlag){
            if (!$this->checkProcessFlag($processFlag)) {
                $this->processFlags[] = $processFlag;
            }
        }

        return $this;
    }

    public function checkProcessFlag(string $processFlag): bool
    {
        $key = array_search($processFlag, $this->processFlags);
        return $key !== false;
    }

    protected function removeProcessFlag(string $processFlag): void
    {
        $key = array_search($processFlag, $this->processFlags);
        if ($key !== false) {
            unset($this->processFlags[$key]);
        }
    }
}
