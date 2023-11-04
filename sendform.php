<?php

// Токен
//const TOKEN = '6024137895:AAEUHi1MLBvXxa05qyVkIdMomqRWuco6exU'; - бот №1
const TOKEN = '6639941736:AAFWgrhLxdkyM3q5PT5Fn-O7cABso7Adwe0';

// ID чата
//const CHATID = '-1001922578470'; - бот №1
const CHATID = '-1001919224556';

// Массив допустимых значений типа файла.
$types = array('image/gif', 'image/png', 'image/jpeg', 'application/pdf');

// Максимальный размер файла в килобайтах
// 1048576; // 1 МБ
$size = 1073741824; // 1 ГБ

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$fileSendStatus = '';
	$textSendStatus = '';
	$msgs = [];

	/*echo "<pre>";
	print_r(json_encode($_POST));
	echo "</pre>";
	return;*/

	// Проверяем не пусты ли поля с именем и телефоном
	if (!empty($_POST['name']) && !empty($_POST['phone']) && !empty($_POST['region']) && !empty($_POST['city']) && !empty($_POST['raion']) && !empty($_POST['poshta'])) {

		// Если не пустые, то валидируем эти поля и сохраняем и добавляем в тело сообщения. Минимально для теста так:
		$txt = "";

		if (isset($_POST['productData']) && !empty($_POST['productData'])) {
			foreach (json_decode($_POST['productData']) as $product) {
				$txt .= "Товар: " . $product->name . "%0A" . 'смак: ' . $product->option . "%0A" . 'кількість: ' . $product->amount . '%0A%0A';
			}
		}

		// Ім'я
		if (isset($_POST['name']) && !empty($_POST['name'])) {
			$txt .= "Ім'я: " . strip_tags(trim(urlencode($_POST['name']))) . "%0A";
		}

		// Призвіще
		if (isset($_POST['surname']) && !empty($_POST['surname'])) {
			$txt .= "Призвіще: " . strip_tags(trim(urlencode($_POST['surname']))) . "%0A";
		}

		// Номер телефона
		if (isset($_POST['phone']) && !empty($_POST['phone'])) {
			$txt .= "Телефон: " . strip_tags(trim(urlencode($_POST['phone']))) . "%0A";
		}

		if (isset($_POST['payment']) && !empty($_POST['payment'])) {
			$txt .= "Оплата: " . strip_tags(trim(urlencode($_POST['payment']))) . "%0A";
		}

		if (isset($_POST['region']) && !empty($_POST['region'])) {
			$txt .= "Область: " . strip_tags(urlencode($_POST['region']))  . "%0A";
		}

		if (isset($_POST['city']) && !empty($_POST['city'])) {
			$txt .= "Місто: " . strip_tags(urlencode($_POST['city']))  . "%0A";
		}

		if (isset($_POST['raion']) && !empty($_POST['raion'])) {
			$txt .= "Район: " . strip_tags(urlencode($_POST['raion']))  . "%0A";
		}

		if (isset($_POST['poshta']) && !empty($_POST['poshta'])) {
			$txt .= "Пошта: " . strip_tags(urlencode($_POST['poshta']))  . "%0A%0A";
		}

		if (isset($_POST['sum']) && !empty($_POST['sum'])) {
			$txt .= "Сума: " . strip_tags(urlencode($_POST['sum']))  . "%0A";
		}

		$url = 'https://api.telegram.org/bot' . TOKEN . '/sendMessage?chat_id=' . CHATID . '&parse_mode=html&text=' . $txt;

		$file = fopen($_SERVER['DOCUMENT_ROOT'] . '/log.txt', 'a');
		fwrite($file, date('Y-m-d G:i:s') . ' - ' . print_r($url, true) . "\n");
		fclose($file);

		$textSendStatus = @file_get_contents($url);
		$file = fopen($_SERVER['DOCUMENT_ROOT'] . '/log.txt', 'a');
		fwrite($file, date('Y-m-d G:i:s') . ' - ' . print_r(json_decode($textSendStatus), true) . "\n");
		fclose($file);

		if (isset(json_decode($textSendStatus)->{'ok'}) && json_decode($textSendStatus)->{'ok'}) {
			if (!empty($_FILES['files']['tmp_name'])) {

				$urlFile =  "https://api.telegram.org/bot" . TOKEN . "/sendMediaGroup";

				// Путь загрузки файлов
				$path = $_SERVER['DOCUMENT_ROOT'] . '/telegramform/tmp/';

				// Загрузка файла и вывод сообщения
				$mediaData = [];
				$postContent = [
					'chat_id' => CHATID,
				];

				for ($ct = 0; $ct < count($_FILES['files']['tmp_name']); $ct++) {
					if ($_FILES['files']['name'][$ct] && @copy($_FILES['files']['tmp_name'][$ct], $path . $_FILES['files']['name'][$ct])) {
						if ($_FILES['files']['size'][$ct] < $size && in_array($_FILES['files']['type'][$ct], $types)) {
							$filePath = $path . $_FILES['files']['name'][$ct];
							$postContent[$_FILES['files']['name'][$ct]] = new CURLFile(realpath($filePath));
							$mediaData[] = ['type' => 'document', 'media' => 'attach://' . $_FILES['files']['name'][$ct]];
						}
					}
				}

				$postContent['media'] = json_encode($mediaData);

				$curl = curl_init();
				curl_setopt($curl, CURLOPT_HTTPHEADER, ["Content-Type:multipart/form-data"]);
				curl_setopt($curl, CURLOPT_URL, $urlFile);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $postContent);
				$fileSendStatus = curl_exec($curl);
				curl_close($curl);
				$files = glob($path . '*');
				foreach ($files as $file) {
					if (is_file($file))
						unlink($file);
				}
			}
			echo json_encode('SUCCESS');
		} else {
			echo json_encode('ERR');
			// 
			// echo json_decode($textSendStatus);
		}
	} else {
		echo json_encode('NOTVALID');
	}
} else {
	header("Location: /");
}
