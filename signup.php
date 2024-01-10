<?php 
session_start();
include('includes/config.php');
error_reporting(0);
if(isset($_POST['signup']))
{
//code for captach verification
$errorm = null;
if ($_POST["vercode"] != $_SESSION["vercode"] OR $_SESSION["vercode"]=='')  {
        $errorm = "Incorrect verification code";
} 
else {    
//Code for student ID
$count_my_page = ("studentid.txt");
$hits = file($count_my_page);
$hits[0] ++;
$fp = fopen($count_my_page , "w");
fputs($fp , "$hits[0]");
fclose($fp); 
$StudentId= $hits[0];   
$fname=$_POST['fullanme'];
$mobileno=$_POST['mobileno'];
$email=$_POST['email']; 
$password=md5($_POST['password']); 
$status=1;
$sql="INSERT INTO  tblstudents(StudentId,FullName,MobileNumber,EmailId,Password,Status) VALUES(:StudentId,:fname,:mobileno,:email,:password,:status)";
$query = $dbh->prepare($sql);
$query->bindParam(':StudentId',$StudentId,PDO::PARAM_STR);
$query->bindParam(':fname',$fname,PDO::PARAM_STR);
$query->bindParam(':mobileno',$mobileno,PDO::PARAM_STR);
$query->bindParam(':email',$email,PDO::PARAM_STR);
$query->bindParam(':password',$password,PDO::PARAM_STR);
$query->bindParam(':status',$status,PDO::PARAM_STR);
$query->execute();
$lastInsertId = $dbh->lastInsertId();
if($lastInsertId)
{
echo '<script>alert("Your Registration successfull and your student id is  "+"'.$StudentId.'")</script>';
echo"<script> document.location ='index.php'; </script>";
}
else 
{
echo "<script>alert('Something went wrong. Please try again');</script>";
}
}
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
    <title>E-Library | Student Signup</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

<style type="text/css">
    .fromerror{
        color:#af4242;
        font-size: 12px;
    }

</style>
    <?php
        if($errorm != null){
            ?><style>.errorm{display: block;}</style><?php
        }
    ?>

</head>
<body>
    <!------MENU SECTION START-->
<?php include('includes/header.php');?>
<!-- MENU SECTION END-->
    <div class="content-wrapper">
         <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">User Signup</h4>
                
                            </div>

        </div>
             <div class="row">
           
<div class="col-md-9 col-md-offset-1">
               <div class="panel panel-danger">
                        <div class="panel-heading">
                           SINGUP FORM
                        </div>
                        <div class="panel-body">
            <form name="signup" method="post"  onSubmit="return validForm();">
<div class="form-group" id="fullnm">
<label>Enter Full Name</label><br>
<span class="fromerror"><b></b></span>
<input class="form-control" type="text" name="fullanme" autocomplete="off" required />
</div>


<div class="form-group" id="mobile">
<label>Mobile Number :</label><br>
<span class="fromerror"><b></b></span>
<input class="form-control" type="tel" name="mobileno" autocomplete="off" required />
</div>
                                        
<div class="form-group">
<label>Enter Email</label>
<span class="fromerror"><b></b></span>
<input class="form-control" type="email" name="email" id="emailid" onBlur="checkAvailability()"  autocomplete="off" required  />
   <span id="user-availability-status" style="font-size:12px;"></span> 
</div>

<div class="form-group" id="pass">
<label>Enter Password</label><br>
<span class="fromerror"><b></b></span>
<input class="form-control" type="password" name="password" autocomplete="off" required  />
</div>

<div class="form-group" id="confirmpass">
<label>Confirm Password </label><br>
<span class="fromerror"><b></b></span>
<input class="form-control"  type="password" name="confirmpassword" autocomplete="off" required  />
</div>
 <div class="form-group">
<label>Verification code : </label><br>
<span class="fromerror"><b><?php echo $errorm ?></b></span><br>
<input type="text"  name="vercode" maxlength="5" autocomplete="off" required style="width: 150px; height: 25px;" />&nbsp;<img src="captcha.php">
</div>                                
<button type="submit" name="signup" class="btn btn-danger" id="submit">Register Now </button>

                                    </form>
                            </div>
                        </div>
                            </div>
        </div>
    </div>
    </div>
     <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php');?>
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
      <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
    <script>
    function clearErrors(){
        errors=document.getElementsByClassName('fromerror');
        for(let item of errors)
        {    item.innerHTML="";}
    }

    function seterror(id,error){
        //sets error inside tag of id
        element=document.getElementById(id);
        element.getElementsByClassName('fromerror')[0].innerHTML=error;
    }

    function validForm(){
        var returnval=true;
        clearErrors();

        var name=document.forms['signup']["fullanme"].value;
        if(name.length<4){
            seterror("fullnm","Length of name is too short");
            returnval=false;
        }

        var phone=document.forms['signup']["mobileno"].value;
        if(phone.length!=10){
            seterror("mobile","Enter valid phone number");
            returnval=false;
        }
        if(isNaN(phone)){
            seterror("mobile","Only numbers are allowed");
            returnval=false;
        }

        var pass=document.forms['signup']["password"].value;
        if(pass.length<6){
            seterror("pass","Password must be atleast 6 characters");
            returnval=false;
        }

        var cpass=document.forms['signup']["confirmpassword"].value;
        if(cpass!=pass){
            seterror("confirmpass","Password and confirm password should be matched");
            returnval=false;
        }

        return returnval;
    }

        function checkAvailability() {
            jQuery.ajax({
            url: "check_availability.php",
            data:'emailid='+$("#emailid").val(),
            type: "POST",
            success:function(data){
            $("#user-availability-status").html(data);
            },
            error:function (){}
            });
        }

</script>   
</body>
</html>
