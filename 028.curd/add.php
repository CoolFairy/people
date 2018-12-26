<?php 

function add_user(){
    if (empty($_POST['name'])) {
        $GLOBALS['error_message']='请输入姓名';
        return;
    }

    if (empty($_POST['birthday'])) {
        $GLOBALS['error_message']='请输入出生日期';
        return;
    } 
    if (!(isset($_POST['gender']))&&($_POST['gender']==1||($_POST['gender']==0))) {
        $GLOBALS['error_message']='请选择性别';
        return;
    }

    $name=$_POST['name'];
    $gender=$_POST['gender'];
    $birthday=$_POST['birthday'];


    if ($_FILES['avator']['name']==='') {
        $GLOBALS['error_message']='请上传头像';
        return;
    }

    $ext=pathinfo($_FILES['avator']['name'],PATHINFO_EXTENSION);

    $avator='../upload/avator'.uniqid().'.'.$ext;

    if (!move_uploaded_file($_FILES['avator']['tmp_name'], $avator)) {
        $GLOBALS['error_message']='上传头像失败';
        return;
    }
    $avator=substr($avator,2);

    $con=@mysqli_connect('localhost','root','root','demo2');

    if (!$con) {
        $GLOBALS['error_message']='数据库连接失败';
        return;
    }

    $sql='INSERT INTO `users` VALUES(null,\''.$avator.'\',\''.$name.'\','.$gender.',\''.$birthday.'\');';


    $query=mysqli_query($con,$sql);
    var_dump($query);

    if (!$query) {
        $GLOBALS['error_message'] = '查询失败！';
        return;
    }

    $affected_rows=mysqli_affected_rows($con);

    if ($affected_rows<=0) {
       $GLOBALS['error_message'] = '添加数据失败！';
        return;
    }


    header('Location: index.php');

}



if ($_SERVER['REQUEST_METHOD']==='POST') {
        add_user();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>信息系统</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <style type="text/css">
        body {
            padding-top: 3.5rem;
        }
        main {
            padding-top: 2rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="#">管理系统</a>
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a href="index.php" class="nav-link">用户管理</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">商品管理</a>
            </li>
        </ul>
    </nav>
    <main class="container">
        <h1 class="heading">添加用户</h1>
        <?php if (isset($GLOBALS['error_message'])): ?>
            <div class="alert alert-danger">
                <?php echo $GLOBALS['error_message']; ?>
            </div>
        <?php endif ?>
        
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="avator">头像</label>
                <input type="file" name="avator" id="avator" class="form-control" />
            </div>
            <div class="from-group">
                <label for="name">姓名</label>
                <input type="text" name="name" id="name" class="form-control" />
            </div>
            <div class="from-group">
                <label for="gender">性别</label>
                <select name="gender" id="gender" class="form-control">
                    <option value="-1">请选择性别</option>
                    <option value="1">男</option>
                    <option value="0">女</option>
                </select>
            </div>
            <div class="form-group">
                <label for="birthday">生日</label>
                <input type="date" name="birthday" id="birthday" class="form-control" />
            </div>
            <input type="submit" value="保存" class="btn btn-block btn-primary" />
        </form>
    </main>
</body>
</html>