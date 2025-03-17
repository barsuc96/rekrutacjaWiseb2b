<?php

namespace Wise\Core\Tests\Support\Trait;

use Symfony\Component\HttpFoundation\HeaderBag;
use Wise\Client\ApiAdmin\Dto\Clients\PutClientsDto;
use Wise\Client\ApiAdmin\Service\Clients\PutClientsService;
use Wise\Client\Domain\Client\Client;
use Wise\Client\Repository\Doctrine\ClientRepository;
use Wise\Core\ApiAdmin\Dto\RequestDataDto\PutRequestDataDto;
use Wise\Delivery\Repository\Doctrine\DeliveryMethodRepository;
use Wise\Order\ApiAdmin\Dto\Orders\PutOrdersDto;
use Wise\Order\ApiAdmin\Service\Orders\Interfaces\PutOrdersServiceInterface;
use Wise\Order\Domain\Order\Order;
use Wise\Order\Repository\Doctrine\OrderRepository;
use Wise\Payment\Repository\Doctrine\PaymentMethodRepository;
use Wise\Pricing\ApiAdmin\Dto\PriceLists\PutPriceListsDto;
use Wise\Pricing\ApiAdmin\Service\PriceLists\Interfaces\PutPriceListsServiceInterface;
use Wise\Pricing\Domain\PriceList\PriceList;
use Wise\Pricing\Repository\Doctrine\PriceListRepository;
use Wise\Pricing\Repository\Doctrine\ProductPriceRepository;
use Wise\Product\ApiAdmin\Dto\Attributes\PutAttributesDto;
use Wise\Product\ApiAdmin\Dto\Categories\PutCategoriesDto;
use Wise\Product\ApiAdmin\Dto\Products\PutProductsDto;
use Wise\Product\ApiAdmin\Dto\Units\PutUnitsDto;
use Wise\Product\ApiAdmin\Service\Attributes\Interfaces\PutAttributesServiceInterface;
use Wise\Product\ApiAdmin\Service\Categories\Interfaces\PutCategoriesServiceInterface;
use Wise\Product\ApiAdmin\Service\Products\Interfaces\PutProductsServiceInterface;
use Wise\Product\ApiAdmin\Service\Units\Interfaces\PutUnitsServiceInterface;
use Wise\Product\Domain\Attribute\Attribute;
use Wise\Product\Domain\Category\Category;
use Wise\Product\Domain\Unit\Unit;
use Wise\Product\Repository\Doctrine\AttributeRepository;
use Wise\Product\Repository\Doctrine\CategoryRepository;
use Wise\Product\Repository\Doctrine\ProductRepository;
use Wise\Product\Repository\Doctrine\ProductUnitRepository;
use Wise\Product\Repository\Doctrine\UnitRepository;
use Wise\Receiver\ApiAdmin\Dto\Receivers\PutReceiversDto;
use Wise\Receiver\ApiAdmin\Service\Receivers\Interfaces\PutReceiversServiceInterface;
use Wise\Receiver\Domain\Receiver\Receiver;
use Wise\Receiver\Repository\Doctrine\ReceiverRepository;
use Wise\Service\ApiAdmin\Dto\Services\PutServicesDto;
use Wise\Service\ApiAdmin\Service\Services\Interfaces\PutServicesServiceInterface;
use Wise\Service\Domain\Service\Service;
use Wise\Service\Repository\Doctrine\ServiceRepository;
use Wise\Stock\ApiAdmin\Dto\Warehouses\PutWarehousesDto;
use Wise\Stock\ApiAdmin\Service\Warehouses\Interfaces\PutWarehousesServiceInterface;
use Wise\Stock\Domain\Warehouse\Warehouse;
use Wise\Stock\Repository\Doctrine\WarehouseRepository;
use Wise\User\Repository\Doctrine\TraderRepository;

