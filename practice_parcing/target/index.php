<?php
    require_once '../../vendor/autoload.php';
    use DiDom\Document;
    use DiDom\Query;
    $document = require_once '../parser/index.php';

    $elems = $document;

//    работсы с сылками
//    echo $document->find('a');
//    if(empty($elems)){
//        echo 'нет ссылок';
//    }  else {
//        foreach($elems as $elem) {
//            echo $elem->attr('href') . "<br>";
//        }
//    }

//    вывод всего текста
//    foreach($elems as $elem) {
//        echo $elem->text() . "<br>";
//    }

//    запись в файл
//    file_put_contents('./спизженыйТекст.txt', $elems->text());

//    парсинг картинок
//    $allText = $elems->text();
//    $pattern = '/https:\/\/[^\s]+/';
//    $lines = explode("\n", $allText);
//
//    foreach($lines as $line) {
//        if(preg_match($pattern, $line)) {
//            echo $line . '\n';
//        }
//    }

// парсинг картинок
//     $images = $elems->find('img');
//
//     if(empty($images)){
//         echo 'нет картинок';
//     } else {
//         foreach ($images as $image) {
//             echo $image->attr('src') . "<br>";
//         }
//     }

// парсинг css файлов
//    echo $elems->text();
//    $data = file_get_contents()

// парсинг с авторизацией

$loginPageUrl = 'https://home.openweathermap.org/users/sign_in';

// Инициализация cURL для получения страницы входа
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $loginPageUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // Следовать за редиректами
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 12_6_8) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
    'Referer: ' . $loginPageUrl,
    'Content-Type: application/x-www-form-urlencoded'
]);
$response = curl_exec($ch);
curl_close($ch);

// Проверка ответа
if (!$response) {
    die('Не удалось получить страницу входа.');
}

// Создание объекта Document на основе полученного ответа
$loginPage = new Document($response);

// Извлечение скрытых полей
$hiddenFields = [];
foreach ($loginPage->find('input[type=hidden]') as $input) {
    $hiddenFields[$input->attr('name')] = $input->attr('value');
}
// Добавление данных авторизации
$loginData = array_merge($hiddenFields, [
    'user[email]' => 'k19-92@mail.ru',
    'user[password]' => '833190692',
    'user[remember_me]' => '0',
    'commit' => 'Submit',
]);

echo '<pre>';
print_r($loginData);
echo '</pre>';

// Отправка POST-запроса для авторизации
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $loginPageUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($loginData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // Следовать за редиректами
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, как Gecko) Chrome/91.0.4472.124 Safari/537.36',
    'Referer: ' . $loginPageUrl,
    'Origin: https://home.openweathermap.org',
    'Content-Type' => 'application/x-www-form-urlencoded'
]);
$response = curl_exec($ch);

// Проверка на ошибки cURL
if (curl_error($ch)) {
    echo 'Ошибка cURL: ' . curl_error($ch);
    curl_close($ch);
    exit;
}

curl_close($ch);

// URL защищенной страницы
$protectedUrl = 'https://home.openweathermap.org/home';

// Использование полученных куки для доступа к защищенной странице
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $protectedUrl);
//curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($loginData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // Следовать за редиректами
//curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
    'Referer: ' . $protectedUrl,
    'Content-Type' => 'application/x-www-form-urlencoded'
]);
$response = curl_exec($ch);
curl_close($ch);

// Отображение ответа сервера
echo 'Ответ сервера (защищенная страница): ' . $response;
?>
