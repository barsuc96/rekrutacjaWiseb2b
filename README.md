# Wise B2B - Rekrutacja

Witaj! Bardzo się cieszymy, że chcesz dołączyć do zespołu WiseB2B.
Poniżej znajdziesz zadanie rekrutacyjne, które pozwoli nam poznać Twoje umiejętności programistyczne.

## DODATKOWE INFORMACJE:

### Dokumentacja
[Dokumentacja WiseB2B](https://test.wiseb2b.eu/docs/)

Zalecamy zapoznać się z dokumentacją, szczególnie z zakładką [Core](https://test.wiseb2b.eu/docs/docs/developer/core)

## URUCHOMIENIE PROJEKTU

### Zbudowanie kontenerów

```shell
$ docker compose build --no-cache
```

```shell
$ docker compose up -d
```

### Konfiguracja środowiska



Połączenie się z konsolą kontenera:

```bash
$ docker compose exec app bash
```

```bash
composer install
```

1. Tworzymy bazę od nowa (wykonaj obie komendy):
```bash
$ php bin/console doctrine:database:create
```
```bash
$ php bin/console doctrine:database:create --connection admin
```
2. Aktualizujemy schemat bazy _(tworzy wszystkie tabele)_:
```bash
$ php bin/console doctrine:schema:update --force
```
```bash
$ php bin/console doctrine:schema:update --em=admin --force
```
3. Uruchamiamy migrację danych:
```bash
$ php bin/console doctrine:migration:migrate
```


### NELMIO

Jak już udało Ci się wszystko wykonać możesz przejść do nelmio.
Nelmio jest dostępne pod adresem:

```
http://localhost:8080/ui-api/nelmio
```

### Dane do autoryzacji w ADMIN API - NELMIO

1. Wejdź do nelmio ADMIN API
```
http://localhost:8080/admin-api/nelmio
```
2. Wykonaj POST `/api/admin/token`

3. Jako request body podaj:

```json
{
"client_id": "dea6d5hagv0c342d674o087Ef3e13E7g",
"client_secret": "8eefe0a060250c96cc9ee1bada11d4069ca6553b9dae4f81180f9777866db0799010e9fe75a0244d209924d1337946393f1682a5d52e07a738dc842891d97509"
}
```

4. Kliknij Execute 
5. W prawym górnym rogu witryny znajduje się button "Authorize", kliknij go
6. Wpisz token zwrócony w odpowiedzi z punktu 3. w pole "Value" i kliknij "Authorize"
7. Od tej pory wszystkie zapytania do ADMIN API będą autoryzowane

**UWAGA! Nie możesz wejść do ADMIN API, dostajesz błąd "Cannot automatically create parameters because responseDtoClass is not set" ? (Zerknij na zadanie numer 1)**


---
LISTA ZADAŃ

---

## Zadanie 1


```php
http://localhost:8080/admin-api/nelmio
```

Przywróć do działania powyższą podstrone, czyli nelmio dla ADMIN-API

## Zadanie 2

Dodaj za pomocą migracji symfony nowego dostawce (encja GpsrSupplier) z polami:

```
symbol: WiseB2B
nip: 1234567890
numer telefonu: 000111999
email: przykladowy_jan_kowalski@example.com
nazwa handlowca: WiseB2B Sp. z o.o.
adres:
    ulica: Przykładowa 44
    kod pocztowy: 00-000
    miasto: Warszawa
    kraj: Polska
```

## Zadanie 3
Dla endpointa GET `/api/admin/suppliers` dodaj filtr umożliwijący filtrowanie dostawców po symbolu.
Poprawne działanie ma być widoczne w nelmio (w sekcji parameters endpointu)

## Zadanie 4

Zrealizuj poniższe "zadanie zlecone przez klienta":


_Klient zleca Ci zadanie. Dodał poniższy opis zadania do Jiry:_

```php
Ostatnio zlecaliśmy wam implementacje GPSR.
Jednakże chcielibyśmy dodatkowo wykorzystać to co dla nas przygotowaliście abyśmy mogli widzieć jak dużo danych dostawców mamy uzupełnione.

Chcielibyśmy abyście do poberanych przez nas danych o dostawcach  dodali dodatkowe pole, które będzie określało jakość dostawcy (oparty na wprowadzonych danych)

Na podstawie poprawności i obecności poszczególnych pól obliczy wynik (zaczynając od 0 punktów):
Tax Number: Jeśli poprawny – dodaj 10 punktów.
Email: Jeśli poprawny – dodaj 10 punktów.
registeredTradeName: Jeśli pole jest podane – dodaj 5 punktów.
Address: Jeśli adres został podany i zawiera wszystkie wymagane pola – dodaj 10 punktów.
Phone: Jeśli pole jest podane i poprawne – dodaj 5 punktów.
Email: Jeśli domena e-mail (część po „@”) znajduje się na liście zaufanych to są domeny:    example.com, my-company.eu, wiseb2b.eu     – dodaj dodatkowe 5 punktów.
Lokalizacja adresu: Jeśli adres został podany, a pole city równa się np. "Warszawa" – dodaj 5 punktów.


Jak macie rozróżniać jakość:

High Quality: wynik ≥ 35 punktów.
Medium Quality: wynik pomiędzy 20 a 34 punktów.
Low Quality: wynik poniżej 20 punktów.

```
 
----

_Zadanie zostało przekazane od PM do architekta, który nałożył swoje uwagi:_

**Uwagi od architekta:**

- Niech będzie to zadanie realizowane poprzez rozszerzenie enpointu /api/admin/suppliers.
- Zmiana ma być realizowana w pluginie klienta jako rozszerzenie standardowej funkcjonalności.
- Endpoint musi zwracać dane o ilości zdobytych punktów oraz o jakości dostawcy w formie tekstu (np. High Quality).
- Zwróć uwagę, aby w przyszłości można było łatwo dodać nowe reguły do oceny jakości dostawcy.


_Zadanie trafiło do Ciebie do realizacji_

