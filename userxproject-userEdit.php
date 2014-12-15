<?php

require 'config.php';
protected_area();

// Get list of existing users
$crud->table = 'user';

if (!isset($_GET['id'])) {
    redirect('dashboard.php');
}

$id = (int) $_GET['id'];

// Users can only edit themselves
// Only admin can edit other users
if ($_SESSION['type'] == 'user' && $id != $_SESSION['user_id']) {
    $_SESSION['message'] = 'You do not have authority to edit this user.';
    redirect('dashboard.php');
}



// Get a list of our existing user
$user = $crud->select('*', ['user_id' => $id]);
$login = $user[0]['login'];
$email = $user[0]['email'];

// Track any errors
$errors = [];
 
if (isset($_GET['save'])) {
    $login = $_POST['login'];
    $email = $_POST['email'];
    
    if (strlen($login) == 0) {
        $errors[] = 'Login is required';
    }
    
    if (strlen($email) == 0) {
        $errors[] = 'Email is required';
    }
    
    if (empty($errors)) {
        $result = $crud->update([
            'login' => $login,
            'email' => $email
        ], [
            'user_id' => $id
        ]);
        
        $result;
    }
}

if (isset($_GET['change_password'])) {
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];
    
    if (strlen($password) == 0) {
        $errors[] = 'Password is required';
    }
    
    if ($password != $confirm) {
        $errors[] = 'Password do not match';
    }

    if (empty($errors)) {
        $result = $crud->update([
            'password' => hash256($password),
        ], [
            'user_id' => $id
        ]);

        $result;
    }
    
}


?>

<!doctype html>
<html>
<body>
    <a href="indes.php?logout"> Logout </a>
        
    <h1> User Management </h1>
    
        <?php
        if(!empty($errors)) {
            echo "<ul>";
            foreach ($errors as $_value) {
                echo "<li>$_value</li>";
            }
            echo "</ul>";
        } 
        ?>
    
    <h3> Edit User </h3>
    
        <form method="post" action="?save&id=<?=$id?>">
            Login: <input type="text" name="login" value="<?=isset($login) ? $login : ' '?>" /> <br />
            Email: <input type="email" name="email" value="<?=isset($email) ? $email : ' '?>" /> <br />
            <input type="submit" value="Save" />
        </form>
        
    <h3> Change Password </h3>
    
         <form method="post" action="?change_password&id=<?=$id?>">
            Password: <input type="text" name="password" value="<?=isset($password) ? $password : ' '?>" /> <br />
            Confirm: <input type="text" name="confirm" value="<?=isset($confirm) ? $confirm : ' '?>" /> <br />
            <input type="submit" value="Save" />
        
         </form>
</body>
</html>
