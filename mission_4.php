<?php
    $dsn = 'データベース名'; //データベースへの接続を行う。
    $user = 'ユーザー名';
    $dbPassword = 'パスワード';
    $pdo = new PDO($dsn,$user,$dbPassword);
    
    $sql = "CREATE TABLE mission_4" //データベース内にテーブル作成コマンド("CREATE TABLE")で、テーブルを作成する。
    ."("
    ."id INT,"
    ."name char(32),"
    ."comment TEXT,"
    ."date TEXT,"
    ."password varchar(20)"
    .")";
    $stmt = $pdo -> query($sql);
    
    // 編集したいカラムを投稿フォームに表示する文
    if(!empty($_POST['edit'])){
        if(!empty($_POST['ePassword']) && $_POST['ePassword'] == "intern"){
            
            $edit = $_POST['edit'];
            $ePassword = $_POST['ePassword'];
                        
            $sql = 'SELECT * FROM mission_4 ORDER BY id';  //入力したデータをSELECTによって表示する。
            $result = $pdo -> query($sql);
            
            foreach($result as $row){
                if($row['id'] == $edit){
                    $editName = $row['name'];
                    $editComment = $row['comment'];
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
<meta charset = "UTF-8">
<title>ミッション４</title>
</head>
<body>

    <!-入力フォーム・ボタンの作成->
    <form action="mission_4.php" method="POST">
        <!-名前を入力するフォーム->
        <input type="text" name="name" placeholder="名前" value="<?php echo $editName ?>"><br>
        <!-コメントを入力するフォーム->
        <input type="text" name="comment" placeholder="コメント" value="<?php echo $editComment ?>"><br>
        <!-編集したい投稿番号を裏で表示するフォーム->
        <input type="hidden" name="editNum" value="<?php echo $edit; ?>" >
        <!-パスワードを入力するフォーム->
        <input type="text" name="password" placeholder="パスワード" >
        <!-送信ボタン->
        <input type="submit" value="送信"><br>
        
        <!-削除対象番号を入力するフォーム->
        <br><input type="text" name="delete" placeholder="削除対象番号" value=""><br>
        <!-パスワードを入力するフォーム->
        <input type="text" name="dPassword" placeholder="パスワード">
        <!-削除ボタン->
        <input type="submit" value="削除"><br>
        
        <!-編集対象番号を指定するフォーム->
        <br><input type="text" name="edit" placeholder="編集対象番号" value=""><br>
        <!-パスワードを入力するフォーム->
        <input type="text" name="ePassword" placeholder="パスワード" value="">
        <!-編集ボタン->
        <input type="submit" value="編集">
        <?php
        echo $ecall; 
        echo $pcall;
        ?>
    </form>
    
<?php 
    // 投稿を行う文
    if(!empty($_POST['name']) && !empty($_POST['comment']) && empty($_POST['editNum'])){  
        if(!empty($_POST['password']) && $_POST['password'] == "intern"){  /*名前とコメントが入力された時、編集番号が未入力である時、そしてパスワード"intern"が入力された時に、下記の動作を行う*/
            
            $sql = 'SELECT * FROM mission_4 ORDER BY id';
            $result = $pdo -> query($sql);
            $count=0;
            $id=0;
            foreach($result as $row){
                $count = $row['id'];
            }
            $id = $count + 1;
            
            $sql = $pdo -> prepare("INSERT INTO mission_4(id, name, comment, date, password) VALUES(:id, :name, :comment, :date, :password)");  //作成したテーブルにINSERTを行ってデータを入力する。
            $sql -> bindValue(':id', $id, PDO::PARAM_INT);
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':date', $date, PDO::PARAM_STR);
            $sql -> bindParam(':password', $password, PDO::PARAM_STR);
            
            $name = $_POST['name'];
            $comment = $_POST['comment'];
            $date = date("Y/m/d/ H:i:s");
            $password = $_POST['password'];
            $sql -> execute();
        }
    }
        
    // カラムの編集を行う文
    if(!empty($_POST['editNum'])){
        $id = $_POST['editNum'];
        $name = $_POST['name'];
        $comment = $_POST['comment'];
        $date = date("Y/m/d/ H:i:s");
        $ePassword = $_POST['password'];
        
        $sql = 'SELECT * FROM mission_4 ORDER BY id';
        $result = $pdo -> query($sql);
        
        foreach($result as $row){
            if($row['id'] == $id && $row['password'] == $ePassword){
                $sql = "UPDATE mission_4 set name='$name', comment='$comment', date='$date' where id=$id ";
                $result = $pdo -> query($sql);
            }
        }
    }
    
    // カラムの削除を行う文
    if(!empty($_POST['delete'])){
        if(!empty($_POST['dPassword']) && $_POST['dPassword'] == "intern"){            
            $id = $_POST['delete'];  //削除フォームの番号        
            $sql = "DELETE from mission_4 where id = $id";  //カラムを削除フォームの番号（$id）に基づき、DELETEによって削除する。
            $result = $pdo -> query($sql);                
        }
    }
    
    $sql = 'SELECT * FROM mission_4 ORDER BY id';  //新規投稿・削除・編集などを行なったデータを、SELECT * FROM〜で表示する。
    $result = $pdo -> query($sql);
    foreach($result as $row){
        echo $row['id'].'：';
        echo $row['name'].' ';
        echo $row['comment'].' ';
        echo $row['date'].'<br>';
    }

?>    

</body>
</html>