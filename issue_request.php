<?php
session_start(); 
$conn = mysqli_connect("localhost", "root", "", "library_ms");

$id=$_GET['id'];

$roll=$_SESSION['login'];

$sql="insert into record (RollNo,BookId) values ('$roll','$id')";

if($conn->query($sql) === TRUE)
{
echo "<script type='text/javascript'>alert('Request Sent to Admin.')</script>";
header( "Refresh:0.01; url=book.php", true, 303);
}
?>