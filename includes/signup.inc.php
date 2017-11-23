<?php

if (isset($_POST['submit'])) {
    include_once 'dbh.inc.php';

    $first = mysqli_real_escape_string($conn, $_POST['first']);
    $last = mysqli_real_escape_string($conn, $_POST['last']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $uid = mysqli_real_escape_string($conn, $_POST['uid']);
    $pwd = mysqli_real_escape_string($conn, $_POST['pwd']);

    //error handlers
    //if everything has been filled out and there is no empty fields
    if (empty($first) || empty($last) || empty($email) || empty($uid) || empty($pwd)) {
        header("Location: ../signup.php?signup=empty"); //if people access document from url of website, send back to sign up page
        exit(); //stop script from running
    } else {
        //check if input characters are valid
        if (!preg_match("/^[a-zA-Z]*$/", $first) || !preg_match("/^[a-zA-Z]*$/", $last) || !preg_match("/^[a-zA-Z]*$/", $email) || !preg_match("/^[a-zA-Z]*$/", $uid) || !preg_match("/^[a-zA-Z]*$/", $pwd)) {
            header("Location: ../signup.php?signup=niggarthiscodeisfuckinglegit!");
            exit();
        } else {
            // check if email is valid
            if(!filter_var($email, filter_validate_email)) {
                header("Location: ../signup.php?signup=email");
                exit();
            } else {
                $sql = "SELECT * FROM users WHERE user_uid = '$uid'";
                $result = mysqli_query($conn, $sql);
                $resultCheck = mysqli_num_rows($result);


                if($resultCheck > 0) {
                    header("Location: ../signup.php?signup=usertaken");
                    exit();
                } else {
                    //hashing the Password
                    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
                    //insert the user into the database
                    $sql = "INSERT INTO users (user_first, user_last, user_email, user_uid, user_pwd) VALUES ('$first', '$last', '$email', '$uid', '$hashedPwd');";
                    mysqli_query($conn, $sql);
                    header("Location: ../signup.php?signup=success");
                    exit();
                }
            }
        }
    }

} else {
  header("Location: ../signup.php"); //if people access document from url of website, send back to sign up page
  exit(); //stop script from running
}