trait TesterExampleEntity
{
    /**
     * Tworzy nową encje Unit
     */
    public function createUnit(): Unit
    {
        $object = [
            'id' => $this->generateId('UNIT'),
            'shortcut' => $this->prepareTranslation(12),
            'name' => $this->prepareTranslation(),
            'is_active' => true
        ];
        $unitService = $this->grabService(PutUnitsServiceInterface::class);
        $responsePutService = $unitService->process([], json_encode([
            'objects' => [
                $object
            ]
        ]), PutUnitsDto::class);

        $responseContent = json_decode($responsePutService->getContent(), true);
        $resultObjectResponse = $this->seeObjectWithElementsInResponse($responseContent, $object['id'], [
            'id' => $object['id'],
            'message' => 'SUCCESS',
            'status' => 1
        ]);

        $unitRepository = $this->grabService(UnitRepository::class);
        return $unitRepository->findOneBy(['id' => $resultObjectResponse['internal_id']]);
    }

    /**
     * Tworzy nową encje PriceList
     */
    public function createPriceList(): PriceList
    {
        $object = [
            'id' => $this->generateId('PRICE_LIST'),
            'name' => $this->fake()->word(),
            'symbol' => $this->fake()->word(),
            'description' => $this->fake()->realText(30),
            'is_active' => true
        ];

        $priceListService = $this->grabService(PutPriceListsServiceInterface::class);
        $responsePutService = $priceListService->process([], json_encode([
            'objects' => [
                $object
            ]
        ]), PutPriceListsDto::class);

        $responseContent = json_decode($responsePutService->getContent(), true);
        $resultObjectResponse = $this->seeObjectWithElementsInResponse($responseContent, $object['id'], [
            'id' => $object['id'],
            'message' => 'SUCCESS',
            'status' => 1
        ]);

        $priceListRepository = $this->grabService(PriceListRepository::class);
        return $priceListRepository->findOneBy(['id' => $resultObjectResponse['internal_id']]);
    }

    /**
     * Tworzy nową encje Category
     */
    public function createCategory(bool $categoryWithParent = false): Category
    {
        $object = [
            'id' => $this->generateId('CATEGORY'),
            'symbol' => $this->generateId('59272aae-'),
            'type' => 'MENU',
            'is_active' => true,
            'name' => $this->prepareTranslation()
        ];

        if ($categoryWithParent) {
            $category = $this->createCategory();
            $object = array_merge($object, [
                'parent_category_internal_id' => $category->getId()
            ]);
        }

        $categoryService = $this->grabService(PutCategoriesServiceInterface::class);

        $responsePutService = $categoryService->process([], json_encode([
            'objects' => [
                $object
            ]
        ]), PutCategoriesDto::class);

        $responseContent = json_decode($responsePutService->getContent(), true);
        $resultObjectResponse = $this->seeObjectWithElementsInResponse($responseContent, $object['id'], [
            'id' => $object['id'],
            'message' => 'SUCCESS',
            'status' => 1
        ]);

        $categoryRepository = $this->grabService(CategoryRepository::class);
        return $categoryRepository->findOneBy(['id' => $resultObjectResponse['internal_id']]);
    }

    /**
     * Tworzy nową encje Attribute
     */
    public function createAttribute(): Attribute
    {
        $object = [
            'id' => $this->generateId('ATTRIBUTE'),
            'name' => $this->prepareTranslation(),
            'is_active' => true,
            'is_searchable' => true,
            'type' => 'SIZE_TABLE'
        ];

        $attributeService = $this->grabService(PutAttributesServiceInterface::class);
        $responsePutService = $attributeService->process([], json_encode([
            'objects' => [
                $object
            ]
        ]), PutAttributesDto::class);

        $responseContent = json_decode($responsePutService->getContent(), true);
        $resultObjectResponse = $this->seeObjectWithElementsInResponse($responseContent, $object['id'], [
            'id' => $object['id'],
            'message' => 'SUCCESS',
            'status' => 1
        ]);

        $attributeRepository = $this->grabService(AttributeRepository::class);
        return $attributeRepository->findOneBy(['id' => $resultObjectResponse['internal_id']]);
    }

