<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	if (!empty($_COOKIE['save'])) {
		setcookie("save", '', time() - 60 * 60 * 24);
		$fheader =  "<div class='form__container form__container_good'><span class='form__span'>Ваши данные отправленны!</span></div>";
	} elseif (!empty($_COOKIE['request-error'])) {
		setcookie("request-error", '', time() - 60 * 60 * 24);
		$fheader =  "<div class='form__container form__container_err'><span class='form__span'>Что-то пошло не так! =(</span></div> ";
	} else {
		$fheader =  "<div class='form__contaner'><span class='form__span form__span_header'>ЗАПОЛНИТЕ</span></div>";
	}

	$message = array();
	checkCookies('name', $message);
	checkCookies('email', $message);
	checkCookies('year', $message);
	checkCookies('gender', $message);
	checkCookies('numlimbs', $message);
	checkCookies('super-powers', $message);
	checkCookies('super-powers-1', $message);
	checkCookies('super-powers-2', $message);
	checkCookies('super-powers-3', $message);
	checkCookies('biography', $message);


	include_once("form.php");
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$requestError = false;
	if (!empty($_POST)) {
		if (empty($_POST["name"])) {
			$errors['name'] = "Введите имя!";
		} elseif (!preg_match("/^\s*[a-zA-Zа-яА-Я'][a-zA-Zа-яА-Я-' ]+[a-zA-Zа-яА-Я']?\s*$/u", $_POST["name"])) {
			$errors['name'] = "Несуществующее имя!";
		}

		if (empty($_POST["email"])) {
			$errors['email'] = "Введите e-mail!";
		} elseif (!preg_match("/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/", $_POST["email"])) {
			$errors['email'] = "Несуществующий e-mail!";
		}

		if (empty($_POST["year"])) {
			$errors['year'] = "Выберите год!";
		} elseif (!preg_match("/^\s*[1]{1}9{1}\d{1}\d{1}.*$|^\s*200[0-8]{1}.*$/", $_POST["year"])) {
			$requestError = true;
		}

		if (!isset($_POST["gender"])) {
			$errors['gender'] = "Выберите пол!";
		} elseif (intval($_POST["gender"]) < 1 && 2 < intval($_POST["gender"])) {
			$requestError = true;
		}

		if (!isset($_POST["numlimbs"])) {
			$errors['numlimbs'] = "Выберите кол-во конечностей!";
		} elseif (intval($_POST["numlimbs"]) < 1 || 4 < intval($_POST["numlimbs"])) {
			$requestError = true;
		}

		if (!isset($_POST["super-powers"])) {
			$errors['super-powers'] = "Выберите хотя бы одну суперспособность!";
		} else {
			foreach ($_POST["super-powers"] as $value) {
				if (intval($value) < 1 || 3 < intval($value)) {
					$requestError = true;
					break;
				}
			}
		}

		if (empty($_POST["biography"])) {
			$errors['biography'] = "Расскажите что-нибудь о себе!";
		}
	} else {
		$requestError = true;
	}

	if ($requestError) {
		setcookie("request-error", '1', time() + 60 * 60 * 24);
		header("Location: index.php");
	} else {
		writeCookies('name', $errors);
		writeCookies('email', $errors);
		writeCookies('year', $errors);
		writeCookies('gender', $errors);
		writeCookies('numlimbs', $errors);
		writeCookies('biography', $errors);

		if (isset($errors['super-powers'])) {
			setcookie('super-powers-error', $errors['super-powers'], time() + 60 * 60 * 24);
		} else {
			$supPowers = ['1' => '0', '2' => '0', '3' => 0];
			foreach ($_POST['super-powers'] as $key => $value) {
				$supPowers[$value] = '1';
			}
			foreach ($supPowers as $key => $value) {
				setcookie("super-powers-$key", $value,  time() + 60 * 60 * 24 * 365);
			}
		}
	}

	if (isset($errors)) {
		header("Location: index.php");
		exit();
	}

	$name = $_POST["name"];
	$email = $_POST["email"];
	$year = intval($_POST["year"]);
	$gender = $_POST["gender"];
	$limbs = intval($_POST["numlimbs"]);
	$superPowers = $_POST["super-powers"];
	$biography = $_POST["biography"];

	$serverName = 'localhost';
	$user = "u41037";
	$pass = "3452345";
	$dbName = $user;

	$db = new PDO("mysql:host=$serverName;dbname=$dbName", $user, $pass, array(PDO::ATTR_PERSISTENT => true));

	$lastId = null;
	try {
		$stmt = $db->prepare("INSERT INTO user2 (name, email, date, gender, limbs, biography) VALUES (:name, :email, :date, :gender, :limbs, :biography)");
		$stmt->execute(array('name' => $name, 'email' => $email, 'date' => $year, 'gender' => $gender, 'limbs' => $limbs, 'biography' => $biography));
		$lastId = $db->lastInsertId();
	} catch (PDOException $e) {
		print('Error : ' . $e->getMessage());
		exit();
	}

	try {
		if ($lastId === null) {
			exit();
		}
		foreach ($superPowers as $value) {
			$stmt = $db->prepare("INSERT INTO user_power2 (id, power) VALUES (:id, :power)");
			$stmt->execute(array('id' => $lastId, 'power' => intval($value)));
		}
	} catch (PDOException $e) {
		print('Error : ' . $e->getMessage());
		exit();
	}
	$db = null;

	setcookie("save", '1', time() + 60 * 60 * 24);
	header("Location: index.php");
}

function checkCookies($name, &$message)
{
	if (!empty($_COOKIE[$name])) {
		$message[$name] = $_COOKIE[$name];
	} else {
		$message[$name] = '';
	}
	if (!empty($_COOKIE[$name . '-error'])) {
		$message[$name . '-error'] = "<div class='form__container form__container_err'><span class='form__span'>{$_COOKIE[$name . '-error']}</span></div>";
		setcookie($name . '-error', '', time() - 60 * 60 * 24);
	} else {
		$message[$name . '-error'] = '';
	}
}
function writeCookies($name, &$errors)
{
	if (isset($errors[$name])) {
		setcookie($name . '-error', $errors[$name], time() + 60 * 60 * 24);
	} else {
		setcookie($name, $_POST[$name], time() + 60 * 60 * 24 * 365);
	}
}