<?php
    //error_reporting(0);



    $DBservername = 'localhost';
    $DBusername = 'root';
    $DBpassword = '';
    $DBdatabase = 'forum_informatyk';
    $con = mysqli_connect($DBservername,$DBusername,$DBpassword,$DBdatabase);
    
    if(!$con){
        echo 'Błąd połaczenia z bazą danych '. mysqli_connect_error();
    }
    if(isset($_POST['login-button'])){
        $login = $_POST['login'];
        $password = $_POST['password'];

        $loggedin = "SELECT * FROM user WHERE `login` = '$login' AND `password` = '$password'";
        $result = mysqli_query($con, $loggedin);
        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
                $_SESSION['login'] = $login;
                setcookie("login",$login, time()+ 3600, "/");
                header("Location: index.php");
                exit;
        }else{
            echo "Nieprawidłowy login lub hasło";
        }
    }
    if(isset($_POST['register-button'])){
        $output = "";
        $login1 = $_POST['login1'];
        $mail = $_POST['mail'];
        $haslo1 = $_POST['haslo1'];
        $haslo2 = $_POST['haslo2'];
        $dblogin = mysqli_query($con,"SELECT `login` FROM `user` WHERE `login` = '$login1'");
        $dbmail = mysqli_query($con,"SELECT `mail` FROM `user` WHERE `mail` = '$mail'");
        if(mysqli_num_rows($dblogin) >0){
            $output = "Login jest już użyty";
        }else{
            if(mysqli_num_rows($dbmail)> 0){
            $output = " Mail jest już użyty";
            }else{
                if(strpos($mail,'@')== false){
                    $output = "Mail jest nieprawidłowy";
                }else{
                    if($haslo1 != $haslo2){
                    $output = "Hasła muszą być identyczne";
                    }else{
                        mysqli_query($con, "INSERT INTO `user`(`mail`, `login`, `password`) VALUES ('$mail','$login','$haslo1')");
                        header("Location: login.php");
                        exit;
                    }
                }
            }
        }

    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum Informatyczne</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="top">
        <div class="logo"><h1>Forum Informatyczne</h1></div>
        <div class="menu">
            <table>
            <tr>
                <td><a href="index.php">Strona główna</a></td>
                <td><a href="index.php">Kategorie</a></td>
                <td><a href="index.php">Najwyżej oceniane</a></td>
                <td><a href="index.php">Najczęściej Komentowane</a></td>
                <td><?php include('session.php');checklogin();?></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="container">
        <form action="login.php" method="POST">
        <div class="login">
            <div class="login-panel">
                <h2>Zaloguj się !</h2>
                <input type="text" name="login" placeholder="Wpisz login">
                <input type="password" name="password" placeholder="Wpisz hasło">
            </div>
            <div class="button-login"><input type="submit" name="login-button" value="Zaloguj się"></div>
        </div>
        <div class="register">
            <div class="register-panel">
                <h2>Zarejestruj się</h2>
                <input type="text" name="mail" placeholder="Wpisz Email">
                <input type="text" name="login1" placeholder="Wpisz login">
                <input type="password" name="haslo1" placeholder="Wpisz hasło">
                <input type="password" name="haslo2" placeholder="Powtórz hasło">
            </div><br>
            <div class="button-register"><input type="submit" name="register-button" value="Zarejestruj się"></div>
            <?php echo $output;?>
        </div>
        </form>
    </div>
    <div class="fotter">
        <h2>Powered by Mateusz Piotrowski</h2>
    </div>
</body>
</html>