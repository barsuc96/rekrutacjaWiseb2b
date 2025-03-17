<?php

declare(strict_types=1);

namespace Wise\Core\Helper\Date;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class CommonDateTimeNormalizer extends DateTimeNormalizer
{
    /**
     * {@inheritdoc}
     *
     * @throws ExceptionInterface
     */
    public function denormalize($data, string $type, string $format = null, array $context = []
    ): DateTimeImmutable|DateTime|DateTimeInterface {
        $data = $data->date ?? $data;

        // Obejście dla CommonDataTransformer - wyjaśnienie w tamtym pliku
        if(is_array($data)){
            $data = $data['date'];
        }

        return parent::denormalize($data, $type, $format, $context);
    }
}
