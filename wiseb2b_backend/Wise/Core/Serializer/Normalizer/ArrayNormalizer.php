<?php

namespace Wise\Core\Serializer\Normalizer;

use DateTimeInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Wise\Core\Helper\Date\CommonDateTimeNormalizer;

class ArrayNormalizer implements NormalizerInterface
{
    public function __construct(
        protected NameConverterInterface $nameConverter,
        protected ObjectNormalizer $objectNormalizer,
    ) {}

    public function normalize($object, string $format = null, array $context = []): array
    {
        $result = [];

        foreach ($object as $key => $value) {
            $key = $this->nameConverter->normalize($key);

            if ($value instanceof DateTimeInterface) {
                $commonDateTimeNormalizer = new CommonDateTimeNormalizer();

                $value = $commonDateTimeNormalizer->normalize($value);
            } elseif (is_object($value)) {
                $value = $this->objectNormalizer->normalize($value);
            } elseif (is_array($value)) {
                $value = $this->normalize($value, $format, $context);
            }

            $result[$key] = $value;
        }

        return $result;
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return is_array($data);
    }
}
