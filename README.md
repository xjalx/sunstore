
# SunStore - Katalog produktów z filtrami

Projekt został przygotowany jako zadanie rekrutacyjne. Kod został opracowany przy wsparciu narzędzia **Claude Code**.

## Koncepcja architektury

Podczas projektowania systemu, moim priorytetem było zapewnienie **wysokiej rozszerzalności (scalability & extensibility)**. Świadomie zrezygnowałem z sztywnego modelu bazy danych na rzecz elastycznej struktury, która pozwoli na błyskawiczne dodawanie nowych kategorii produktów bez modyfikacji rdzenia aplikacji.

### Moje kluczowe decyzje projektowe:

1.  **Zastosowanie modelu EAV (Entity-Attribute-Value):**
    Zdecydowałem się na przechowywanie specyficznych parametrów (np. pojemność, typ złącza) w oddzielnej strukturze atrybutów. Dzięki temu baza danych nie wymaga migracji przy wprowadzaniu nowych typów produktów, co jest kluczowe w dynamicznie rosnących systemach E-commerce.

2.  **Modułowy Pipeline filtracji:**
    Zaprojektowałem proces filtrowania w oparciu o wzorzec **Pipeline**. Każdy filtr (cena, nazwa, atrybuty techniczne) jest niezależnym ogniwem, co pozwala na łatwe dodawanie nowych warunków filtrowania bez ingerencji w logikę pozostałych.

3.  **Abstrakcja logiki filtrów (Strategy Pattern):**
    Wprowadziłem fabrykę strategii dla atrybutów specyficznych. System sam decyduje, czy dany parametr (np. moc) powinien być traktowany jako zakres (Range), czy jako wartość stała (Exact), bazując na definicji w Enumach.

## Instrukcja rozszerzania systemu

Dzięki przyjętej przeze mnie architekturze, dodanie nowego asortymentu sprowadza się do prostej konfiguracji:

### Jak dodać nowy produkt i atrybuty?

1.  **Krok 1 (Typ produktu):** W pliku `App\Enums\ProductType` dodaj nową kategorię (np. `INVERTER`).
2.  **Krok 2 (Atrybuty):** W pliku `App\Enums\FilterableAttribute` zdefiniuj nowe parametry techniczne (np. `EFFICIENCY`).
3.  **Krok 3 (Powiązanie):** Wróć do `ProductType` i w metodzie `filterableAttributes()` wskaż, które atrybuty przypisane są do nowego produktu.

**To wszystko.** Cała reszta – od walidacji danych wejściowych, przez mapowanie DTO, aż po automatyczne budowanie zapytań SQL z uwzględnieniem odpowiednich strategii – zostanie obsłużona automatycznie przez zaimplementowaną przeze mnie logikę.

## Refleksje i kierunki rozwoju

Przygotowując to zadanie, dużą część czasu poświęciłem na testowanie możliwości narzędzia **Claude Code** oraz badanie samego frameworka Laravel w kontekście wzorca Pipeline i nowoczesnych Enumów w PHP 8.3.

### Dlaczego Enumy, a nie Baza Danych?
Mam pełną świadomość, że w produkcyjnym, dynamicznie skalowalnym systemie E-commerce, definicje typów produktów i ich atrybutów powinny znajdować się całkowicie w bazie danych (np. w tabelach konfiguracyjnych połączonych z modelem EAV). Pozwoliłoby to na edycję parametrów z poziomu panelu administracyjnego bez ingerencji w kod.

W tym projekcie zdecydowałem się jednak pozostać przy definicjach w Enumach, ponieważ:
1. Chciałem skupić się na dostarczeniu solidnej architektury kodu i przetestowaniu przepływu danych (DTO -> Pipeline -> Strategy).
2. Uznałem to za złoty środek między czasem poświęconym na zadanie a jakością dostarczonej logiki biznesowej.

W kolejnym kroku rozwoju aplikacji, naturalną ewolucją byłoby przeniesienie logiki z `App\Enums\FilterableAttribute` do bazy danych, co obecna architektura (dzięki abstrakcji filtrów) pozwala zrobić przy minimalnym nakładzie pracy w warstwie usług (`Services`).
