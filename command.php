<?php
//error_reporting(0);


if (!isset($_SESSION['login'])) {
    if (isset($_COOKIE['login'])) {
        $_SESSION['login'] = $_COOKIE['login'];
    }
}

$DBservername = 'localhost';
$DBusername = 'root';
$DBpassword = '';
$DBdatabase = 'forum_informatyk';
$con = mysqli_connect($DBservername, $DBusername, $DBpassword, $DBdatabase);

if (!$con) {
    die('Błąd połaczenia z bazą danych: ' . mysqli_connect_error());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum Informatyczne</title>
    <link rel="stylesheet" href="indexa.css">
</head>
<body>
    <div class="top">
        <div class="logo"><h1>Forum Informatyk</h1></div>
        <div class="menu">
            <table>
                <tr>
                    <td><a href="index.php">Strona główna</a></td>
                    <td><a href="category.php">Kategorie</a></td>
                    <td><a href="views.php">Najczęsciej odwiedzane</a></td>
                    <td><a href="command.php">Najczęściej Komentowane</a></td>
                    <td><?php include('session.php'); checklogin(); ?></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="left-side">
        
            <?php
                AddPost();
            ?>
        
    </div>
    <div class="container">

        <?php 
            $query = "SELECT `title`,`views`,`komentarze_ilosc`,`kategoria`,`data` FROM `post`";
            $result = mysqli_query($con, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    
                    echo '<div class="tresc">';
                    echo '<form action="post.php" method="POST">';
                        echo '<div class="iloscwyswietlen">Wyświetlenia: ' . $row['views'] . '</div>';
                        echo '<div class="liczbaodpowiedzi">Komentarze: ' . $row['komentarze_ilosc'] . '</div>';
                        echo '<input type="hidden" name="title" value="'.$row['title'].'">';  
                        echo '<input type="submit" value="'.$row['title'].'">';                      
                        echo '<div class="kategoria">' . $row['kategoria'] . '</div>';
                        echo '<div class="kiedydodane">' . $row['data'] . '</div>';
                        echo '</form>';
                    echo '</div>';
                }
            } 
        ?>
       
    </div>

    <div class="right-side"></div>
    <div class="fotter">
        <h2>Powered by Mateusz Piotrowski</h2>
    </div>
</body>
</html>
