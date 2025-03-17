<?php

declare(strict_types=1);


namespace Wise\Core\Serializer\Denormalizer;

use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class BoolPropertyDenormalizer implements ContextAwareDenormalizerInterface
{
    public function __construct(
        private readonly ObjectNormalizer $objectNormalizer
    ) {}

    /**
     * Denormalizujemy wartości stringowe 'true' i 'false' do wartości boolowskich, bez tego każda losowa wartość
     * zwróci nam true. Tutaj się zabezpieczamy na poprawne wartości.
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        foreach ($data as &$value) {
            if (in_array($value, ['true', 'on'], true)) {
                $value = true;
            } elseif (in_array($value, ['false', 'off'], true)) {
                $value = false;
            }
        }

        return $this->objectNormalizer->denormalize($data, $type, $format, $context);
    }

    /**
     * @param mixed $data
     * @param string $type
     * @param string|null $format
     * @param array $context
     * @inheritDoc
     */
    public function supportsDenormalization($data, string $type, string $format = null, array $context = []): bool
    {
        return is_array($data) && class_exists($type);
    }
}
