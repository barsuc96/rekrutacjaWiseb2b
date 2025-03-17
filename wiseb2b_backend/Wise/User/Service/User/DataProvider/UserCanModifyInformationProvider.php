<?php

namespace Wise\User\Service\User\DataProvider;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Wise\Core\DataProvider\AbstractAdditionalFieldProvider;
use Wise\User\Domain\User\CanModifyOtherUserServiceInterface;

#[AutoconfigureTag(name: 'details_provider.user')]
class UserCanModifyInformationProvider extends AbstractAdditionalFieldProvider implements UserDetailsProviderInterface
{
    public const FIELD = 'canModifyUser';

    public function __construct(
        private readonly CanModifyOtherUserServiceInterface $canModifyOtherUserService,
    ) {}

    /**
     * Pobieramy dane dla danego użytkownika,
     * używamy do tego serwisu aplikacji ListOfferService
     *
     * @throws \Exception
     */
    public function getFieldValue($userId, ?array $cacheData = null): mixed
    {
        try{
            $this->canModifyOtherUserService->check($userId, true);
        }catch (\Exception $e) {
            return false;
        }

        return true;
    }
}