    /**
     * Tworzy nową encje Warehouse
     */
    public function createWarehouse(): Warehouse
    {
        $object = [
            'id' => $this->generateId('WAREHOUSE'),
            'symbol' => $this->fake()->sentence(1),
            'name' => $this->prepareTranslation(),
            'description' => $this->prepareTranslation(),
            'is_active' => true,
        ];

        $warehouseService = $this->grabService(PutWarehousesServiceInterface::class);
        $responsePutService = $warehouseService->process([], json_encode([
            'objects' => [
                $object
            ]
        ]), PutWarehousesDto::class);

        $responseContent = json_decode($responsePutService->getContent(), true);
        $resultObjectResponse = $this->seeObjectWithElementsInResponse($responseContent, $object['id'], [
            'id' => $object['id'],
            'message' => 'SUCCESS',
            'status' => 1
        ]);

        $warehouseRepository = $this->grabService(WarehouseRepository::class);
        return $warehouseRepository->findOneBy(['id' => $resultObjectResponse['internal_id']]);
    }

    public function createProduct(
        bool $withUnit = false,
        bool $withPriceList = false,
        bool $withCategory = false,
        bool $withImages = false,
        bool $withAttachments = false,
        bool $withAttributes = false,
        bool $withStock = false,
        bool $withVariant = false,
        bool $getProductUnit = false,
        bool $getProductPrice = false,
        string $parentId = null,
        bool $variant = false,
        bool $categoryWithParent = false
    ): array {
        $productRepository = $this->grabService(ProductRepository::class);
        $result = [];
        $object = [
            'id' => $this->generateId('PRODUCT'),
            'parent_id' => ($parentId === null) ? '11-100-HP0110-000' : $parentId,
            'symbol' => $this->fake()->uuid(),
            'ean' => $this->generateEAN13(),
            'type' => ($variant) ? 'VARIANT' : 'PRODUCT',
            'moq' => 1,
            'is_active_on_web' => true,
            'is_active' => true,
            'name' => $this->prepareTranslation(),
            'description' => $this->prepareTranslation(),
            'tech_description' => $this->prepareTranslation(),
        ];

        /**
         * Dodaj produkt wraz z Unit
         */
        if ($withUnit) {
            $unitEntity = $this->createUnit();
            $unit = [
                'units' => [
                    [
                        'id' => 'UNIT_TEST',
                        'unit_id' => $unitEntity->getIdExternal(),
                        'main' => true,
                        'is_active' => true,
                        'weight' => 2.45,
                        'converter' => 1
                    ]
                ],
            ];

            // Dodanie zawartości tablicy $unit do tablicy $object
            $object = array_merge($object, $unit);
            $result['usedEntity']['unit'] = $unitEntity;
        }

        /**
         * Dodaj produkt wraz z PriceList
         */
        if ($withPriceList) {
            $priceListEntity = $this->createPriceList();
            $unitEntity = $result['usedEntity']['unit'] ?? $this->createUnit();
            $priceList = [
                'prices' => [
                    [
                        'id' => 'PRICE_TEST',
                        'price_list_internal_id' => $priceListEntity->getId(),
                        'unit_id' => $unitEntity->getIdExternal(),
                        'price_net' => 12.34,
                        'price_gross' => 15.18,
                        'tax_percent' => 23,
                        'currency' => 'PLN',
                        'priority' => 1,
                        'is_active' => true
                    ],
                    [
                        'id' => 'PRICE_TEST1',
                        'price_list_internal_id' => $priceListEntity->getId(),
                        'unit_id' => $unitEntity->getIdExternal(),
                        'price_net' => 12.34,
                        'price_gross' => 15.18,
                        'tax_percent' => 23,
                        'currency' => 'PLN',
                        'priority' => 0,
                        'is_active' => true
                    ],
                    [
                        'id' => 'PRICE_TEST2',
                        'price_list_internal_id' => $priceListEntity->getId(),
                        'unit_id' => $unitEntity->getIdExternal(),
                        'price_net' => 43.34,
                        'price_gross' => 56.18,
                        'tax_percent' => 23,
                        'currency' => 'PLN',
                        'priority' => 2,
                        'is_active' => false
                    ]
                ]
            ];

            // Dodanie zawartości tablicy $priceList do tablicy $object
            $object = array_merge($object, $priceList);
            $result['usedEntity']['priceList'] = $priceListEntity;
        }

        /**
         * Dodaj produkt wraz z Kategoria
         */
        if ($withCategory) {
            $category = $this->createCategory($categoryWithParent);
            $categories = [
                'categories' => [
                    [
                        'category_id' => $category->getIdExternal()
                    ]
                ]
            ];

            // Dodanie zawartości tablicy $categories do tablicy $object
            $object = array_merge($object, $categories);
            $result['usedEntity']['category'] = $category;
        }

        /**
         * Dodaj produkt wraz ze zdjeciami
         */
        if ($withImages) {
            $images = [
                'images' => [
                    [
                        'id' => $this->generateId('IMAGES'),
                        'position' => 1,
                        'extension' => 'png',
                        'type' => 'ORIGINAL',
                        'main' => 1,
                        'is_active' => true,
                        'base64' => 'iVBORw0KGgoAAAANSUhEUgAAAAIAAAACAQMAAABIeJ9nAAAABlBMVEUAAAADAwMVBQXvAAAADElEQVQI12NoYGAAAAGEAIEo3XrXAAAAAElFTkSuQmCC'
                    ],
                    [
                        'id' => $this->generateId('IMAGES'),
                        'position' => 2,
                        'extension' => 'png',
                        'type' => 'ORIGINAL',
                        'main' => 0,
                        'is_active' => true,
                        'base64' => 'iVBORw0KGgoAAAANSUhEUgAAAAIAAAACAQMAAABIeJ9nAAAABlBMVEUAAAADAwMVBQXvAAAADElEQVQI12NoYGAAAAGEAIEo3XrXAAAAAElFTkSuQmCC'
                    ]
                ]
            ];

            // Dodanie zawartości tablicy $images do tablicy $object
            $object = array_merge($object, $images);
        }

        /**
         * Dodaj produkt wraz z zalacznikami
         */
        if ($withAttachments) {
            $attachments = [
                'images' => [
                    [
                        'id' => $this->generateId('ATTACHMENT'),
                        'position' => 1,
                        'extension' => 'png',
                        'type' => 'ORIGINAL',
                        'main' => 1,
                        'is_active' => true,
                        'base64' => 'iVBORw0KGgoAAAANSUhEUgAAAAIAAAACAQMAAABIeJ9nAAAABlBMVEUAAAADAwMVBQXvAAAADElEQVQI12NoYGAAAAGEAIEo3XrXAAAAAElFTkSuQmCC',
                        'name' => 'ATTACHMENT_1',
                        'description' => 'ATTACHMENT_DESCRIPTION_1'
                    ],
                    [
                        'id' => $this->generateId('ATTACHMENT'),
                        'position' => 2,
                        'type' => 'ORIGINAL',
                        'main' => 0,
                        'is_active' => true,
                        'base64' => 'iVBORw0KGgoAAAANSUhEUgAAAAIAAAACAQMAAABIeJ9nAAAABlBMVEUAAAADAwMVBQXvAAAADElEQVQI12NoYGAAAAGEAIEo3XrXAAAAAElFTkSuQmCC',
                        'name' => 'ATTACHMENT_2',
                        'description' => 'ATTACHMENT_DESCRIPTION_2'
                    ]
                ]
            ];

            // Dodanie zawartości tablicy $attachments do tablicy $object
            $object = array_merge($object, $attachments);
        }

        /**
         * Dodaj produkt wraz z attrybutem
         */
        if ($withAttributes) {
            $attribute = $this->createAttribute();
            $attributes = [
                'attributes' => [
                    [
                        'attribute_id' => $attribute->getIdExternal(),
                        'value' => [
                            [
                                'language' => 'pl',
                                'translation' => 'ExampleValue',
                                'number' => 2.32,
                                'boolean' => true,
                                'blob_text' => 'example'
                            ],
                            [
                                'language' => 'pl',
                                'translation' => 'ExampleValue2',
                                'number' => 5.67,
                                'boolean' => true,
                                'blob_text' => 'example2'
                            ]
                        ]
                    ],
                ]
            ];

            // Dodanie zawartości tablicy $attributes do tablicy $object
            $object = array_merge($object, $attributes);
            $result['usedEntity']['attribute'] = $attribute;
        }

        /**
         * Dodanie produktu
         */
        $productService = $this->grabService(PutProductsServiceInterface::class);
        $responsePutService = $productService->process([], json_encode([
            'objects' => [
                $object
            ]
        ]), PutProductsDto::class);


        /**
         * Dodaj produkt wraz ze Stock
         */
        if ($withStock) {

            // Zwrócenie Product
            $responseContent = json_decode($responsePutService->getContent(), true);
            $resultObjectResponse = $this->seeObjectWithElementsInResponse($responseContent, $object['id'], [
                'id' => $object['id'],
                'message' => 'SUCCESS',
                'status' => 1
            ]);


            $productEntity = $productRepository->findOneBy(['id' => $resultObjectResponse['internal_id']]);
            $warehouseFirst = $this->createWarehouse();
            $warehouseSecond = $this->createWarehouse();
            $objectProduct = [
                'id' => $productEntity->getIdExternal()
            ];


            // Pobranie Unit, jeśli go nie dodano wcześniej to teraz go dodaj aby utworzyło ProductUnit
            $unitEntity = $result['usedEntity']['unit'] ?? $this->createUnit();
            if (!isset($result['usedEntity']['unit'])) {
                $unit = [
                    'units' => [
                        [
                            'id' => 'UNIT_TEST',
                            'unit_id' => $unitEntity->getIdExternal(),
                            'main' => true,
                            'is_active' => true,
                            'weight' => 2.45,
                            'converter' => 1
                        ]
                    ],
                ];

                // Dodanie zawartości tablicy $unit do tablicy $objectProduct
                $objectProduct = array_merge($objectProduct, $unit);
                $result['usedEntity']['unit'] = $unitEntity;
            }

            // Dodanie ProductStock
            $objectStocks = [
                'stocks' => [
                    [
                        'id' => $this->generateId('STOCK'),
                        'unit_id' => $unitEntity->getIdExternal(),
                        'warehouse_id' => $warehouseFirst->getIdExternal(),
                        'quantity' => 1,
                        'is_active' => true
                    ],
                    [
                        'id' => $this->generateId('STOCK'),
                        'unit_id' => $unitEntity->getIdExternal(),
                        'warehouse_id' => $warehouseSecond->getIdExternal(),
                        'quantity' => 65,
                        'is_active' => true
                    ]
                ]
            ];
            $objectProduct = array_merge($objectProduct, $objectStocks);

            //Modyfikacja Produktu
            $productService = $this->grabService(PutProductsServiceInterface::class);
            $responsePutService = $productService->process([], json_encode([
                'objects' => [
                    $objectProduct
                ]
            ]), PutProductsDto::class);

            $result['usedEntity']['warehouse'][] = $warehouseFirst;
            $result['usedEntity']['warehouse'][] = $warehouseSecond;

        }


        // Zwracamy ostatecznie Produkt

        $responseContent = json_decode($responsePutService->getContent(), true);
        $resultObjectResponse = $this->seeObjectWithElementsInResponse($responseContent, $object['id'], [
            'id' => $object['id'],
            'message' => 'SUCCESS',
            'status' => 1
        ]);

        $product = $productRepository->findOneBy(['id' => $resultObjectResponse['internal_id']]);
        $result['product'] = $product;

        if ($getProductUnit || $getProductPrice) {
            $productUnitRepository = $this->grabService(ProductUnitRepository::class);
            $result['usedEntity']['productUnit'] = $productUnitRepository->findOneBy([
                'productId' => $product->getId(),
                'unitId' => $unitEntity->getId()
            ]);
        }

        if ($getProductPrice) {
            $productPriceRepository = $this->grabService(ProductPriceRepository::class);
            $result['usedEntity']['productPrice'] = $productPriceRepository->findBy(['productUnitId' => $result['usedEntity']['productUnit']->getId()]);
        }

        /**
         * Dodaj warianty
         */
        if ($withVariant) {
            $result['product-variants'][] = $this->createProduct(
                withAttributes: true,
                parentId: $result['product']->getIdExternal(),
                variant: true
            );
            $result['product-variants'][] = $this->createProduct(
                withAttributes: true,
                parentId: $result['product']->getIdExternal(),
                variant: true
            );
        }

        return $result;
    }

