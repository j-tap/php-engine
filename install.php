<?php 
require_once('/components/Db.php');

$dbConfig = include('/config/db_params.php');
//$dbConfig['host'] 
//$dbConfig['dbname']
//$dbConfig['user']
//$dbConfig['password']

$aTables = array(
	'users' => array("id int(11) NOT NULL AUTO_INCREMENT,
	group_id int(11) NOT NULL DEFAULT '2',
	name varchar(128) NOT NULL,
	password varchar(50) NOT NULL,
	email varchar(50) NOT NULL,
	join_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	salt varchar(50) NOT NULL,
	code varchar(50) NOT NULL,
	active tinyint(1) NOT NULL DEFAULT '0',
	last_activity timestamp NOT NULL,
	isauth tinyint(1) NOT NULL,
	PRIMARY KEY (id)"),
	'user_groups' => array("id int(11) NOT NULL,
	name varchar(128) NOT NULL,
	code varchar(128) NOT NULL,
	PRIMARY KEY (id),
	UNIQUE KEY id (id, code)", array('id, name, code', array("1, 'Администратор', 'admin'", "10, 'Гость', 'guest'")))
);
$msg = '';
$aNeedTbl = array();

if (isset($_POST['install'])) {
	createTables($_POST['tables']);
	header('Location: '.$_SERVER['PHP_SELF']);
	die;
}

checkAllTables();


function checkAllTables () {
	global $aTables;
	global $aNeedTbl;
	global $msg;

	$msg .= 'Проверка...<br>';
	foreach ($aTables as $name => $fields) {
		if (isTableExist($name)) {
			$s = '<span class="text-success">установлена</span><br>';
		} else {
			$s = '<span class="text-warning">отсутствует</span><br>';
			array_push($aNeedTbl, $name);
		}
		$msg .= $name .' - '. $s;
	}
	$msg .= '<br>';
}

function createTables ($sTables) {
	global $aTables;
	global $msg;

	$aTbl = explode(',', $sTables);

	$msg .= 'Начало установки...<br>';
	$db = Db::getConnection();
	foreach ($aTbl as $name) {
		$fields = $aTables[$name][0];
		$msg .= 'Создание таблицы '. $name .'<br>';
		try {
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql ="CREATE TABLE IF NOT EXISTS $name ($fields) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
			$result = $db->prepare($sql);
			$result->execute();
			$msg .= 'успешно<br>';
		} catch(PDOException $e) {
			$msg .= '<span class="text-danger">ошибка: '. $e->getMessage() .'</span><br>';
		}

		if ($aTables[$name][1]) {
			$msg .= 'Добавление системных записей<br>';

			$aNotes = $aTables[$name][1];
			$sFields = $aNotes[0];
			$aRows = $aNotes[1];

			$sValues = '';
			foreach ($aRows as $row) {
				if (strlen($sValues)) $sValues .= ',';
				$sValues .= "($row)";
			}
			try {
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$sql = "INSERT INTO $name ($sFields) VALUES $sValues";
				$result = $db->prepare($sql);
				$result->execute();
			} catch(PDOException $e) {
				$msg .= '<span class="text-danger">ошибка: '. $e->getMessage() .'</span><br>';
			}
		}
	}
	$msg .= '<br>';
}
function isTableExist ($table) {
	try {
		$db = Db::getConnection();
		$result = $db->query("SELECT 1 FROM $table LIMIT 1");
	} catch (Exception $e) {
		return false;
	}
	return $result !== false;
}
?>

<?php include '/views/layouts/header.php'; ?>

<div class="container">
	<div class="row">
		<div class="col-sm-6 col-sm-offset-3">
			<h1 class="h2 text-center mb-10 ">Установка таблиц в базу данных <b><?php echo $dbConfig['dbname']; ?></b></h1>
			<div class="text-center mb-30">
				<?php if (count($aNeedTbl)) : ?>
					<h2 class="h3 text-warning mb-30">необходима установка</h2>
					<form action="" method="post">
						<input type="hidden" name="tables" value="<?php echo implode(',', $aNeedTbl); ?>">
						<input class="btn btn-default" value="Начать" name="install" type="submit">
					</form>
				<?php else: ?>
					<h2 class="h3 text-success mb-30">установка не требуется</h2>
					<span class="btn btn-default disabled">Начать</span>
				<?php endif; ?>
			</div>
			<p class="text-muted"><?php echo $msg; ?></p>
		</div>
	</div>
</div>

<?php include '/views/layouts/footer.php'; ?>

