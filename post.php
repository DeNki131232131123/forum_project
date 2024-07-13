<?php
//error_reporting(0);

if (!isset($_SESSION['login'])) {
    if (isset($_COOKIE['login'])) {
        $_SESSION['login'] = $_COOKIE['login'];
    }
}
global $con;
function titleLink(){
    global $con;
        $title = $_POST['title'];
        $query = "SELECT `tytul`,`tresc`,`user`,`time`,`views`,`category`,`komentarzeilosc` FROM `post_tresc` WHERE `tytul` = '$title'";


$result = mysqli_query($con, $query);


    if ($result) {
        if(mysqli_num_rows($result) > 0) {
            
            $row = mysqli_fetch_assoc($result);
            $titleAfter = $row['tytul'];
            $content = $row['tresc'];
            $user = $row['user'];
            $time = $row['time'];
            $views = $row['views'] + 1;
            $category = $row['category'];
            $commandValue = $row['komentarzeilosc'];



        echo $titleAfter.'<br>';
        echo $content.'<br>';
        echo $user.'<br>';
        echo $time.'<br>';
        echo $views.'<br>'. $category.'<br>'.$commandValue;
    } else {
        echo "Brak wyników do wyświetlenia.";
    }
    $view_count = "post_views_" . preg_replace('/[=,; \t\r\n\013\014]/', '_', $titleAfter);
    if(isset($_COOKIE[$view_count])){
        $count = $_COOKIE[$view_count];
        $count++;
    }else{
        $count = 1;
            }
        setcookie($view_count,$count,time() + (36000 * 144) ,"/");
    }
    mysqli_query($con,"UPDATE `post_tresc` SET `views`='$count' WHERE `tytul` = '$titleAfter'");
    mysqli_query($con, "UPDATE `post` SET `views` = '$count' WHERE `title` = '$titleAfter'");
}


    


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum Informatyczne</title>
    <link rel="stylesheet" href="indexa.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#commentForm').submit(function(event) {
                event.preventDefault(); // Zapobiega domyślnemu działaniu formularza
                $.ajax({
                    type: 'POST',
                    url: 'session.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#commentsSection').html(response); // Aktualizuje sekcję komentarzy
                    }
                });
            });
        });
    </script>
</script>
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

    <div class="left-side"></div>
    <div class="container">
        
        <?php 
        titleLink();
    
    ?>
    <br> <h2>Dodaj komentarz</h2>
    <form method="POST" id="commentForm" action="session.php">
        <input type="text" name="commant_add" placeholder="Wpisz komentarz">
        <input type="hidden" name="title_cmmt" value="<?php $title?>">
        <input type="submit" name="cmmt_add_btn" value="Wyślij!">
        
    </form>   
    </div>
    <div id="commentsSection">
    <?php
    // Wyświetl początkowe komentarze
    global $title;
    $result = mysqli_query($con, "SELECT * FROM `komentarze` WHERE `tutyl` = '$title' ORDER BY `data` DESC");
    while($row = mysqli_fetch_assoc($result)) {
        echo "<div class='comment'>";
        echo "<h4>" . $row['user'] . "</h4>";
        echo "<p>" . $row['komentarz'] . "</p>";
        echo "<span>" . $row['data'] . "</span>";
        echo "</div>";
    }
    ?>
</div>

    <div class="right-side"></div>
    <div class="fotter">
        <h2>Powered by Mateusz Piotrowski</h2>
    </div>
</body>
</html>
