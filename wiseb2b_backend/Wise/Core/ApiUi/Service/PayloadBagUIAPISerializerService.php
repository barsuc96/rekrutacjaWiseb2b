<?php

namespace Wise\Core\ApiUi\Service;

use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Wise\Core\ApiUi\Service\Interface\PayloadBagUIAPISerializerServiceInterface;
use Wise\Core\Entity\PayloadBag\PayloadBag;
use Wise\Core\Model\Translations;
use Wise\Core\Service\TranslationService;
use Wise\Core\ServiceInterface\Locale\LocaleServiceInterface;
use ReflectionClass;

/**
 * TODO: Tymczasowy deserializator (po dodaniu scopowania listingu uwspólnić i używać jeden)
 */
class PayloadBagUIAPISerializerService implements PayloadBagUIAPISerializerServiceInterface
{
    public function __construct(
        private readonly TranslationService $translationService,
        private readonly LocaleServiceInterface $localeService
    ){}

    public function __invoke(array $fullProductInfos, ?string $language = null): array
    {
        if($language === null){
            $language = $this->localeService->getCurrentLanguage();
        }

        return $this->deserializePayloadBag($fullProductInfos, $language);
    }

    /**
     * Deserializuje tablicę PayloadBag
     * @param array $payloadBag
     * @param string $language
     * @return array
     * @throws ExceptionInterface
     * @throws \ReflectionException
     */
    protected function deserializePayloadBag(array $payloadBag, string $language): array
    {
        $results = [];

        // Iteruje po każdym produkcie
        foreach ($payloadBag as $productId => $productShortInfo) {
            $element = $this->deserializeSinglePayloadBag($productShortInfo, $language, $productId);
            if($element === []){
                continue;
            }

            $results[] = $element;
        }

        return $results;
    }

    /**
     * Deserializuje pojedynczy PayloadBag
     * @param PayloadBag|array $payloadBag
     * @param string $language
     * @param string $elementId
     * @return array
     * @throws ExceptionInterface
     * @throws \ReflectionException
     */
    public function deserializeSinglePayloadBag(PayloadBag|array $payloadBag, string $language, string $elementId): array
    {
        $elementData = [];
        $elementData['id'] = $elementId;


        if(!is_array($payloadBag)){
            // Deserializacja PayloadBag
            $serializer = new Serializer([new ObjectNormalizer()]);
            $deserializedPayloadBag = $serializer->normalize($payloadBag);
        }else{
            $deserializedPayloadBag = $payloadBag;
        }


        // Sprawdzam czy PayloadBag zawiera payloadsList
        if (!array_key_exists('payloadsList', $deserializedPayloadBag) || !is_array($deserializedPayloadBag['payloadsList'])) {
            return [];
        }

        // Iteruje po każdym payloadzie i dodaje go do tablicy z danymi
        foreach ($deserializedPayloadBag['payloadsList'] as $class => $payload) {

            // Pobieramy nazwę Providera np. ProductBasicShortInfo
            $infoClassName = basename(str_replace('\\', '/', $class));

            // Dodaje payload do tablicy z danymi
            $elementData[$infoClassName] = $this->preparePayloadForResponse($class, $payload, $language);
        }

        // Dodaje dane do tablicy z wynikami
        return $elementData;
    }

