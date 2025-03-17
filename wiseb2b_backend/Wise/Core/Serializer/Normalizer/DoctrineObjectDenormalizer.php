<?php

declare(strict_types=1);


namespace Wise\Core\Serializer\Normalizer;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class DoctrineObjectDenormalizer extends ObjectNormalizer
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type instanceof Collection;
    }

    public function denormalize($data, $type, string $format = null, array|string|null $context = [])
    {
        $collection = $context['collection'];
        $collection->clear();

        foreach ($data as $item) {
            $collection->add($this->serializer->denormalize($item, $context['class'], $format, $context));
        }

        return $collection;
    }
}