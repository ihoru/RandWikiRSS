# RandWikiRSS

Script lets you read new random articles from Wikipedia.org every day.

## How to use RandWikiRSS?
You can visit <http://iho.su/RandWikiRSS/?lang=en> or add it to your favorite RSS-reader.

Supported languages are: de, en, es, fr, it, nl, pl, ru, ceb, sv, vi, war (subdomain, i.e. ru.wikipedia.org)

Supported "engines" are: pedia (wikipedia.org), quote (wikiquote.org)

## How to use RandWikiRSS on own server?
1. Create copy of repository:
```shell
	git clone https://github.com/ihoru/RandWikiRSS.git
	cd RandWikiRSS
```
2. Install vendor software with [composer](https://getcomposer.org/download/)
```shell
	composer install
```
3. Copy <code>config.example.php</code> to <code>config.php</code>.
4. Make directory data/ readable for apache user.
5. Open index.php page in browser

## Requirements
* PHP 5.4 or above
* [mibe/feedwriter](//github.com/mibe/feedwriter)
* [ihoru/wikirandom](//github.com/ihoru/wikirandom) (fork of [byteriot/wikirandom](//github.com/byteriot/wikirandom))
* [oleku/supervariable](//github.com/oleku/supervariable)

## Example
![Example for English version](http://bloho.ru/RandWikiRSS/example_en.png)

## Changes
2015-01-05

	* Added support of "engines", now you can read quotes-pages from wikiquote.org in separate RSS feed. Just add argument "**engine=pedia**" to the URL.

## Licence
MIT

## Author
Ihor Polyakov (ihoru) - <ihor.polyakov@gmail.com>.

This was my first mini project on Github (just for fun).
