<?php 

$con=@mysqli_connect('localhost','root','root','demo2');

if (!$con) {
    exit('<h1>数据库连接失败</h1>');
}

$query=mysqli_query($con,'select * from users');

if (!$query) {
    exit('<h1>数据库查询失败</h1>');
}


 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>管理系统</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <style type="text/css" media="screen">
        body {
            padding-top: 3.5rem;
        }
        main {
            padding-top: 2rem;
        }
        .table td img {
            height: 60px;
        }
        .table td,
        .table th {
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand navbar-dark bg-dark fixed-top">
        <a href="#" class="navbar-brand">管理系统</a>
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
        <h1 class="heading">用户管理
            <a href="add.php" class="btn btn-link">添加</a>
        </h1>
            <table class="table table-hover text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>头像</th>
                        <th>姓名</th>
                        <th>性别</th>
                        <th>年龄</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row=mysqli_fetch_assoc($query)) : ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><img src="<?php echo $row['avator']; ?>" alt="<?php echo $row['name']; ?>"></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['gender']==0?'♀':'♂'; ?></td>
                            <td><?php echo $row['birthday']; ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">编辑</a>
                                <a href="del.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">删除</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
    </main>
</body>
</html>