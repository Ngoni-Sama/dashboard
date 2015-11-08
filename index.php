
<?php
header('Access-Control-Allow-Origin: http://127.0.0.2');
	
error_reporting(E_ALL);

/*
 * Here we use function for MySQL
 * function Request_data() {
$DB = new mysqli("localhost", "root", "", "wsn_db");
$result=$DB->query("SELECT `senstype`, `sensnodeid`, `sensvalue`, `datetime` FROM `sensors_values` ORDER BY `id` DESC LIMIT 10");
//$result=$DB->query("SELECT * FROM `sensors_values` ORDER BY `id` DESC LIMIT 30");

$i=0;
while($row=$result->fetch_row())
{
	$senstype[$i]=$row[0];
	$sensnode[$i]=$row[1];
	$sensvalue[$i]=$row[2];
	$datetime[$i]=$row[3];
	$i++;
}
$str='';
for($i=0;$i<count($senstype);$i++)
	{
	$str.=strval($senstype[$i]);
	if($sensnode[$i]<10)
		{
		$str.="0";
		$str.=strval($sensnode[$i]);
		}
	else 
		$str.=strval($sensnode[$i]);
	$str.='=';
	$str.=strval($sensvalue[$i]);
	$str.='&';
	}
echo $str;
}*/
function Request_data(){
$sqlite=new SQLite3('wsn_db.sqlite');

$values=$sqlite->query("SELECT `senstype`, `sensnodeid`, `sensvalue`, `datetime` FROM `sensors_values` ORDER BY `id` DESC LIMIT 10");
$i=0;
while($row=$values->fetchArray(SQLITE3_ASSOC))
{
	$senstype[$i]=$row["senstype"];
	$sensnode[$i]=$row["sensnodeid"];
	$sensvalue[$i]=$row["sensvalue"];
	$datetime[$i]=$row["datetime"];
	$i++;
}
$str='';
for($i=0;$i<count($senstype);$i++)
{
	$str.=strval($senstype[$i]);
	if($sensnode[$i]<10)
	{
		$str.="0";
		$str.=strval($sensnode[$i]);
	}
	else
		$str.=strval($sensnode[$i]);
	$str.='=';
	$str.=strval($sensvalue[$i]);
	$str.='&';
}
echo $str;
$sqlite->close();
}


function Request_weather() {

$city_id=36870; // id города
$data_file="http://export.yandex.ru/weather-ng/forecasts/$city_id.xml"; // адрес xml файла 

$xml = simplexml_load_file($data_file); // раскладываем xml на массив
echo "<div><b>Сейчас ". $xml->fact->temperature." &degC ".$xml->fact->weather_type."</b><br>";
echo "Завтра ".$xml->day[1]["date"].":<br>";
echo "Утро: ".$xml->day[1]->day_part[0]->temperature_from." - ".$xml->day[1]->day_part[0]->temperature_to." &degC ";//.$xml->day[1]->day_part[0]->weather_type."<br>";
echo "День: ".$xml->day[1]->day_part[1]->temperature_from." - ".$xml->day[1]->day_part[0]->temperature_to." &degC ";//.$xml->day[1]->day_part[1]->weather_type."<br>";
echo "Вечер: ".$xml->day[1]->day_part[2]->temperature_from." - ".$xml->day[1]->day_part[0]->temperature_to." &degC ";//.$xml->day[1]->day_part[2]->weather_type."<br>";
echo "Ночь: ".$xml->day[1]->day_part[3]->temperature_from." - ".$xml->day[1]->day_part[0]->temperature_to." &degC ";//.$xml->day[1]->day_part[3]->weather_type."<br></div>";
echo '<div style="width:48px; height:48px; background-image:url(public/images/weather/';
	switch($xml->fact->image["type"]){
		case "2":
			echo "rain.svg";
			break;
		default:
			echo "";
	}
echo ')"></div>';
}

function Save_comment() {
	$sqlite=new SQLite3('wsn_db.sqlite');
	$str='Insert into Notes ("note","date") VALUES ("'.$_POST["note"].'",datetime("now"))';
	$sqlite->query($str);
	$sqlite->close();

	header("Location: http://127.0.0.2/index.html");
	die();

}

function Request_comments() {
	$sqlite=new SQLite3('wsn_db.sqlite');
	$values=$sqlite->query("Select * from Notes Order by Date Desc");
	$i=0;
	while($row=$values->fetchArray(SQLITE3_ASSOC)) {
		echo "<form action=http://127.0.0.2/index.php?request=delete_comment method=POST>";
		$i++;
		echo "Comment ".$i." <div id=date".$i.">".$row["date"]."</div><br>";
		echo $row["note"] ."<br>";
		echo '<input name=date type=hidden value="'.$row["date"].'">';
		echo '<input type="submit" value="Удалить">';
		echo "</form>";
	}

	$sqlite->close();
}

function Delete_comment() {
	$sqlite=new SQLite3('wsn_db.sqlite');
	$str='Delete From Notes where date="'.$_POST["date"].'"';
	$sqlite->query($str);
	$sqlite->close();

	header("Location: http://127.0.0.2/index.html");
	die();
}

switch($_GET["request"]) {
	case "weather":
		Request_weather();
		break;
	case "sensors":
		Request_data();
		break;
	case "save_comment":
		Save_comment();
		break;
	case "comments":
		Request_comments();
		break;
	case "delete_comment":
		Delete_comment();
		break;
	default:
		break;
}
	
	

?>
