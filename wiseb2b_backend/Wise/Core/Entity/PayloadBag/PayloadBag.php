<?php

declare(strict_types=1);

namespace Wise\Core\Entity\PayloadBag;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Kontener przechowujący dodatkowe struktury zdefiniowane w kodzie bez potrzeby przeciążania lub dodawania nowych pól w encji
 * Wykorzystywana m.in. w sytuacjach, kiedy potrzebujemy przechować informacje w bazie danych bez definiowania dodatkowych pól w bazie danych
 */
class PayloadBag
{
    protected array $payloads = [];

    /**
     * Umieszcza strukturę w kontenerze payloads
     * @param AbstractPayload $structurePayload
     * @return $this
     */
    public function set(AbstractPayload $structurePayload): PayloadBag
    {
        $this->payloads[$structurePayload::class] = $structurePayload;

        $newPayloadBag = new PayloadBag();
        $newPayloadBag->setPayloads($this->payloads);

        return $newPayloadBag;
    }

    /**
     * Zwraca obiekt z kontenera payload.
     * Jeśli nie znajdzie, zwraca wartość null.
     * @param string $structureClassName
     * @param bool $createObjectWhenNotExists
     * @return AbstractPayload|null
     */
    public function get(string $structureClassName, bool $createObjectWhenNotExists = false): ?AbstractPayload
    {
        if(isset($this->payloads[$structureClassName])){
            return $this->payloads[$structureClassName];
        }

        if($createObjectWhenNotExists){
            return new $structureClassName();
        }

        return null;
    }

    /**
     * Zwraca informacje czy tablica payloads jest pusta
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->payloads);
    }

    /**
     * Zwraca tablice payloads
     * @return array
     */
    public function getPayloadsList(): array
    {
        return $this->payloads;
    }

    /**
     * Ustawia tablice payloads
     * @param array $payloads
     * @return void
     */
    public function setPayloads(array $payloads): void
    {
        $this->payloads = $payloads;
    }

    /**
     * Tworzy obiekt PayloadBag z JSONa
     * @param string|null $payloadBagJson
     * @return self|null
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public static function fromJson(?string $payloadBagJson): ?self
    {
        if($payloadBagJson === null) {
            return null;
        }


        try {

            $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

            $deserializedPayloads = $serializer->decode($payloadBagJson, 'json');
            $payloadBag = new self();

            foreach ($deserializedPayloads as $payloadData) {
                $className = $payloadData['class'];
                $data = $payloadData['data'];

                $payload = $serializer->denormalize($data, $className);
                $payloadBag->set($payload);
            }

        }catch (\Exception $e){
            return null;
        }

        return $payloadBag;
    }

    /**
     * Zwraca JSONa z obiektu PayloadBag
     * @return string|null
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function toJson(): ?string
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $serializedPayloads = [];

        if(!empty($this->getPayloadsList())) {
            foreach ($this->getPayloadsList() as $className => $payload) {
                $serializedPayloads[] = [
                    'class' => $className,
                    'data' => $serializer->normalize($payload)
                ];
            }

            return $serializer->encode($serializedPayloads, 'json');
        }

        return null;
    }
}