    /**
     * Tworzy nową encje Service
     */
    public function createService(): Service
    {
        $object = [
            'id' => $this->generateId('Service'),
            'type' => 'TYPE' . rand(1, 999),
            'description' => $this->prepareTranslation(30),
            'name' => $this->prepareTranslation(14),
            'is_active' => true
        ];
        $serviceService = $this->grabService(PutServicesServiceInterface::class);
        $responsePutService = $serviceService->process([], json_encode([
            'objects' => [
                $object
            ]
        ]), PutServicesDto::class);

        $responseContent = json_decode($responsePutService->getContent(), true);
        $resultObjectResponse = $this->seeObjectWithElementsInResponse($responseContent, $object['id'], [
            'id' => $object['id'],
            'message' => 'SUCCESS',
            'status' => 1
        ]);

        $serviceRepository = $this->grabService(ServiceRepository::class);
        return $serviceRepository->findOneBy(['id' => $resultObjectResponse['internal_id']]);
    }

    /**
     * Tworzy nową encje Client
     */
    public function createClient(): Client
    {
        $priceListRepository = $this->grabService(PriceListRepository::class);
        $priceLists = $priceListRepository->findAll();

        $traderRepository = $this->grabService(TraderRepository::class);
        $traders = $traderRepository->findAll();

        $paymentMethodRepository = $this->grabService(PaymentMethodRepository::class);
        $paymentMethods = $paymentMethodRepository->findAll();

        $deliveryMethodRepository = $this->grabService(DeliveryMethodRepository::class);
        $deliveryMethods = $deliveryMethodRepository->findAll();

        $object = [
            'id' => $this->generateId('CLIENT'),
            'name' => $this->fake()->name(),
            'register_address' => [
                'name' => 'Agnieszka Wegorz',
                'street' => 'Kwiatowa',
                'house_number' => '34',
                'apartment_number' => '18',
                'city' => 'Testowo',
                'postal_code' => '00-000',
                'country_code' => 'pl',
                'state' => $this->fake()->sentence(2),
            ],
            'email' => $this->fake()->email(),
            'phone' => '111222333',
            'is_active' => true,
            'tax_number' => 'PL' . rand(111111111, 999999999),
            'client_parent_id' => null,
            'default_payment_method_id' => $paymentMethods[0]->getIdExternal(),
            'default_delivery_method_id' => $deliveryMethods[0]->getIdExternal(),
            'flags' => 'FLAG',
            'return_bank_account' => [
                'owner_name' => $this->fake()->name(),
                'account' => '57106001938797265026589024',
                'bank_country_id' => 'PL',
                'bank_name' => 'mBANK',
                'bank_address' => 'ul. Sokolska 34, 40-086 Katowice',
            ],
            'default_currency' => 'PLN',
            'type' => 'CANDIDATE',
            'dropshipping_cost' => 4.54,
            'order_return_cost' => 40.34,
            'free_delivery_limit' => 156.42,
            'discount' => 6.43,
            'first_name' => 'Jan',
            'last_name' => 'Kowalski',
            'trader_id' => $traders[0]->getIdExternal(),
            'pricelist_id' => $priceLists[0]->getIdExternal(),
            'payments' => [
                [
                    'payment_method_id' => $paymentMethods[0]->getIdExternal()
                ],
                [
                    'payment_method_id' => $paymentMethods[1]->getIdExternal()
                ]
            ],
            'deliveries' => [
                [
                    'delivery_method_id' => $deliveryMethods[0]->getIdExternal()
                ],
                [
                    'delivery_method_id' => $deliveryMethods[1]->getIdExternal()
                ]
            ]
        ];

        $putClientService = $this->grabService(PutClientsService::class);
        $requestContent = json_encode([
            'objects' => [
                $object
            ]
        ]);

        $requestDto = new PutRequestDataDto();
        $requestDto
            ->setClearRequestContent($requestContent)
            ->setRequestContent($requestContent)
            ->setIsPatch(false)
            ->setRequestDtoClass(PutClientsDto::class)
            ->setHeaders(new HeaderBag());

        $putClientService->process($requestDto);

        $clientRepository = $this->grabService(ClientRepository::class);
        return $clientRepository->findOneBy(['idExternal' => $object['id']]);
    }

