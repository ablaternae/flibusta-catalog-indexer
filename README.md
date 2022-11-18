# поисковик на примере каталога флибусты

## термины и лицензии
* [флибуста](https://ru.wikipedia.org/wiki/%D0%A4%D0%BB%D0%B8%D0%B1%D1%83%D1%81%D1%82%D0%B0)
* каталог &mdash; архив списка книг (автор, название, год, ид), находится в открытом доступе
* данный проект не аффилирован с какими-либо торговыми марками, здесь упомянутыми
* файлы из каталога /data/* не являются авторскими разработками и могут быть использованы без лицензии. Лицезию исходного файла данных (каталога) установить не удалось. Формальная лицензия проекта Unlicense License совместима с MIT, WTFPL, CC-BY

## проект
Проект выполнен в исследовательских и экспериментальных целях. Были выявлены ограничения гитхаба и других хранилищ, установлено, что образы ubuntu* для gihub actions содержат множество дополнительного софта (apache, bind, mariadb, dot-net sdk, nodejs, php, python и т.п.), проверены возможности SQLite (удобно расширять внешними пользовательскими функциями). Результаты экспериментов будут учтены в проектах [@metaphonia](//github.com/metaphonia/) и git-acts

## состав файлов
### в каталоге /data
* catalog.zip, catalog.txt &mdash; исходные файлы, внутренний формат похож на CSV с разделителем ```;```, но таковым не является, потому что поле №5 ```Subtitle``` может содержать несколько полей __с тем же разделителем__
* catalog.csv.txt &mdash; исправленный и подготовленный
* catalog.sqlite &mdash; бд с закачанными данными, структура: 
```sql 
create table if not exists 
catalog (surname varchar(255), name varchar(255), patronymic varchar(255), 
          title text, subtitle text, language char(2), year int, series text, id integer);
```
* catalog.csv &mdash; дамп в _правильном_ CSV
* catalog.sql.gz &mdash; дамп ANSI SQL

### /docs
* содержит демо https://ablaternae.github.io/flibusta-catalog-indexer/
* и проиндексированные данные в формате JSON ```/docs/i/``` 
### /scripts
* разные скрипты преобразования данных и вспомогательные функций

## todo
* [x] придумать морфологический поиск
* [x] проиндексировать содержимое
* [x] написать реализацию
* [x] прикрутить веб-интерфейс
* [x] статистика поиска, посещения страниц
* [ ] тг-бот

## немного статистики
* ![Visitor count](https://shields-io-visitor-counter.herokuapp.com/badge?page=ablaternae.flibusta-catalog-indexer)
[![Hits](https://hits.seeyoufarm.com/api/count/incr/badge.svg?url=https%3A%2F%2Fablaternae.github.io%2Fflibusta-catalog-indexer%2F&count_bg=%2379C83D&title_bg=%23555555&icon=&icon_color=%23E7E7E7&title=counter&edge_flat=true)](https://hits.seeyoufarm.com)
[![Hits](https://hits.sh/ablaternae.github.io/flibusta-catalog-indexer.svg?view=today-total&style=flat-square&label=hits.sh)](https://hits.sh/ablaternae.github.io/flibusta-catalog-indexer/)
![GitHub](https://img.shields.io/github/license/ablaternae/flibusta-catalog-indexer?style=flat-square)
![GitHub repo size](https://img.shields.io/github/repo-size/ablaternae/flibusta-catalog-indexer?style=flat-square)
![GitHub repo directory count (custom path)](https://img.shields.io/github/directory-file-count/ablaternae/flibusta-catalog-indexer/docs/i?label=index%20dirs&style=flat-square&type=dir)
![GitHub file size in bytes on a specified ref (branch/commit/tag)](https://img.shields.io/github/size/ablaternae/flibusta-catalog-indexer/data/catalog.txt?label=origin%20data%20size&style=flat-square)
![GitHub file size in bytes on a specified ref (branch/commit/tag)](https://img.shields.io/github/size/ablaternae/flibusta-catalog-indexer/data/catalog.csv?label=target%20csv&style=flat-square)
![GitHub file size in bytes on a specified ref (branch/commit/tag)](https://img.shields.io/github/size/ablaternae/flibusta-catalog-indexer/data/catalog.sql.gz?label=sql.gz&style=flat-square)

* общее количество записей в первоначальном архиве более 600 000 и увеличивается
* размер исходных данных в текстовом виде более 70Мб
* SQL дамп 100Мб
* JSON дамп 120Мб
* общее количество слов длиннее двух символов более 10E6 (10 миллионов)
* размер индексных файлов 21Мб (место на диске 2,2Гб для NTFS). учтите это, клонирование репозитория может приводить к неожиданным зависаниям
* скорость поиска в индексе стремится к нулю. точнее, сравнима с пропускной способностью интернет-канала и растет не более чем линейно в зависимости от количества слов

## собрано при помощи
* [bash](https://www.gnu.org/software/bash/)
* [AWK](https://www.grymoire.com/Unix/Awk.html#toc_Intro_to_AWK)
* [SQLite](https://sqlite.org/docs.html)
* [PHP](https://www.php.net/manual/ru/)
* [zepto.js](//github.com/madrobby/zepto)
* [underscore.js](//github.com/jashkenas/underscore)
* [superkube](//github.com/imperavi/superkube)
* [ЧудоТекст](//github.com/Alexey-T/CudaText)

всем спасибо, проект завершён. если хотите на своем маленьком сайте быстрый поиск, свяжитесь со мной
