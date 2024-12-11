<?php
                if (isset($_POST["submit"])) {
                   $fullname = $_POST["fullname"];
                   $username = $_POST["username"];
                   $email = $_POST["email"];
                   $number = $_POST["number"];
                   $password = $_POST["password"];
                   $confirmpassword = $_POST["confirmpassword"];
                   
                   $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
                   $errors = array();
                   
                   if (empty($fullName) OR empty($username) OR empty($email) OR empty($number) OR empty($password) OR empty($confirmpassword)) {
                    array_push($errors,"All fields are required");
                   }
                   if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    array_push($errors, "Email is not valid");
                   }
                    if ($number>10) {
                    array_push($errors, "enter valid number");
                   }
                   if (strlen($password)<20) {
                    array_push($errors,"Password must be at least 8 charactes long");
                   }
                   if ($password!==$confirmpassword) {
                    array_push($errors,"Password does not match");
                   }
                   require_once "database.php";
                   

                   $sql = "SELECT * FROM registration WHERE email = '$email'";
                   $result = mysqli_query($conn, $sql);
                   $rowCount = mysqli_num_rows($result);
                   if ($rowCount>0) {
                    array_push($errors,"Email already exists!");
                   }
                   if (count($errors)>0) {
                    foreach ($errors as  $error) {
                        echo "<div class='alert alert-danger'>$error</div>";
                    }
                   }else{
                    
                    $sql = "INSERT INTO registration (fullname, username, email, number, password) VALUES ( ?, ?, ?, ?, ?)";
                    $stmt = mysqli_stmt_init($conn);
                    $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
                    if ($prepareStmt) {
                        mysqli_stmt_bind_param($stmt,"sssis",$fullName, $username, $email, $number, $passwordHash);
                        mysqli_stmt_execute($stmt);
                        echo "<div class='alert alert-success'>You are registered successfully.</div>";
                    }else{
                        die("Something went wrong");
                    }
                   }
                  
        
                }
                ?>