    /**
     * Tworzy nową encje Order
     */
    public function creatOrder(): Order
    {
        $client = $this->createClient();
        $paymentMethodRepository = $this->grabService(PaymentMethodRepository::class);
        $paymentMethods = $paymentMethodRepository->findAll();

        $deliveryMethodRepository = $this->grabService(DeliveryMethodRepository::class);
        $deliveryMethods = $deliveryMethodRepository->findAll();

        $object = [
            'id' => $this->generateId('ORDER'),
            'payment_method_internal_id' => $paymentMethods[0]->getId(),
            'delivery_method_internal_id' => $deliveryMethods[0]->getId(),
            'payment_cost' => 12.99,
            'delivery_cost' => 156.43,
            'additional_cost' => 122.87,
            'position_counter' => 3,
            'currency' => 'PLN',
            'client_internal_id' => $client->getId(),
            'client_email' => 'example@example.com',
            'client_phone' => '111222333',
            'receiver_id' => 1,
            'receiver_name' => 'Jan Kowalski',
            'receiver_address' => [
                'name' => 'Podstawowy - Domyslny',
                'street' => 'Powstańców Warszawkich',
            ],
            'value_net' => 54936.54,
            'value_gross' => 7153.32,
            'dropshipping' => false,
            'dropshipping_cost' => 105.54,
        ];


        $putPutService = $this->grabService(PutOrdersServiceInterface::class);
        $putPutService->process([], json_encode([
            'objects' => [
                $object
            ]
        ]), PutOrdersDto::class);

        $orderRepository = $this->grabService(OrderRepository::class);
        return $orderRepository->findOneBy(['idExternal' => $object['id']]);
    }


    /**
     * Tworzy nową encje Receiver
     */
    public function createReceiver(): Receiver
    {
        $client = $this->createClient();

        $object = [
            'id' => $this->generateId('RECEIVER'),
            'client_id' => $client->getIdExternal(),
            'name' => 'Jan Kowalski',
            'delivery_address' => [
                'name' => 'Jan Kowalski S.A',
                'street' => 'ul. Kowalska 1',
                'city' => 'Warszawa',
                'postal_code' => '12-345',
                'country_code' => 'PL',
                'house_number' => '55',
                'apartment_number' => '1',
                'state' => 'Lodzkie'
            ],
            'email' => 'kowalski@email.com',
            'phone' => '123456789',
            'is_default' => 'true',
            'first_name' => 'Agnieszka',
            'last_name' => 'Nowak'
        ];


        $putPutService = $this->grabService(PutReceiversServiceInterface::class);
        $putPutService->process([], json_encode([
            'objects' => [
                $object
            ]
        ]), PutReceiversDto::class);

        $receiverRepository = $this->grabService(ReceiverRepository::class);
        return $receiverRepository->findOneBy(['idExternal' => $object['id']]);
    }

}
