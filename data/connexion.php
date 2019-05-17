<?php
session_start();//-----à mettre sur chaque page ou la session est active----

$bdd = new PDO('mysql:host=localhost;dbname=smoothie_barjo', 'root', '0000'); //----------Connexion BDD----------

if(isset($_POST['form_connexion']))//--------Voir si la variable 'form_connexion' existe-------
{
    $id_connect = htmlspecialchars($_POST['id_connect']);//--------'htmlsepcialchars' pour sécuriser la variable
    $passw_connect = sha1($_POST['passw_connect']);

    if(!empty($_POST['id_connect']) AND !empty($_POST['passw_connect']))//--------Vérifier si l'identifiant et le mot de passe ont été renseignés 
    {
        $request_id = $bdd->prepare("SELECT * FROM members WHERE id_member = ? AND passw = ?");//-----------Vérifier que le compte existe bien dans la BDD-----------
        $request_id->execute(array($id_connect, $passw_connect));
        $id_exist = $request_id->rowCount();//-------rowCount compte le nombre de colonnes de "request_id"-------
        if($id_exist == 1)
        {
            $userinfo = $request_id->fetch();//--------Récupère les informations utilisateurs dans la BDD-------
            $_SESSION['id'] = $userinfo['id'];//----------Mettre les infos récupérer dans une session-------
            $_SESSION['id_member'] = $userinfo['id_member'];
            $_SESSION['mail'] = $userinfo['mail'];
            header("Location: profil.php?id=".$_SESSION['id']);//------Rediriger vers le profil de l'utilisateur---
        }
        else
        {
            $error = "Mauvaise identifiant ou mot de passe !";
        }
    }
    else
    {
        $error = "Tous les champs doivent être complétés !";
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>
<body>
    <h1>Connexion</h1> <!-----------Formulaire de connexion--------->
    <form method="POST" action="">
        <table>
        <tr>
        <td>
            <input type="text" name="id_connect" placeholder="Votre identifiant">
        </td>
        </tr>
        <tr>
        <td>
            <input type="password" name="passw_connect" placeholder="Votre mot de passe">
        </td>
        </tr>
        <tr>
        <td>    
            <input type="submit" name="form_connexion" value="Se connecter">
        </td>
        </tr>
        </table>
    </form>
    <?php //-----------Si tous les champs ne sont pas remplis----------
    if(isset($error))
    {
        echo '<font color="red">'.$error.'</font>';
    }
    ?>
</body>
</html>