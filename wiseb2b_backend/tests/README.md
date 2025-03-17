## Biblioteka

Biblioteka wykorzystywana do testowania WiseB2B: [https://codeception.com/](https://codeception.com/).

## Dodawanie testów do modułu

1. Wpisz komendy

```shell
$ export MODULE=Stock
$ php vendor/bin/codecept bootstrap Wise/${MODULE}
$ php vendor/bin/codecept generate:suite AdminApi -c Wise/${MODULE}
$ php vendor/bin/codecept generate:suite UiApi -c Wise/${MODULE}

```

2. W `Wise/{moduł}/codeception.yml` dokonaj kilku adaptacji. Przykład:

```yaml
namespace: Wise\{moduł}\Tests
support_namespace: Support
paths:
  tests: Tests
  output: ../../tests/_output
  data: Tests/Support/Data
  support: Tests/Support
actor_suffix: Tester
extensions:
  enabled:
  - Wise\Core\Tests\Support\Events\PrepareDatabaseForTest
  - Codeception\Extension\RunFailed
```

3. Dodaj w globalnym pliku `codeception.yml` (plik znajduje się w katalogu głównym projektu) moduł w sekcj iinclude

```yaml
include:
- Wise/{moduł}
```

4. Zmodyfikuj plik: `AdminApi.suite.yml` oraz `UiApi.suite.yml`. Przykład:

```yaml
actor: AdminApiTester
suite_namespace: Wise\{moduł}\Tests\AdminApi
step_decorators:
- \Codeception\Step\AsJson
modules:
  enabled:
  - Symfony:
      app_path: ../../src
      environment: test
  - Doctrine2:
      depends: Symfony
  - REST:
      depends: Symfony
      url: /api/admin/
```

5. W `Tests/Support/UnitTester.php` zmodyfikuj namespace (sprawdzić w innych testach - trzeba pamiętać o namespace i trait). To samo należy zrobić z plikami: `AdminApiTester.php` oraz `UiApiTester.php`

6. Skasuj pliki z `Tests/Support/\_generated/` i uruchom budowanie zależności poleceniem

```shell
$ php vendor/bin/codecept build
```

7. Uruchom test

```shell
$ php vendor/bin/codecept run -c Wise/{moduł}
```

---

## Używane Suite w projekcie
* **BasicTester** - Używana w momencie kiedy potrzebujemy jednocześnie skorzystać z ADMIN API oraz UI API
* **UiApiTester** - Używana w momencie kiedy robimy testy dla UI API
* **AdminApiTester** - Używana w momencie kiedy robimy testy dla ADMIN API
---

## Trait dla testerów - metody pomocnicze
* **AdminApiTesterTrait** - Metody pomocnicze dedykowane dla AdminApiTester
* **UiApiTesterTrait** - Metody pomocnicze dedykowane dla UiApiTester
* **BasicApiTesterTrait** - Metody pomocnicze dedykowane dla BasicApiTester
* **TesterUtilsTrait** - Metody pomocnicze i dodatkowe assercje dla wszystkich testerów
---


## Dodanie nowych testów jednostkowych w modułach

```shell
$ php vendor/bin/codecept generate:cest Unit SampleTest -c Wise/${MODULE}
```

---

## Przed uruchomieniem testów

Testy uruchamiamy na dockerze! Nie robimy tego lokalnie. 

Wchodzimy w kontener z aplikacją backend:
```shell
$ docker compose exec app bash
```

## Uruchomienie testów

Konkretny cest w konkretnym module

```shell
$ php vendor/bin/codecept run Wise/Product/Tests/AdminApi/Units/PutUnitsCest.php  
```

Konkretny text w konkretnym pliku Cest

```shell
$ php vendor/bin/codecept run Wise/Product/Tests/AdminApi/Units/DeleteUnitsCest.php:successUnitDelete 
```

Wszystkie testy, jakie istnieją

```shell
$ php vendor/bin/codecept run
```


