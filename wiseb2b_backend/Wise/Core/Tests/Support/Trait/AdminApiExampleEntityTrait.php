<?php

namespace Wise\Core\Tests\Support\Trait;

use Codeception\Util\HttpCode;

trait AdminApiExampleEntityTrait
{

    /**
     * Pobiera gotowe dane dotyczace Product
     */
    public function grabNewProduct(int $count = 1){
        $objects = [];

        for($i = 1; $i <= $count; $i++){
            $productId = 'PRODUCT' . rand(1,99999);
            $objects[] = [
                'id' => $productId,
                'symbol' => '59272aae-eeaa-4eef-938c-6147487f3cf2',
                'parent_internal_id' => 1,
                'ean' => '1c64ccfd-d3e6-440c-a619-9fe6230da514',
                'type' => 'PRODUCT',
                'is_active_on_web' => true,
                'is_active' => true,
                'name' => $this->prepareTranslation(),
                'description' => $this->prepareTranslation(),
                'tech_description' => $this->prepareTranslation()
            ];
        }

        $payload = [
            'objects' => $objects
        ];

        $responsePut = $this->sendPutAsJson('/products', $payload);
        $this->seeResponseIsJson();
        $this->seeResponseCodeIs(HttpCode::OK);
        $this->seeObjectWithElementsInResponse($responsePut, $productId, [
            'id' => $productId,
            'message' => 'SUCCESS'
        ]);


        $responseGet = $this->sendGetAsJson('/products', ['id' => $productId]);
        $this->seeResponseIsJson();
        $this->seeResponseCodeIs(HttpCode::OK);
        $this->seeObjectWithElementsInResponse($responseGet, $productId, [
            'id_external' => $productId,
        ], 'id_external');

        return [
            'object' => $responseGet['objects'][0] ?? [],
            'payload' => $payload,
            'response' => [
                'put' => $responsePut,
                'get' => $responseGet
            ]
        ];
    }

    /**
     * Pobiera gotowe dane dotyczace Unit
     */
    public function grabNewUnit(int $count = 1){
        $objects = [];

        for($i = 1; $i <= $count; $i++){
            $unitId = 'UNIT' . rand(1,99999);
            $objects[] = [
                'id' => $unitId,
                'shortcut' => $this->prepareTranslation(12),
                'name' => $this->prepareTranslation(),
                'is_active' => true
            ];
        }

        $payload = [
            'objects' => $objects
        ];

        $responsePut = $this->sendPutAsJson('/units', $payload);
        $this->seeResponseIsJson();
        $this->seeResponseCodeIs(HttpCode::OK);
        $this->seeObjectWithElementsInResponse($responsePut, $unitId, [
            'id' => $unitId,
            'message' => 'SUCCESS'
        ]);

        $responseGet = $this->sendGetAsJson('/units', ['id' => $unitId]);
        $this->seeResponseIsJson();
        $this->seeResponseCodeIs(HttpCode::OK);
        $this->seeObjectWithElementsInResponse($responseGet, $unitId, [
            'id' => $unitId,
        ]);

        return [
            'object' => $responseGet['objects'][0] ?? [],
            'payload' => $payload,
            'response' => [
                'put' => $responsePut,
                'get' => $responseGet
            ]
        ];
    }


    /**
     * Pobiera gotowe dane dotyczace PriceList
     */
    public function grabNewPriceList(int $count = 1){
        $objects = [];

        for($i = 1; $i <= $count; $i++){
            $priceListId = 'PRICE_LIST' . rand(1,99999);
            $objects[] = [
                'id' => $priceListId,
                'name' => $this->fake()->word(),
                'symbol' => $this->fake()->word(),
                'description' => $this->fake()->realText(30),
                'is_active' => true
            ];
        }

        $payload = [
            'objects' => $objects
        ];

        $responsePut = $this->sendPutAsJson('/price-lists', $payload);
        $this->seeResponseIsJson();
        $this->seeResponseCodeIs(HttpCode::OK);
        $this->seeObjectWithElementsInResponse($responsePut, $priceListId, [
            'id' => $priceListId,
            'message' => 'SUCCESS'
        ]);


        $responseGet = $this->sendGetAsJson('/price-lists', ['id' => $priceListId]);
        $this->seeResponseIsJson();
        $this->seeResponseCodeIs(HttpCode::OK);
        $this->seeObjectWithElementsInResponse($responseGet, $priceListId, [
            'id_external' => $priceListId,
        ], 'id_external');

        return [
            'object' => $responseGet['objects'][0] ?? [],
            'payload' => $payload,
            'response' => [
                'put' => $responsePut,
                'get' => $responseGet
            ]
        ];
    }

