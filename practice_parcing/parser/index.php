<?php
    require_once '../../vendor/autoload.php';
    use DiDom\Document;

    $domain = 'https://salary.ordertarget.ru';
    $href = '/worker/2024/06';

    $normUrl = 'https://www.php.net/manual/en/function.file-get-contents.php';
    // сайт для картинок
    $sitePicture = 'https://www.gtrk-vyatka.ru/vesti/';
    // для скачки стилей
    $stiledUrl = 'https://www.gtrk-vyatka.ru/engine/classes/min/index.php?f=engine/editor/css/default.css&v=431kr';

    // для парсинга с авторизованных сайтов
    $openWeather = 'https://openweathermap.org/';

    return $document = new Document($openWeather, true);

