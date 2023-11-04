﻿<?
//***************** Страница с завершением заказа ******************
session_start();
 
// формируем массив с товарами в заказе (если товар один - оставляйте только первый элемент массива)
$products_list = array(
    0 => array(
            'product_id' => '5',    //код товара (из каталога CRM)
            'price'      => '2535', //цена товара 1
            'count'      => '1',                     //количество товара 1
            // если есть смежные товары, тогда количество общего товара игнорируется

    ),

);
$products = urlencode(serialize($products_list));
$sender = urlencode(serialize($_SERVER));
$order_id = number_format(round(microtime(true)*10),0,'.','');
$_SESSION['order_id'] = $order_id;
// параметры запроса
$data = array(
    'key'             => 'e5c088c09b12c40fa4b82feaccbdd8a6', //Ваш секретный api токен
    'order_id'        => $order_id, //идентификатор (код) заказа (*автоматически*)
    'country'         => 'UA',                         // Географическое направление заказа
    'office'          => '7',                          // Офис (id в CRM)
    'products'        => $products,                    // массив с товарами в заказе
    'bayer_name'      => $_REQUEST['name'],            // покупатель (Ф.И.О)
    'phone'           => $_REQUEST['phone'],           // телефон
    'email'           => $_REQUEST['number'],           // электронка
    'comment'         => $_REQUEST['product_name'],    // комментарий
    'delivery'        => $_REQUEST['delivery'],        // способ доставки (id в CRM)
    'delivery_adress' => $_REQUEST['delivery_adress'], // адрес доставки
    'payment'         => '',                           // вариант оплаты (id в CRM)
    'sender'          => $sender,                        
    'utm_source'      => $_SESSION['utms']['utm_source'],  // utm_source
    'utm_medium'      => $_SESSION['utms']['utm_medium'],  // utm_medium
    'utm_term'        => $_SESSION['utms']['utm_term'],    // utm_term
    'utm_content'     => $_SESSION['utms']['utm_content'], // utm_content
    'utm_campaign'    => $_SESSION['utms']['utm_campaign'],// utm_campaign
    'additional_1'    => '',                               // Дополнительное поле 1
    'additional_2'    => '',                               // Дополнительное поле 2
    'additional_3'    => '',                               // Дополнительное поле 3
    'additional_4'    => ''                                // Дополнительное поле 4
);
 
// запрос
/*$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'http://soskitelki.lp-crm.biz/api/addNewOrder.html');///урл адрес срм, с https:// и до .biz или .top включительно
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
$out = curl_exec($curl);
curl_close($curl);*/
//$out – ответ сервера в формате JSON


$chat_id = '-4031260226';
$token = '6459298175:AAGlnehfK__oTLG_35d5VM-pNrEAHLE2sHk';

$fnameFieldset = "Ім'я: ";
$phoneFieldset = "Телефон: ";
$regionFieldset = "Область: ";
$raionFieldset = "Район: ";
$cityFieldset = "Місто/село: ";
$poshtaFieldset = "Пошта: ";

$priceFieldset = "Ціна: ";
$nameFieldset = "Товар: ";
$amountFieldset = "Смак: ";
$optionFieldset = "К-ть: ";





$fname = $_REQUEST['fname'];
$phone = $_REQUEST['phone'];
$region = $_REQUEST['region'];
$raion = $_REQUEST['raion'];
$city = $_REQUEST['city'];
$poshta = $_REQUEST['poshta'];

$price = $_REQUEST['price'];
$name = $_REQUEST['name'];
$amount = $_REQUEST['amount'];
$option = $_REQUEST['option'];

$arr = array(
    'Нове Замовлення! ' => '',
    $fnameFieldset => $_REQUEST['fname'],
    $phoneFieldset => $_REQUEST['phone'],
    $regionFieldset => $_REQUEST['region'],
    $raionFieldset => $_REQUEST['raion'],
    $cityFieldset => $_REQUEST['city'],
    $poshtaFieldset => $_REQUEST['poshta'],

    $priceFieldset => $_REQUEST['price'],
    $nameFieldset => $_REQUEST['name'],
    $amountFieldset => $_REQUEST['amount'],
    $optionFieldset => $_REQUEST['option'],
    'Сайт: ' => 'tomsmok.com.ua',
);


foreach ($arr as $key => $value) {
    $txt .= "<b>" . $key . "</b> " . $value . "\n";
};
$txt = urlencode($txt);
$sendToTelegram = fopen("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$txt}&disable_web_page_preview=true", "r");
if ($sendToTelegram) {
    header("Location: form.php?name=$name&phone=$phone");
    echo '<p class="success">Дякуємо за відправку вашого повідомлення!</p>';
    return true;
} else {
    echo '<p class="fail"><b>Помилка. Повідомлення не відправлено!</b></p>';
}
























$to = "grisanesa@gmail.com"; // Ваш Электронный адрес
$product = "Щетка детская";
 
$name = stripslashes(htmlspecialchars($_POST['name']));
$phone = stripslashes(htmlspecialchars($_POST['phone']));
$comment = $_POST['comment'];

if(empty($name) || empty($phone) ) {
echo '<h1 style="color:red;">Пожалуйста заполните все поля</h1>';
echo '<meta http-equiv="refresh" content="2; url=http://'.$_SERVER['SERVER_NAME'].'">';
}
else{

$subject = 'Заказ товара - "'.$product.'"'; // заголовок письма
$sender="<anaitore32@gmail.com>"; // Адрес отправителя
$header="Content-type:text/plain;charset=utf-8\r\nFrom: {$sender}\r\n";

$message = "ФИО: {$name}\nКонтактный телефон: {$phone}\nАдрес доставки: {$delivery_adress}\nТовар: {$product}\n\nСайт продажи: {$_SERVER['HTTP_HOST']}\nВремя покупки: ".date("m.d.Y H:i:s")."\n\nИнформация о покупателе:\nIP покупателя: {$_SERVER['REMOTE_ADDR']}\nУстановленный язык: {$_SERVER['HTTP_ACCEPT_LANGUAGE']}\nБраузер и ОС: {$_SERVER['HTTP_USER_AGENT']}\nРеферер: {$_SESSION['server']['referer']}\n";
$success_url = 'form.php?name='.$name.'&phone='.$phone.'&comment='.$comment.'';

$verify = mail($to,$subject,$message,$header);
if ($verify == 'true'){
	echo("<script>document.location.href = '{$success_url}';</script>");
    //header('Location: '.);
    echo '<h1 style="color:green;">Поздравляем! Ваш заказ принят!</h1>';
    exit;
}
else
    {
    echo '<h1 style="color:red;">Произошла ошибка!</h1>';
    }
}
?>
