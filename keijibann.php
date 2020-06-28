<!DOCTYPE html>
<html lang = "ja">
	<head>
		<meta charset="utf-8" />
		<style>
			body{
				background-color: #ffbeda;
			}
		</style>
		<title>掲示板</title>
	</head>
<body>
<form action = "keijibann.php" method = "post">


<?php

/*
$dsn = 'mysql:dbname=test_db;host=localhost';

$user = 'root';

$password = 'root';

$pdo = new PDO($dsn,$user,$password); //3-1 データベースへ接続


$sql = "CREATE TABLE tbKeijibann"

."("
."id INT,"
."name char(32),"
."comment TEXT,"
."date date,"
."time time"
.");";

$stmt = $pdo->query($sql); //　テーブル作成


$sql = $pdo->prepare("INSERT INTO tbtest (id,name,comment,dt,tm)VALUES (:id,:name,:comment,:dt,:tm)");

$sql->bindParam(':id',$id,PDO::PARAM_STR);

$sql->bindParam(':name',$name,PDO::PARAM_STR);

$sql->bindParam(':comment',$comment,PDO::PARAM_STR);

$sql->bindParam(':dt',$dt,PDO::PARAM_STR);

$sql->bindParam(':tm',$tm,PDO::PARAM_STR);

*/

$filename = 'testdata.txt';
$fp = fopen($filename,'a+');	// testdata.txtのファイルを開く、なければ新規作成
$num = count(file($filename))+1;
$pass = $_POST["pass"]; //パスワードの取得
$name = $_POST["name"];

$comment = $_POST["comment"];
$date = date("Y/m/d H:i:s");
$contents = $date."<>".$num."<>".$name."<>".$comment."<>".$pass."<>";

$lines = file($filename);// 　file関数でテキストファイル読み込み

// 名前とコメントとパスワードが入力されるとファイルに書き込まれる（editcon以外）
if(!empty($name) && !empty($comment) && empty($_POST["editcon"]) && !empty($pass)){
	fwrite($fp,$contents."\n");
	fclose($fp);
}

if(!empty($_POST["editcon"])){ //editconが入力されていると更新
	$newname = $_POST["name"];
	$newcom = $_POST["comment"];

	$fp = fopen($filename, 'r+');
	ftruncate($fp,0);

	foreach($lines as $line){ 
		$content = explode("<>", $line); // array([0] => $date, [1] => $num ...)を繰りかえす
		if($content[1] !=  $_POST["editcon"]){ // $numとeditconを比較し、一致しないものはそのまま書き込む
			$fp = fopen($filename, 'a');
			fwrite($fp, $line);
			fclose($fp);
		}else{					//それ以外（一致するもの）は更新する
			$fp = fopen($filename, 'a');
			fwrite($fp, $date."<>".$_POST["editcon"]."<>".$newname."<>".$newcom."<>".$content[4]."\n");
			fclose($fp);
		}
	}
}

if(isset($_POST["editnum"]) && isset($_POST["editpass"])){ //編集番号入力時の分岐
	$editnum = $_POST["editnum"];
	$editpass = $_POST["editpass"];


       	foreach($file as $val){
		$content = explode("<>",$val);
		
		if($content[1] == $editnum){

			if($content[4] == $editpass){
			$sample = explode("<>",$val);
			}
		}
	}
	if($content[1] == $editnum && $content[4] != $editpass){
	echo"パスワードが違います"."<br/>";
	}

} 




if(!empty($_POST["delnum"]) && !empty($_POST["dpass"])){ //削除フォームが埋まっているか確認

	$delnum	= $_POST["delnum"];
	$delpass = $_POST["dpass"];
	ftruncate($fp, 0);// ファイルをいったん空にする

	foreach($lines as $line){
		$content = explode("<>",$line);

		
		if($content[4] == $delpass){// データと同じパスワードの場合の分岐
			
			foreach($lines as $line){
				$content = explode("<>", $line);
				
				if($content[1] != $delnum){// データとid番号が一致しない場合に書き込む
					$fp = fopen($filename, 'a');
					fwrite($fp, $line);
					fclose($fp);
				}
			}
		}
		if($content[1] == $delnum && $content[4] != $delpass){// パスワードが正しいか参照
			echo "パスワードが違います"."<br/>";
		}
	}
}




?>
<!- 掲示板書き込みフォーム ->

<input type = "text"name = "name"placeholder="名前"value = <?php if(isset($sample) && $sample[1] == $editnum){echo $sample[2];}?>><br/><!-編集の番号が一致したらここに該当番号の名前を入力->
<input type = "text"name = "comment"placeholder="コメント"value = <?php if(isset($sample) && $sample[1] == $editnum){echo $sample[3];}?>><br/><!-該当番号のコメントを入力->
<input type = "hidden"name = "editcon"value = <?php if (isset($sample) && $sample[1] == $editnum){echo $sample[1];}?>>
<input type = "text"name = "pass"placeholder = "パスワード" ><br/>
<input type = "submit"value="送信"><br/>

<!- 　削除フォーム（番号、パスワード） ->


<input type = "text"name = "delnum"placeholder="削除番号"><br/>
<input type = "text"name = "dpass"placeholder = "パスワード"><br/>
<input type = "submit"value = "削除"><br/>


<!-　編集フォーム（番号、パスワード） ->


<input type = "text"name = "editnum"placeholder = "編集"><br/>
<input type = "text"name = "editpass"placeholder = "パスワード"><br/>
<input type = "submit"value = "編集"><br/>
<hr><br/>

<?php

$lines = file($filename); 

foreach($lines as $val){
	$content = explode("<>",$val); //投稿内容の表示（ループ）

	echo "<hr noshade>";
	echo $content[0]." -- ";
	echo $content[1]."<br/>";
	echo $content[2]."　";
	echo $content[3]."　"."<br/>";

}

?>


</form>
</body>
</html>

