<?php 

/**
* class Router
*/

class Router {

	private $routes;
	
	// считывание и сохранение маршрутов в $this->routes
	public function __construct () {
		$routesPath = ROOT.'/config/routes.php';
		$this->routes = include($routesPath);
	}

	// Получение строки запроса 
	private function getUri () {
		if (!empty($_SERVER['REQUEST_URI'])) {
			return trim($_SERVER['REQUEST_URI'], '/');
		}
	}

	// Обработка пути
	public function run () {

		$uri = $this->getUri();
		$i = 0; 

		foreach ($this->routes as $uriPattern=>$path) { // 'contacts' => 'site/contact'
			$i++;
			
			$arrUri = explode('/', $uri);
			$arrUriPatt = explode('/', $uriPattern);

			// Сравнение всей части паттерна роута и запроса
			//print_r(preg_match("~$uriPattern~", $uri));
			if (preg_match("~$uriPattern~", $uri)) {
				// Поллучение параметров из строки запроса
				$internalRoute = preg_replace("~$uriPattern~", $path, $uri); 
				// определение контроллера, экшена для обработки запроса, параметров
				$segments = explode('/', $internalRoute); // разделить по /
				//echo '<pre>';print_r($segments);echo '</pre>';
				// array_shift извлекает первое значение массива, возвращая его
				$controllerName = array_shift($segments).'Controller';
				// ucfirst первый символ строки заглавный
				$controllerName = ucfirst($controllerName);

				$actionName = 'action'.ucfirst(array_shift($segments));
				$actionParam = $segments;

				// echo 'Контроллер: '.$controllerName.'<br>Экшен: '.$actionName.'<br>';
				// echo '<pre>';print_r($actionParam);echo '</pre>';

				// Подключение файла класса контроллера 
				$controllerFile = ROOT.'/controllers/'.$controllerName.'.php';
				if (file_exists($controllerFile)) include_once($controllerFile);
				else exit('Отсутствует контроллер: '.$controllerName);

				// Создание объекта
				$controllerObject = new $controllerName;

				// Проверка на наличие метода $actionName в классе $controllerObject
				if (method_exists($controllerObject, $actionName)) {
					// Вызов у объекта $controllerObject метода $actionName() и передача параметров вметод как переменных $actionName($var1, $varN, ...)
					$result = call_user_func_array(array($controllerObject, $actionName), $actionParam);
					if ($result != null) break;
				// если нет метода в классе - вывод 404
				} else $this->run404();
				
			// если роут отсутствует для введённого адреса - вывод 404
			} elseif ($i == count($this->routes)) $this->run404();
		}
	}

	private function run404 () {
		include_once(ROOT.'/controllers/SiteController.php');
		$controllerObject = new SiteController;
		$controllerObject->action404();
	}
}

?>