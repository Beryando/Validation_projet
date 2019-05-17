<?php
session_start();//-----à mettre sur chaque page ou la session est active----

$bdd = new PDO('mysql:host=localhost;dbname=smoothie_barjo', 'root', '0000'); //----------Connexion BDD----------

if(isset($_SESSION['id']))//--------Vérifier si une personne est connectée sur une session
{
    $request_member = $bdd->prepare("SELECT * FROM members WHERE id = ?");//------Sélectionner l'utilisateur dans la base de donnée------
    $request_member->execute(array($_SESSION['id']));
    $user = $request_member->fetch();//------Récupère l'id_member dans la BDD---

    if(isset($_POST['new_id_member']) AND !empty($_POST['new_id_member']) AND $_POST['new_id_member'] != $user['id_member'])//---Vérifier que le champ identifiant n'est pas vide, et que le nouveau est différent de l'ancien---
    {
        $new_id_member = htmlspecialchars($_POST['new_id_member']);//-----Sécuriser la variable en évitant les injections SQL---
        $insert_id_member = $bdd->prepare("UPDATE members SET id_member = ? WHERE id = ?");//---Mettre à jour l'identifiant dans la BDD----
        $insert_id_member->execute(array($new_id_member, $_SESSION['id']));
        header('Location: profil.php?id='.$_SESSION['id']);//---Rediriger l'utilisateur sur le profil correspondant à son id----
    }

    if(isset($_POST['new_mail']) AND !empty($_POST['new_mail']) AND $_POST['new_mail'] != $user['new_mail'])
    {
        $new_mail = htmlspecialchars($_POST['new_mail']);
        $insert_mail = $bdd->prepare("UPDATE members SET mail = ? WHERE id = ?");
        $insert_mail->execute(array($new_mail, $_SESSION['id']));
        header('Location: profil.php?id='.$_SESSION['id']);
    }

    if(isset($_POST['new_age']) AND !empty($_POST['new_age']) AND $_POST['new_age'] != $user['new_age'])
    {
        $new_age = htmlspecialchars($_POST['new_age']);
        $insert_age = $bdd->prepare("UPDATE members SET age = ? WHERE id = ?");
        $insert_age->execute(array($new_age, $_SESSION['id']));
        header('Location: profil.php?id='.$_SESSION['id']);
    }




?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil</title>
</head>
<body>
    <h1>Editer mon profil</h1>
    <form method="POST" action="">
        <label>Identifiant :</label>
        <input type="text" name="new_id_member" placeholder="Identifiant" value="<?php echo $user['id_member']; ?>"><br><br>
        <label>Adresse mail :</label>
        <input type="text" name="new_mail" placeholder="Adresse mail" value="<?php echo $user['mail']; ?>"><br><br>
        <label>Mot de passe :</label>
        <input type="password" name="new_passw" placeholder="Mot de passe"><br><br>
        <label>Confirmation de mot de passe :</label>
        <input type="password" name="new_passw2" placeholder="Confirmation du mot de passe"><br><br>
        <label>Age :</label>
        <input type="text" name="new_age" placeholder="Age" value="<?php echo $user['age']; ?>"><br><br>
        <input type="submit" value="Mettre à jour mon profil"><br><br>

    </form>
<?php
}
else
{
    header("Location: connexion.php");  
}
?> 