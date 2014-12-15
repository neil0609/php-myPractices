<?php

require 'config.php';
protected_area(true);
$page_title = 'User';

$crud->table = 'user';

// Get list of existing users
$user_list = $crud->select = ('*');

// Track any errors
$errors = [];

if (isset($_GET['create'])) {
    
    // Post the form
    $login = $_POST['login'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];
    $email = $_POST['email'];
    
    // Error Checking
    if (strlen($login) == 0) {
        $errors[] = 'Login is required';
    }
    
    if (strlen($password) ==0) {
        $errors[] = 'Password is required';
    }
    
    if ($password != $confirm) {
        $errors[] = 'Passwords do not match';
    }
    
    if (strlen($email) == 0) {
        $errors[] = 'Email is required';
    }
    
    // Success
    if (empty($errors)) {
        $user_id = $crud->insert([
            'login' => $login,
            'password' => hash256($password),
            'email' => $email
        ]);
        
        if (!$user_id) 
        {
            $errors[] = 'Problem creating the user';
        }
        else 
        {
            redirect('user.php');  
            exit;
        }
    }
}

if (isset($_GET['delete'])) {
    $user_id = $GET['delete'];
    $result = $crud->delete(['user_id' => $user_id]);
    if ($result) 
    {
        redirect('user.php');
    } else 
    {
        $errors[] = "Could not delete user";
    }
}


?>


<!doctype html>
<html>
    <body>
        
        <a href="index.php?logout"> Logout </a>
        
        <h1> User Manager </h1>
        
        <?php 
            if(!empty($errors)) {
                echo "<ul>";
                foreach ($errors as $_value) {
                    echo "<li> $_value </li>";
                }
                echo "</ul>";
            }
        ?>
        
        <h3> Create </h3>
        
        <form method="post" action="user.php?create">
            Login: <input type="text" name="login" value="<?=isset($login) ? $login : ' '?>" /> <br />
            Password: <input type="text" name="password" value="<?=isset($password) ? $password : ' '?>" /> <br />
            Confirm: <input type="text" name="confirm" value="<?=isset($confirm) ? $confirm : ' '?>" /> <br />
            Email: <input type="email" name="email" value="<?=isset($email) ? $email : ' '?>" /> <br />
            <input type="submit" value="Create" />
        </form>
        
        <h3> Existing Users </h3>
        <table>
            <?php foreach ($user_list as $user):?>
                <tr>
                    <td><?=$user['user_id']?></td>
                    <td><?=$user['login']?></td>
                    <td><?=$user['email']?></td>  
                    <td><a href="userEdit.php?id<?=$user['user_id']?>">Edit</a></td>
                    <td><a href="?delete"=<?=$user['user_id']?>"> Delete </a></td>
                </tr>
            <?php endforeach;?>    
        </table>
        
    </body>
</html>