    /**
     * Przygotowuje rezultatu payload do zwrócenia
     * @param string $class
     * @param mixed $payload
     * @param string|null $language
     * @return array
     * @throws \ReflectionException
     * @throws ExceptionInterface
     */
    public function preparePayloadForResponse(string $class, array $payload, ?string $language = null): array
    {
        $reflectionClass = new ReflectionClass($class);

        // Przygotowujemy pola na podstawie konkretnych typów zmiennych
        foreach ($reflectionClass->getProperties() as $property) {
            $propertyName = $property->getName();
            $propertyType = $property->getType();
            $typeName = $propertyType->getName();

            // ===== Przygotowanie translacji - Pole Translations =====
            if ($typeName === Translations::class) {

                // Jeśli istnieje pole w payload, którego typ to Translations to tłumaczymy go
                if(!empty($payload[$property->getName()])){
                    $payload[$property->getName()] = $this->translationService->getTranslationForField($payload[$property->getName()], $language);
                }else{
                    // Jeśli nie ma tłumaczenia to ustawiamy null
                    $payload[$property->getName()] = null;
                }
            }
            // ===== Przygotowanie translacji - Pole Translations =====

            // ===== Obsługa rekurencyjna pól tablicowych =====
            if (is_array($payload[$propertyName] ?? null)) {
                $docComment = $property->getDocComment();

                if ($docComment && preg_match('/@var\s+([\w\\\\\[\]]+)/', $docComment, $matches)) {
                    $typeFromDoc = $matches[1];

                    // Obsługa tablicy obiektów (np. AttributeInfo[]).
                    if (str_ends_with($typeFromDoc, '[]')) {
                        $itemClass = rtrim($typeFromDoc, '[]');
                    } else {
                        $itemClass = $typeFromDoc;
                    }

                    // Konstruowanie pełnej nazwy klasy z przestrzenią nazw
                    if (!class_exists($itemClass)) {
                        $namespace = $reflectionClass->getNamespaceName();

                        // Pobieramy mapowanie przestrzeni nazw (use statements) z klasy
                        $uses = $this->getAllClassImports($reflectionClass);

                        // Jeśli klasa jest zaimportowana, używamy pełnej nazwy z importu
                        if (isset($uses[$itemClass])) {
                            $itemClass = $uses[$itemClass];
                        } else {
                            // Jeśli klasa nie jest zaimportowana, dodajemy lokalną przestrzeń nazw
                            $itemClass = $namespace . '\\' . $itemClass;
                        }
                    }


                    if (class_exists($itemClass)) {

                        // Jeśli klasa dziedziczy po PayloadBag to deserializujemy ją
                        if(is_subclass_of($itemClass, PayloadBag::class)){
                            $payload[$propertyName] = $this->deserializePayloadBag($payload[$propertyName], $language);
                            continue;
                        }

                        // Jeśli klasa nie dziedziczy po PayloadBag to iterujemy po każdym elemencie tablicy
                        foreach ($payload[$propertyName] as $key => $item) {
                            if (is_array($item)) {
                                // Rekurencyjne przetwarzanie dla każdego elementu tablicy
                                $payload[$propertyName][$key] = $this->preparePayloadForResponse($itemClass, $item, $language);
                            }
                        }
                    }
                }
            }
            // ===== Obsługa rekurencyjna pól tablicowych =====

            // .... inne typy zmiennych ....
        }

        return $payload;
    }

    /**
     * Pobrać wszystkie importy klas z pliku
     * @param ReflectionClass $reflectionClass
     * @return array
     */
    protected function getAllClassImports(ReflectionClass $reflectionClass): array
    {
        $allImports = [];

        // Przetwarzamy wszystkie klasy w hierarchii dziedziczenia
        while ($reflectionClass) {
            $fileName = $reflectionClass->getFileName();
            if ($fileName && file_exists($fileName)) {
                $content = file_get_contents($fileName);

                // Dopasowanie importów (use statements)
                preg_match_all('/^use\s+([a-zA-Z0-9_\\\\]+)\s*;\s*$/m', $content, $matches, PREG_SET_ORDER);

                foreach ($matches as $match) {
                    $fullClassName = $match[1];
                    $shortClassName = substr($fullClassName, strrpos($fullClassName, '\\') + 1);
                    $allImports[$shortClassName] = $fullClassName;
                }
            }

            // Przechodzimy do klasy nadrzędnej
            $reflectionClass = $reflectionClass->getParentClass();
        }

        return $allImports;
    }

    /**
     * Pobiera importy klas z pliku
     * @param ReflectionClass $reflectionClass
     * @return array
     */
    protected function getClassImports(ReflectionClass $reflectionClass): array
    {
        $fileName = $reflectionClass->getFileName();
        if (!$fileName || !file_exists($fileName)) {
            return [];
        }

        $content = file_get_contents($fileName);

        // Dopasowanie importów (use statements)
        preg_match_all('/^use\s+([a-zA-Z0-9_\\\\]+)\s*;\s*$/m', $content, $matches, PREG_SET_ORDER);

        $imports = [];
        foreach ($matches as $match) {
            $fullClassName = $match[1];
            $shortClassName = substr($fullClassName, strrpos($fullClassName, '\\') + 1);
            $imports[$shortClassName] = $fullClassName;
        }

        return $imports;
    }
}
