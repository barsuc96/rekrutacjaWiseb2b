<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Dto;

use JsonSerializable;
use OpenApi\Attributes as OA;

/**
 * TODO: [ws] dodać opis, nie wiem jak to działa
 */
abstract class CommonFormResponseDto implements JsonSerializable
{
    public function __construct(
        #[OA\Property(description: 'Status zdarzenia', example: 1)]
        protected int $status,

        #[OA\Property(description: 'Wiadomość do wyświetlenia użytkownikowi', example: 'Dane zostały zapisane')]
        protected string $message,
        #[OA\Property(description: 'Styl wiadomości do wyświetlenia użytkownikowi', example: 'success')]
        protected string $messageStyle,
        #[OA\Property(description: 'Czy wyświetlić użytkownikowi wiadomość', example: true)]
        protected bool $showMessage,

        #[OA\Property(description: 'Czy wyświetlić wiadomość w formie modal', example: false)]
        protected bool $showModal = false,
    ) {}

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function isShowMessage(): bool
    {
        return $this->showMessage;
    }

    public function isShowModal(): bool
    {
        return $this->showModal;
    }

    public function getMessageStyle(): string
    {
        return $this->messageStyle;
    }

}