    /**
     * Pobiera gotowe dane dotyczace ProductPrice
     */
    public function grabNewProductPrice(int $count = 1){
        $objects = [];

        for($i = 1; $i <= $count; $i++){
            $productUnit = $this->grabNewProductUnit();

            $productId = $productUnit['product']['internal_id'];
            $unitIdExternal = $productUnit['unit']['external_id'];
            $priceList = $this->grabNewPriceList()['object'];

            $productPriceId = 'PRODUCT_PRICE' . rand(1,99999);
            $objects[] = [
                'id' => $productPriceId,
                'product_internal_id' => $productId,
                'unit_id' => $unitIdExternal,
                'price_list_internal_id' => $priceList['id'],
                'price_net' => 564.25,
                'price_gross' => 694.03,
                'tax_percent' => 23,
                'currency' => 'PLN',
                'priority' => 1,
                'is_active' => true
            ];
        }

        $payload = [
            'objects' => $objects
        ];

        $responsePut = $this->sendPutAsJson('/product-prices', $payload);
        $this->seeResponseIsJson();
        $this->seeResponseCodeIs(HttpCode::OK);
        $this->seeObjectWithElementsInResponse($responsePut, $productPriceId, [
            'id' => $productPriceId,
            'message' => 'SUCCESS',
        ]);

        $responseGet = $this->sendGetAsJson('/product-prices', ['priceListId' => $priceList['id_external']]);
        $this->seeResponseIsJson();
        $this->seeResponseCodeIs(HttpCode::OK);
        $this->seeObjectWithElementsInResponse($responseGet, $productPriceId, [
            'id' => $productPriceId,
        ]);

        $responseGet = $this->sendGetAsJson('/product-prices', ['productId' => $productUnit['product']['external_id']]);
        $this->seeResponseIsJson();
        $this->seeResponseCodeIs(HttpCode::OK);
        $this->seeObjectWithElementsInResponse($responseGet, $productPriceId, [
            'id' => $productPriceId,
        ]);

        $responseGet = $this->sendGetAsJson('/product-prices', ['unitId' => $unitIdExternal]);
        $this->seeResponseIsJson();
        $this->seeResponseCodeIs(HttpCode::OK);
        $this->seeObjectWithElementsInResponse($responseGet, $productPriceId, [
            'id' => $productPriceId,
        ]);

        $responseGet = $this->sendGetAsJson('/product-prices', ['priceListId' => $priceList['id_external'], 'productId' => $productUnit['product']['external_id'], 'unitId' => $unitIdExternal]);
        $this->seeResponseIsJson();
        $this->seeResponseCodeIs(HttpCode::OK);
        $this->seeObjectWithElementsInResponse($responseGet, $productPriceId, [
            'id' => $productPriceId,
        ]);

        return [
            'object' => $responseGet['objects'][0] ?? [],
            'payload' => $payload,
            'response' => [
                'put' => $responsePut,
                'get' => $responseGet
            ]
        ];
    }

