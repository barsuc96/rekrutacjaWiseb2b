<?php

declare(strict_types=1);

namespace Wise\Core\Resources\doctrine\types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;
use Wise\Core\Entity\PayloadBag\PayloadBag;

class PayloadBagType extends JsonType
{
    const PAYLOAD_BAG = 'payload_bag';

    public function getName(): string
    {
        return self::PAYLOAD_BAG;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        return PayloadBag::fromJson($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        /** @var ?PayloadBag $value */
        if($value === null){
            return null;
        }

        return $value->toJson();
    }

    public function canRequireSQLConversion(): bool
    {
        return true;
    }
}
