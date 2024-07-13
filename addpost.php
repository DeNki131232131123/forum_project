<?php
//error_reporting(0);


if (!isset($_SESSION['login'])) {
    if (isset($_COOKIE['login'])) {
        $_SESSION['login'] = $_COOKIE['login'];
    }
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
                    <td><a href="index.php">Kategorie</a></td>
                    <td><a href="index.php">Najwyżej oceniane</a></td>
                    <td><a href="index.php">Najczęściej Komentowane</a></td>
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
        <form method="POST" action="addpost.php">
        <input type="text" name="title_add" placeholder="Wpisz Tytuł">
        <select name="category_add">
            <?php 
                global $con;

                $query = "SELECT `category` FROM `category_post`";
                $result = mysqli_query($con,$query);

                if(mysqli_num_rows($result)>0){
                    while($row = mysqli_fetch_assoc($result)){
                        echo '<option value="' . $row['category'] . '">' . $row['category'] . '</option>';
                }
            }
            ?>
        </select>
        <input type="text" name="content_add" placeholder="Wpisz treść">
        <input type="submit" name="post_add_btn" value="Dodaj Post">
        </form>    
    </div>

    <div class="right-side"></div>
    <div class="fotter">
        <h2>Powered by Mateusz Piotrowski</h2>
    </div>
</body>
</html>
