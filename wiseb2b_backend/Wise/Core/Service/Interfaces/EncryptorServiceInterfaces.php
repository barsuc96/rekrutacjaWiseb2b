<?php

namespace Wise\Core\Service\Interfaces;

interface EncryptorServiceInterfaces
{
    public function encrypt($data): string;
    public function decrypt($data): string;
}
