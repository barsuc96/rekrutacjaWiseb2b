<h1> Architektura systemu Wise (backend) </h1>

<h2> Główne założenia architektury </h2>

Architektura systemu Wise B2B została oparta na wzorcu DDD, ale nie jest jego wiernym odzwierciedleniem.
Dzięki temu system ma zdolność do rozwijania i właściwego zarządzania kodem, budowania scenariuszy testowych, i odpowiedniej gotowości na modyfikacje wdrożeniowe (customizacji).
Choć struktura katalogów została dostosowana do powszechnego myślenia o architekturze trójwarstwowej, to sama zawartość została rozpisana wg wzorca DDD.

UWAGA !. Główna róznica pojęciowa pomiędzy przyjętą architektura (wzorcowana na DDD) a klasycznym podejściem Symphony to pojęcie <B> ENCJI </B>

Zwykle programiści utożsamiają Encję z Tableką w bazie (1:1). U nas Encja jest OBIEKTEM BIZNESOWYM. To FUNDALMENTALA różnica. W jaki sposób OBIEKT jest persystowany, decyduje warstwa persystencji - w tabelkach, jakich tabelkach,a  może plaskich plikach ? Warstwy logiki to NIE OBCHODZI. 

UWAGA2 ! Aktualny wzorzec kodu jest w ProductUnits - jako przykłąd prosty, ale nie banalny. Ponieważ obecnie się koncetrujemy na AdminAPI, jeszcze nie ma serwisów realnych biznesowych, a głównie Crude

<h2> Architektura i katalogi </h2>

<h3>Warstwa prezentacji</h3><BR>

   W warstwie prezentacji mamy wszystko to, co odpowiada za prezentacje danych na świat zewnętrznych ( tak, jak świat zewnętrzny tego potrzebuje), i przyjmowanie oczekiwań ze swiata zewnętrznego - czego świat chce zrobić na naszym modelu.<BR>
   Główne założenia dotyczące warstwy prezentacji:
   1. Zero logiki biznesowej - żadnego porównywania wartości property jakiś encji, uzależniania działania od jakiś wartości boznesowych.
   2. Jedyne zadanie warstwy prezentacji to konwersja pomiędzy konsumentem a warstwą logiki biznesowej i wywoływanie odpowiednich serwisów warstwy logiki biznesowej 
      1. przekształcanie struktur warstwy biznesowej na potrzeby warstwy prezentacji
      2. przekształcanie struktur prezentacji na struktury potrzebne do wywoływania serwisów warstwy logiki biznesowej (DTO na parametry deklarowane przez serwisy aplikacji i/lub serwisy domen)
   3. Nie powinniśmy używać struktur warstwy logiki biznesowej. 
   4. Zakaz używania metod repozytoriów służących do manipulacji danymi. To robimy tylko przez serwisy logiki biznesowej<BR><BR>

   Katalogi warstwy<BR>
   1. \ApiAdmin - moduł prezentacji systemu dla innych systemó, z którymi się integrujemy. Zasadniczo realizuje strategię CRUD do obsługi procesó wymiany danych z innymi systemami. Obejmuje kontrolery, DTO i transformety DTO<->serwisy warstwy logiki <BR>
      1. \Controller - punkt dostępowy dla enpointu. Budowany wg wzorca, nie grzebiemy (tzn. ustawiamy tylko routingi, podłączamy DTO, ale kod jest wg wzorca).
      2. \DTO - struktury definiujące struktury requestów, parmetrów filtrów, i reposne dla poszczególnych Controllerów. Tutaj opisujemy elementy tych struktur, na tej podstawie jest potem generowana dokumentacja API oraz OpenAPI. Dzięki tym strukturom następuje automatyczna walidacja techniczna requestów. 
      3. \Service - metody obsługi transformacji dla poszczególnych enpointów, jako parametr przyjmują DTO. Do manimpulacji danych korzystają z serwisów warstwy domeny. Do odczytu korzystają z serwisów logiki biznesowej. <BR>
   2. \UpiUI - kontrolery, DTO i serwisy obsługi wywołań UIApi, pełna analogia jak wyżej.<BR>

