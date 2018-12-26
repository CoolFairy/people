<?php
if($_SERVER['REQUEST_METHOD']==='POST'){
    edit_user();
}else{
    if(empty($_GET['id'])){//接收要修改数据的id
        exit('<h1>必须传入指定的参数</h1>');
    }
    $id = $_GET['id'];

    //建立连接
    $con = mysqli_connect('localhost', 'root', 'root', 'demo2');
    if(!$con){
        exit('<h1>连接数据库失败！</h1>');
    }

    // 查询
    $query = mysqli_query($con,'SELECT * FROM `users` WHERE `id`='.$id.' limit 1;');
    if(!$query){
        exit('<h1>数据库查询失败</h1>');
    }

    //首次出现的数据
    $user = mysqli_fetch_assoc($query);

    if(!$user){
        exit('<h1>找不到你要编辑的数据</h1>');
    }
}

function edit_user(){
    // 验证文本框非空
    if(empty($_POST['name'])){
        $GLOBALS['error_message']  = '请输入姓名';
        return;
    }
    //判断性别
    if(!(isset($_POST['gender']) && ($_POST['gender']==1 || $_POST['gender'] == 0) )){
        $GLOBALS['error_message'] = '请选择性别';
        return;
    }

    //验证日期
    if(empty($_POST['birthday'])){
        $GLOBALS['error_message'] = '请输入出生日期';
        return;
    }

    //取值
    $id= $_POST['id'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $birthday = $_POST['birthday'];


    //接收文件验证
    if($_FILES['avator']['name']===''){
        $GLOBALS['error_message'] = '请上传头像';
        return;
    }

    //后缀
    $ext = pathinfo($_FILES['avator']['name'],PATHINFO_EXTENSION);
    // exit($ext);
    $avator = '../uploads/avator/' . uniqid() . '.' . $ext;
    if(!move_uploaded_file($_FILES['avator']['tmp_name'], $avator)){
        $GLOBALS['error_message'] = '上传头像失败！';
        return;
    }
    $avator = substr($avator,2);


    // 1.建立连接
    $con = mysqli_connect('localhost', 'root', 'root', 'demo2');
    if(!$con){
        $GLOBALS['error_message'] = '连接数据库失败';
        return;
    }

    $sql = 'UPDATE `users` SET `name`=\''.$name.'\',`avator`=\''.$avator.'\',`gender`='.$gender.',`birthday`=\''.$birthday.'\' WHERE `id` ='.$id.';';
    $query = mysqli_query($con,$sql);
    if(!$query){
        $GLOBALS['error_message'] = '查询失败！';
        return;
    }

    $affected_rows =mysqli_affected_rows($con);
    if($affected_rows <= 0){
        $GLOBALS['error_message'] = '修改失败！';
    }

    // 响应
    header('Location: index.php');
}


?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8" />
    <title>xxx管理系统</title>
    <link rel="stylesheet" href="css/bootstrap.css" />
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
        <a class="navbar-brand" href="#">xxx管理系统</a>
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="index.php">用户管理</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">商品管理</a>
            </li>
        </ul>
    </nav>
    <main class="container">
        <h1 class="heading">编辑 “<?php echo $user['name']; ?>”</h1>
        <?php if (isset($GLOBALS['error_message'])): ?>
        <div class="alert alert-danger">
            <?php echo $GLOBALS['error_message']; ?>
        </div>
        <?php endif ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <input type="hidden" name="id" id="id" class="form-control" value="<?php echo $user['id']; ?>" />
            </div>
            <div class="form-group">
                <label for="avator">头像</label>
                <input type="file" name="avator" id="avator" class="form-control" />
            </div>
            <div class="from-group">
                <label for="name">姓名</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo $user['name']; ?>" />
            </div>
            <div class="from-group">
                <label for="gender">性别</label>
                <select name="gender" value="1" id="gender" class="form-control">
                    <option value="-1">请选择性别</option>
                    <option value="1"<?php echo $user['gender'] == 1? 'selected':''; ?>>男</option>
                    <option value="0"<?php echo $user['gender'] == 0? 'selected':''; ?>>女</option>
                </select>
            </div>
            <div class="form-group">
                <label for="birthday">生日</label>
                <input type="date" name="birthday" id="birthday" class="form-control" value="<?php echo $user['birthday']; ?>" />
            </div>
            <input type="submit" value="保存" class="btn btn-block btn-primary" />
        </form>
    </main>
</body>
</html>