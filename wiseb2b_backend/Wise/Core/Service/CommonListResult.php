<?php

declare(strict_types=1);


namespace Wise\Core\Service;

use Wise\Core\Dto\CommonServiceDTO;

/**
 * Klasa bazowa dla DTO zwracających listę obiektów pobranych z serwisu List
 */
class CommonListResult extends CommonServiceDTO
{
    /**
     * Ilość wszystkich rekordów (wykorzystywane w paginacji)
     * @var null|int
     */
    protected ?int $totalCount = 0;

    /**
     * @return int|null
     */
    public function getTotalCount(): ?int
    {
        return $this->totalCount;
    }

    /**
     * @param int|null $totalCount
     * @return self
     */
    public function setTotalCount(?int $totalCount): self
    {
        $this->totalCount = $totalCount;
        return $this;
    }
}
