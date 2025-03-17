# Deptrac

[Dokumentacja](https://qossmic.github.io/deptrac/)

## Uruchamianie

Wymaga PHP 8.1 lub nowszego. Można uruchomić wewnątrz kontenera aplikacyjnego lub skorzystać z gotowych obrazów, np. [jakzal/phpqa](https://github.com/jakzal/phpqa).

```shell
$ composer install
$ composer run-script analyse
```

### Generowanie grafów

```shell
$ composer install
$ composer run-script _prepare
$ composer run-script graph-full
$ composer run-script graph-app
```