    /**
     * Pobiera gotowe dane dotyczace ProductUnit
     */
    public function grabNewProductUnit(int $count = 1){
        $objects = [];

        for($i = 1; $i <= $count; $i++){
            $productUnitId = 'PRODUCT_UNIT' . rand(1,99999);
            $product = $this->grabNewProduct()['object'];
            $unit = $this->grabNewUnit()['object'];
            $objects[] = [
                'id' => $productUnitId,
                'unit_id' => $unit['id'],
                'product_internal_id' => $product['id'],
                'weight' => 43.54,
                'main' => true,
                'is_active' => true
            ];
        }

        $payload = [
            'objects' => $objects
        ];

        $responsePut = $this->sendPutAsJson('/product-units', $payload);
        $this->seeResponseIsJson();
        $this->seeResponseCodeIs(HttpCode::OK);
        $this->seeResponseContainsJson([
            'message' => 'SUCCESS'
        ]);
        $this->seeObjectWithElementsInResponse($responsePut, $unit['id'], [
            'unit_id' => $unit['id'],
        ], 'unit_id');

        $element = $this->grabObjectWithId($responsePut, $unit['id'], 'unit_id');
        $productUnitId = $element['internal_id'];

        $responseGet = $this->sendGetAsJson('/product-units', ['id' => $productUnitId]);

        $this->seeResponseIsJson();
        $this->seeResponseCodeIs(HttpCode::OK);
        $this->seeObjectWithElementsInResponse($responseGet, $productUnitId, [
            'internal_id' => $productUnitId,
        ], 'internal_id');

        return [
            'object' => $responseGet['objects'][0] ?? [],
            'payload' => $payload,
            'response' => [
                'put' => $responsePut,
                'get' => $responseGet
            ],
            'product' => [
                'internal_id' => $product['id'],
                'external_id' => $product['id_external']
            ],
            'unit' => [
                'internal_id' => $unit['internal_id'],
                'external_id' => $unit['id']
            ],
            'id' => $productUnitId
        ];
    }

    /**
     * Pobiera gotowe dane dotyczace Attribute
     */
    public function grabNewAttribute(int $count = 1): array
    {
        $objects = [];

        for($i = 1; $i <= $count; $i++){
            $attributeId = 'ATTRIBUTE' . rand(1,99999);
            $objects[] = [
                'id' => $attributeId,
                'name' => $this->prepareTranslation(),
                'is_active' => true,
                'is_searchable' => true,
                'type' => 'SIZE_TABLE'
            ];
        }

        $payload = [
            'objects' => $objects
        ];

        $responsePut = $this->sendPutAsJson('/attributes', $payload);
        $this->seeResponseIsJson();
        $this->seeResponseCodeIs(HttpCode::OK);
        $this->seeObjectWithElementsInResponse($responsePut, $attributeId, [
            'id' => $attributeId,
            'message' => 'SUCCESS'
        ]);

        $responseGet = $this->sendGetAsJson('/attributes', ['id' => $attributeId]);
        $this->seeResponseIsJson();
        $this->seeResponseCodeIs(HttpCode::OK);
        $this->seeObjectWithElementsInResponse($responseGet, $attributeId, [
            'id' => $attributeId,
        ]);

        return [
            'object' => $responseGet['objects'][0] ?? [],
            'payload' => $payload,
            'response' => [
                'put' => $responsePut,
                'get' => $responseGet
            ]
        ];
    }

    /**
     * Pobiera gotowe dane dotyczace Kategorii
     */
    public function grabNewCategory(int $count = 1): array
    {
        $objects = [];

        for($i = 1; $i <= $count; $i++){
            $productCategoryId = 'CATEGORY' . rand(1,99999);
            $objects[] = [
                'id' => $productCategoryId,
                'symbol' => '59272aae-eeaa-4eef-938c-6147487f3cf2',
                'type' => 'MENU',
                'is_active' => true,
                'name' => $this->prepareTranslation(),
            ];
        }

        $payload = [
            'objects' => $objects
        ];

        $responsePut = $this->sendPutAsJson('/categories', $payload);
        $this->seeResponseIsJson();
        $this->seeResponseCodeIs(HttpCode::OK);
        $this->seeResponseContainsJson([
            'message' => 'SUCCESS'
        ]);

        $responseGet = $this->sendGetAsJson('/categories', ['id' => $productCategoryId]);

        $this->seeResponseIsJson();
        $this->seeResponseCodeIs(HttpCode::OK);
        $this->seeObjectWithElementsInResponse($responseGet, $productCategoryId, [
            'id' => $productCategoryId,
        ]);

        return [
            'object' => $responseGet['objects'][0] ?? [],
            'payload' => $payload,
            'response' => [
                'put' => $responsePut,
                'get' => $responseGet
            ]
        ];
    }


