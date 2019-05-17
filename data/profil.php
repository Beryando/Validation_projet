<?php
session_start();//-----à mettre sur chaque page ou la session est active----

$bdd = new PDO('mysql:host=localhost;dbname=smoothie_barjo', 'root', '0000'); //----------Connexion BDD----------

if(isset($_GET['id']) AND $_GET['id'] > 0)//------Vérifier si la variable 'id' existe et supèrieure à 0-----
{
    $getid = intval($_GET['id']);//-------Pour sécuriser la variable et ne pas pouvoir insérer n'importe quoi---
    $request_id = $bdd->prepare('SELECT * FROM members WHERE id = ?');
    $request_id->execute(array($getid));
    $userinfo = $request_id->fetch();//------Récupère l'ID dans la BDD---
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil</title>
</head>
<body>
    <h1>Profil de <?php echo $userinfo['id_member']; ?></h1> <!-----------Affiche l'identifiant de l'utilisateur dans le titre--------->
    <br><br><br>
    Pseudo = <?php echo $userinfo['id_member']; ?> 
    <br>
    mail = <?php echo $userinfo['mail']; ?>
    <br>
    <?php
    if(isset($_SESSION['id']) AND $userinfo['id'] == $_SESSION['id'])//-----Si l'id de la session en cours correspond bien à l'id de l'utilisateur renseigné dans la BDD-----
    {
    ?>
    <a href="edition_profil.php">Editer mon profil</a>
    <br>
    <a href="deconnection.php">Se déconnecter</a>
    <?php
    }
    ?>
</body>
</html>
<?php
}
?>