<h3>Warstwa logiki biznesowej</h3> <BR>

   To tutaj dzieje się logika biznesowa. Nigdzie indziej. Tutaj są deklarowane struktury obiektów odzwierciedlajacych byty biznesowe(modele) (np. zamówienie, klient, produkt). Do modelowania tych struktur staramy się stosować zasady DDD. <BR>
   Katalogi warstwy:
   1. \Domain - modele i generalnie cała logika  danego modułu, skupiona wokół obiektów biznesowych. Obejmują:<BR>
      1. ValueObject - obiekty typu Adres, email, Trasnlacja. Zawierają property, settery, i walidację property (zazwyczaj na setterach)
      2. Entity - obiekt biznesowy, posiadający własny identyfikator, podlegający persystencji. O ile wymaga tego zasada spójności, możę posiadać property skłądających się z innych Encji, i wtedy te Encje nie posiadają włąsnych metod manipulacji, a są tworzone/modyfikowane/usuwane poprzez obiekt agregata. W metodach Encji nie realizujemy żadnych persystencji - te są tylko w serwisach. Metoda encji możę (?) używać serwisu (czy tylko metod innych modeli lub włąsnych?) 
      3. Eception - wyjątki o znaczeniu biznesowym 
      4. Event - zdarzenia , jeszce ich nie ma, ale tutaj będą ich definicję. To struktura niosąca jakieś określone dane od adresata do odbiorcy. Jeszcze nie mamy wybranego mechanizmu zdarzeń, pewnie RabbitMQ.
      5. RepositoryInterface - interfejs do repozytorium  Entity 
   2. \Service - serwisy obsługi logiki biznesowej modeli, tzw. warstwa serwisów aplikacji. Klasy jednometodowe (najlepiej), wywoływane z zewnątrz przez __invoke, każda implementująca własny interfejs, co umożliwa rekonfigurowanie działania systemu.<BR>
      Rozróżniamy następujące typy:  
      1. Service - metoda biznesowa której logika nie należy bezpośrednio do jakiegoś modelu, ale go dotyczy i operuje zasadniczo na nim. Np. "Zamknij zamówienie", "Zarejestruj użytkownika".<BR>
         Standardowo każda Encja posiada co najmniej te metody (wykorzystywane głównie przez Admin API):
         1. Add - zwalidowanie biznesowe i zapisanie (w tym nadanie identyfikatora) nowo utworzonemu obiektowi. Parametr: dane o strukturze najlepiej odpowiadajacej Encji, bo wtedy wiekszość zrobi automat.
         2. Modify - aktualizacja obiektu poprzez przepisanie ustawionych property przekazywanego obiektu, zwalidowanie, i zapisanie do repozytorium. Parametr: jak wyżej 
         3. Delete - usuwanie poprzez identyfikator, waliduje czy to można zrobić 
         4. AddOrModify - dodaje lub modyfikuje, korzysta z dwóch powyższych
         5. ListByFilters - listowanie DANYCH (nie ENCJI, nie MODELU) wg podanych filtrów, z możliwością deklarowaia jakie property encji chcemy mieć zwracane i z jakich encji (ta w której ejsteśmy + ewentualnie relacyjne)  
      2. Policy - metoda wyliczenia czegoś na użytek modelu. Np. metoda wyliczenia rabatu dla pozycji w danym koszyku. Albo wyliczenia wartości brutto z netto, albo na odwrót. 

3. <h3>Warstwa persystencji</h3><BR>
   
      1. \Repository\Doctrine - implementacja repozytoriów w Doctrinie. Jeszcze będzie refaktorowana, bo chcemy przejść na pliki KONFIGURACYJNE, może auto-generowane na początku z Encji, i usunac adnotacje Doctrina z Encji (zaszłość)
      2. \Repository\InMemory - implementacja repozytórwiw w pamięci, używana głównie do testowania


Zagadnienia do omówienia:
1. Id Enecji - czy to int, czy jednak definiujemy coś takiego jak EntityId, albo wręcz ProductId, ProductUnitId, OrderId - aby mieć stron typy w argumentach. Na obiektach CostamId możemy definiować operatory porównania, i walidacje
2. Gdybyśmy się stosowali wytycznych aby metody stanowiące naturalna odpowiedzialnosć modelu (np. "Aktywuj","Deaktywuj") robić w klasie Encji, to czy będziemy w stanie technicznie jakoś to przeciazać ?
   1. Dekoratory nam w tym pomogą (nie znam mechanizmu w ogóle)
   2. A może lepiej zrobić Factory do Encji, i w ten sposób ?