    /**
     * Pobiera gotowe dane dotyczace Warehouse
     */
    public function grabNewWarehouse(int $count = 1): array
    {
        $objects = [];

        for($i = 1; $i <= $count; $i++){
            $warehouseId = 'WAREHOUSE' . rand(1,99999);
            $objects[] = [
                'id' => $warehouseId,
                'symbol' => $this->fake()->sentence(1),
                'name' => $this->prepareTranslation(),
                'description' => $this->prepareTranslation(),
                'is_active' => true,
            ];
        }

        $payload = [
            'objects' => $objects
        ];

        $responsePut = $this->sendPutAsJson('/warehouses', $payload);
        $this->seeResponseIsJson();
        $this->seeResponseCodeIs(HttpCode::OK);
        $this->seeResponseContainsJson([
            'message' => 'SUCCESS'
        ]);
        $this->seeObjectWithElementsInResponse($responsePut, $warehouseId, [
            'id' => $warehouseId,
        ]);

        $responseGet = $this->sendGetAsJson('/warehouses', ['id' => $warehouseId]);
        $this->seeResponseIsJson();
        $this->seeResponseCodeIs(HttpCode::OK);
        $this->seeObjectWithElementsInResponse($responseGet, $warehouseId, [
            'id' => $warehouseId,
        ]);

        return [
            'object' => $responseGet['objects'][0] ?? [],
            'payload' => $payload,
            'response' => [
                'put' => $responsePut,
                'get' => $responseGet
            ]
        ];
    }

    /**
     * Pobiera gotowe dane dotyczace ProductRelation
     */
    public function grabNewProductRelation(int $count = 1): array
    {
        $objects = [];
        $productFirst = $this->grabNewProduct()['object'];
        $productSecond = $this->grabNewProduct()['object'];

        for($i = 1; $i <= $count; $i++){
            $productRelationId = 'PRODUCT_RELATION' . rand(1,99999);

            $objects[] = [
                'id' => $productRelationId,
                'main_product_id' => $productFirst['id_external'],
                'relation_product_id' => $productSecond['id_external'],
                'relation_id' => $this->fake()->uuid(),
                'is_active' => true,
                'bilateral' => true,
            ];
        }

        $payload = [
            'objects' => $objects
        ];

        $responsePut = $this->sendPutAsJson('/product-relations', $payload);
        $this->seeResponseIsJson();
        $this->seeResponseCodeIs(HttpCode::OK);
        $this->seeResponseContainsJson([
            'message' => 'SUCCESS'
        ]);
        $this->seeObjectWithElementsInResponse($responsePut, $productRelationId, [
            'id' => $productRelationId,
        ]);

        $responseGet = $this->sendGetAsJson('/product-relations', ['mainProductId' => $productFirst['id_external']]);
        $this->seeResponseIsJson();
        $this->seeResponseCodeIs(HttpCode::OK);
        $this->seeObjectWithElementsInResponse($responseGet, $productRelationId, [
            'id' => $productRelationId,
        ]);

        return [
            'object' => $responseGet['objects'][0] ?? [],
            'payload' => $payload,
            'response' => [
                'put' => $responsePut,
                'get' => $responseGet
            ]
        ];
    }

    /**
     * Pobiera gotowe dane dotyczace PaymentMethod
     */
    public function grabNewPaymentMethod(int $count = 1): array
    {
        $objects = [];

        for($i = 1; $i <= $count; $i++){
            $paymentMethodId = 'PAYMENT_METHOD' . rand(1,99999);
            $objects[] = [
                'id' => $paymentMethodId,
                'name' => $this->prepareTranslation(),
                'description' => $this->prepareTranslation()
            ];
        }

        $payload = [
            'objects' => $objects
        ];

        $responsePut = $this->sendPutAsJson('/payment-methods', $payload);
        $this->seeResponseIsJson();
        $this->seeResponseCodeIs(HttpCode::OK);
        $this->seeObjectWithElementsInResponse($responsePut, $paymentMethodId, [
            'id' => $paymentMethodId,
            'message' => 'SUCCESS'
        ]);

        $responseGet = $this->sendGetAsJson('/payment-methods', ['id' => $paymentMethodId]);
        $this->seeResponseIsJson();
        $this->seeResponseCodeIs(HttpCode::OK);
        $this->seeObjectWithElementsInResponse($responseGet, $paymentMethodId, [
            'id' => $paymentMethodId,
        ]);

        return [
            'object' => $responseGet['objects'][0] ?? [],
            'payload' => $payload,
            'response' => [
                'put' => $responsePut,
                'get' => $responseGet
            ]
        ];
    }


}
