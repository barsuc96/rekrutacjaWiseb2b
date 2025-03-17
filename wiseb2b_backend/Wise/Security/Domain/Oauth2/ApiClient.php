<?php

declare(strict_types=1);

namespace Wise\Security\Domain\Oauth2;

use DateInterval;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use League\Bundle\OAuth2ServerBundle\Model\AbstractClient;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Wise\Security\Repository\Doctrine\ApiClientRepository")
 * @ORM\Table(name="oauth2_client")
 */
class ApiClient extends AbstractClient
{
    public const DEFAULT_EXPIRATION_PERIOD = 'P1Y';

    /**
     * @ORM\Id
     * @ORM\Column(type="string", options={"length": 32})
     */
    public $identifier;

    /**
     * @var string|null
     */
    public $secret;

    /**
     * @var string|null
     * @Assert\Unique()
     */
    public $name;

    /**
     * @var Collection|null
     * @ORM\ManyToMany(targetEntity="ApiScope")
     * @ORM\JoinTable(name="api_client_scope",
     *      joinColumns={@ORM\JoinColumn(name="api_client_id", referencedColumnName="identifier")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="api_scope_id", referencedColumnName="id")}
     *      )
     */
    private iterable $apiScopes;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $expirationDate;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $name = '';
        $identifier = hash('md5', random_bytes(16));
        $secret = hash('sha512', random_bytes(32));

        $this->expirationDate = (new DateTime('NOW'))
            ->add(
                new DateInterval(self::DEFAULT_EXPIRATION_PERIOD)
            )
        ;

        parent::__construct($name, $identifier, $secret);
    }

    /**
     * @return iterable
     */
    public function getApiScopes(): iterable
    {
        return $this->apiScopes;
    }

    /**
     * @param iterable $apiScopes
     * @return ApiClient
     */
    public function setApiScopes(iterable $apiScopes): self
    {
        $this->apiScopes = $apiScopes;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getExpirationDate(): DateTime
    {
        return $this->expirationDate;
    }

    /**
     * @param DateTime $expirationDate
     * @return ApiClient
     */
    public function setExpirationDate(DateTime $expirationDate): self
    {
        $this->expirationDate = $expirationDate;
        return $this;
    }
}
