# Typescript to php source converter

![ts2phplogo](https://github.com/utopszkij/ts2php/blob/master/doc/ts2php.png) 

Php is a non type oriented language, resulting in syntax errors, strikes
most of it is only running time (better during testing,
worse in sharp use).

That's why many programmers like to use the type oriented languages, where bugs are great
part of translation is time out.

This software can provide a subset of **typescript** type oriented programming language
to **php**.

The translation is done with a translation program written in php, on the other hand
this is done by using libraries for this purpose.

Due to the incompatibility of the two languages there are several javascript / typescript options
should be avoided.

## version
V 1.00  in developing; test version

## licence
GNU/GPL

## syntax

[See the available syntax here.](https://github.com/utopszkij/ts2php/blob/master/doc/syntax.md)

## request

- tsc  typescript compiller
- php  php interpreter

## use

>ts2php tsFileName  (tsFileName without '.ts')

## install

- cd 'this_repo_root'
- sudo cp ./sbin/ts2php /sbin/ts2php
- sudo cp ./sbin/ts2php.php /sbin/ts2php.php
- sudo chmod 0555 /sbin/ts2php
- sudo chmod 0444 /sbin/ts2php.php
- sudo cp ./ts2php_core/* 'your project repo'/ts2php_core

## example:

>ts2php ./test/test

# Typescript --> php forrás konverter

A php nem típusos nyelv, ennek következtében a szintaktikai hibák, elütések 
nagy része csak futási időben (jobb esetben a tesztelés során, 
rosszabb esetben az éles használatban) derül ki.

Ezért sok programozó a típusos nyelvek használatát kedveli, ahol a hibák nagy 
része fordítás időben derül ki.

Ez a szoftver a typescript típusos programnyelv egy szűkített változatát tudja
php kóddá transformálni.

A fordítás egyrészt egy php nyelven megírt fordító programmal történik, másrészt
erre a célra készült függvény könyvtárak segítségével valósul meg.

A két nyelv inkompatibilitása miatt jónéhány javascript/typescript lehetőség
használatát mellőzni kell.

## verzió
V 1.00  fejlesztés altt, teszt verzió

## licensz
GNU/GPL

## szintaxis

>[A használható szintaxist lásd itt.](https://github.com/utopszkij/ts2php/blob/master/doc/syntax.md)


## Szükséges szoftver elemek

- tsc
- php

## használat

>ts2php tsFileName  (tsFileName ne tartalmazza a '.ts' -t)

## telepités

- cd 'this_repo_root'
- sudo cp ./sbin/ts2php /sbin/ts2php
- sudo cp ./sbin/ts2php.php /sbin/ts2php.php
- sudo chmod 0555 /sbin/ts2php
- sudo chmod 0444 /sbin/ts2php.php
- sudo cp ./ts2php_core/* 'your project repo'/ts2php_core

## példa:

>cd test

>ts2php test

