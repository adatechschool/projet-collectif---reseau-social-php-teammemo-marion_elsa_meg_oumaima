<br>
<hr>
<br>

<form action="wall.php?user_id=<?php echo $userId; ?>" method="post">
<input type="submit" name = 'subscribe' value="S'abonner">
</form>

<?php 
if (isset($_POST['subscribe'])) {
//Etape 4 : construction de la requete
$SqFollow = "INSERT INTO followers "
    . "(id, followed_user_id, following_user_id) "
    . "VALUES (NULL, "
    .  $userId. ", "
    . $_SESSION['connected_id'] .")
    ;";

    $SqlFollowResult = $mysqli -> query($SqFollow);

    if ( ! $SqlFollowResult)
        {
        echo "oups, impossible de s'abonner";
        } else{
            header("Refresh:0");;
        }
}
    ?>
