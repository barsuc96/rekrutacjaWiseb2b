<?php

declare(strict_types=1);

namespace Wise\Core\DataTransformer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Wise\Core\Entity\AbstractEntity;

/**
 * Normalizer używany aby zmienić pole name z translation (lista), na pojedyncze pole string
 */
class TranslationFieldNameNormalizer implements DenormalizerInterface
{
    public function denormalize($data, string $type, string $format = null, array $context = []): string
    {
        // zwróć wartość pola obiektu
        return '';
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        if (is_subclass_of($data, AbstractEntity::class)) {
            return true;
        }

        return false;
    }
}
