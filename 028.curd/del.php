<?php 
if(empty($_GET['id'])){
    exit('<h1>非法参数</h1>');
}
$id=$_GET['id'];

$con=@mysqli_connect('localhost','root','root','demo2');

if (!$con) {
    exit('<h1>数据库连接失败</h1>');
}
$query=mysqli_query($con,'delete from `users` where `id` in ('.$id.');');

if (!$query) {
    exit('<h1>数据库查询 失败</h1>');
}

$affected_rows=mysqli_affected_rows($con);

if ($affected_rows<=0) {
    exit('<h1>删除失败</h1>');
}
// exit(111);
header('Location: index.php');
