<?php

use phpDocumentor\Reflection\Location;
use Symfony\Component\HttpFoundation\Session\Session;

    $DBservername = 'localhost';
    $DBusername = 'root';
    $DBpassword = '';
    $DBdatabase = 'forum_informatyk';
    $con = mysqli_connect($DBservername,$DBusername,$DBpassword,$DBdatabase);
    
    if(!$con){
        echo 'Błąd połaczenia z bazą danych '. mysqli_connect_error();
    }
    
session_start();

if (!isset($_SESSION['login'])) {
    if (isset($_COOKIE['login'])) {
        $_SESSION['login'] = $_COOKIE['login'];
    }
}

    function checklogin(){
        if (isset($_SESSION['login'])) {
            echo '<a href="logout.php">Wyloguj się</a>';
        } else {
            echo '<a href="login.php">Zaloguj się</a>';
        }
    }
    if(isset($_POST['title_submit'])){
        header("Location: post.php");
    } 
    Function AddPost(){
        if (isset($_SESSION['login'])){
            echo '<a href="addpost.php"> Dodaj Post!</a>';
        }
    }
    if(isset($_POST['post_add_btn'])){
        $login = $_SESSION['login'];
        $title_add = $_POST['title_add'];
        $category_add = $_POST['category_add'];
        $content_add = $_POST['content_add'];
        $time = date('Y-m-d');
        
        if(!empty($title_add)){
            if(!empty($content_add)){
                // Wstawianie danych do tabeli post_tresc
                $query1 = "INSERT INTO `post_tresc` (`tytul`, `tresc`, `user`, `time`, `views`, `category`, `komentarzeilosc`)
                           VALUES ('$title_add', '$content_add', '$login', '$time', 0, '$category_add', 0)";
                $result1 = mysqli_query($con, $query1);
                
                if($result1){
                    // Wstawianie danych do tabeli post
                    $query2 = "INSERT INTO `post` (`title`, `views`, `komentarze_ilosc`, `data`, `kategoria`)
                               VALUES ('$title_add', 0, 0, '$time', '$category_add')";
                    $result2 = mysqli_query($con, $query2);
                    
                    if($result2){
                        // Przekierowanie po pomyślnym dodaniu danych
                        header("Location: addpost.php");
                        exit();
                    } else {
                        echo "Wystąpił błąd podczas dodawania posta do tabeli post.";
                        echo "Error: " . mysqli_error($con);
                    }
                } else {
                    echo "Wystąpił błąd podczas dodawania posta do tabeli post_tresc.";
                    echo "Error: " . mysqli_error($con);
                }
            } else {
                echo "Treść jest wymagana";
            }
        } else {
            echo "Podaj tytuł";
        }
    }
    if(isset($_POST['cmmt_add_btn'])){
        if($_SESSION['login']){
            $comm_value = $_POST['commant_add'];
            $user = $_SESSION['login'];
            $title_cmmt = $_POST['title_cmmt'];
            $time = date('Y-m-d');
            mysqli_query($con,"INSERT INTO `komentarze`(`user`, `komentarz`, `tutyl`, `data`) VALUES ('$user','$comm_value','$title_cmmt','$time')");
            
            
            $result = mysqli_query($con, "SELECT * FROM `komentarze` WHERE `tutyl` = '$title_cmmt' ORDER BY `data` DESC");
            while($row = mysqli_fetch_assoc($result)) {
                echo "<div class='comment'>";
                echo "<h4>" . $row['user'] . "</h4>";
                echo "<p>" . $row['komentarz'] . "</p>";
                echo "<span>" . $row['data'] . "</span>";
                echo "</div>";
            }
        } else {
            echo "Proszę się zalogować, aby dodać komentarz.";
        }
    }

    ?>
