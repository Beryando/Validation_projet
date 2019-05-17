<?php

$bdd = new PDO('mysql:host=localhost;dbname=smoothie_barjo', 'root', '0000'); //----------Connexion BDD----------

if(isset($_POST['form_inscription']))
{
    $id_member = htmlspecialchars($_POST['id_member']);//--------'htmlsepcialchars' pour sécuriser la variable
    $passw = sha1($_POST['passw']); //------Hachage de mdp-------
    $passw2 = sha1($_POST['passw2']);
    $mail = htmlspecialchars($_POST['mail']);
    $mail2 = htmlspecialchars($_POST['mail2']);
    $age = htmlspecialchars($_POST['age']);

    //---------Vérifier si tous les champs sont remplis----------
    if(!empty($_POST['id_member']) AND !empty($_POST['passw']) AND !empty($_POST['passw2']) AND !empty($_POST['mail']) AND !empty($_POST['mail2']) AND !empty($_POST['age']))
    {
    //-------------Vérifier que le id_member<255 caractères----------
        $id_member_length = strlen($id_member);
        if($id_member_length <= 255)
        {
            $request_id = $bdd->prepare("SELECT * FROM members WHERE id_member = ? ");//-----------Vérifier si l'identifiant existe déja dans la BDD-----------
            $request_id->execute(array($id_member));
            $id_exist = $request_id->rowCount();//-------rowCount compte le nombre de colonnes de "request_id"-------
            if($id_exist == 0)   
            {
                if(filter_var($mail, FILTER_VALIDATE_EMAIL))//--------Vérifier que le mail est valide------
                {
                    if($mail == $mail2) //------Vérifier que les adresses mail correspondent--------
                    {
                        if($passw == $passw2) //---------------Vérifier que les MDP correspondent-----
                        {
                            $request_mail = $bdd->prepare("SELECT * FROM members WHERE mail = ? ");//-----------Vérifier si l'adresse mail existe déja dans la BDD-----------
                            $request_mail->execute(array($mail));
                            $mail_exist = $request_mail->rowCount();//-------rowCount compte le nombre de colonnes de "request_mail"-------
                            if($mail_exist == 0)   
                            {
                                if($age >= 18)//-------------Vérifier que la personne est majeure------------
                                {//--------Insérer les infos rentrées dans la base de donnée-----------
                                    $insert_member = $bdd->prepare("INSERT INTO members(id_member, passw, mail, age) VALUES(?, ?, ?, ?)");
                                    $insert_member->execute(array($id_member, $passw, $mail, $age)); //--------Executer la fonction et insérer les infos dans la BDD sous forme de tableau-------
                                    $error = "Votre compte a bien été créé ! <a href=\"connexion.php\">Me connecter</a>";
                                }
                                else
                                {
                                    $error = "Vous devez être majeur pour vous inscrire!";
                                }
                            } 
                            else
                            {
                                $error = "Votre adresse mail est déjà utilisée !"; 
                            }   

                        }
                        else
                        {
                            $error = "Vos mots de passes ne correspondent pas!";
                        }
                    }
                    else
                    {
                        $error = "Vos adresses mail ne correspondent pas!";
                    }
                }
                else
                {
                    $error = "Votre adresse mail n'est pas valide!";
                }
            }
            else
            {
                $error = "Cet identifiant est déjà utilisé!";
            }
        }
        else //----------Si le pseudo dépasse 255 caractères---------
        {
            $error = "Votre pseudo ne doit pas dépasser 255 caractères !";
        }
    }
    else  //------------Si les champs ne sont pas tous remplis--------
    {
        $error = "Tous les champs doivent être complétés!";
    }
}
?>
<?php require_once './templates/header.html'?>


<h1>Inscription</h1>
<!-----------Formulaire d'inscription--------->
<div class="container-fluid" id="contact">
    <div class="row align-items-center">
        <div class="col-12 offset-md-7 col-md-4 formCont">
            <form action="" method="POST"
                enctype="text/plain">
                <div class="form-group">
                    <label for="id_member">Email</label>
                    <input type="text" class="form-control"
                        name="id_member" aria-describedby="emailHelp"
                        id="id_membre"
                        placeholder="Votre identifiant" value="<?php
                        if(isset($id_member)) { echo $id_member; } ?>">

                </div>
                <div class="form-group">
                    <label for="passw">Mot de passe</label>
                    <input type="password" class="form-control"
                        name="passw" id="passw"
                        placeholder="Votre mot de passe">
                    <small id="emailHelp" class="form-text text-muted">
                    </small>
                </div>
                <div class="form-group">
                    <label for="passw2">Confirmez votre mot de passe</label>
                    <input type="password" class="form-control"
                        name="passw2" id="passw2"
                        placeholder="Confirmez votre mot de passe">
                </div>
                <div class="form-group">
                    <label for="mail">Email</label>
                    <input type="email" class="form-control"
                        name="mail" id="mail"
                        placeholder="Votre email" value="<?php if(isset($mail))
                        { echo $mail; } ?>">
                </div>
                <div class="form-group">
                    <label for="mail2">Confirm email</label>
                    <input type="email" class="form-control"
                        name="mail2" id="mail2"
                        placeholder="Confirmez votre email" value="<?php
                        if(isset($mail2)) { echo $mail2; } ?>">
                </div>
                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="number" class="form-control" id="name" name="age"
                        placeholder="Votre age" value="<?php if(isset($age)) { echo $age; } ?>">
                </div>
                
                
                <button type="submit" name='form_inscription' value="Je m'inscris" class="btn btn-danger">Envoyer</button>
            </form>
            <?php //-----------Si tous les champs ne sont pas remplis----------
                if(isset($error))
                {
                    echo '<font color="red">'.$error.'</font>';
                }
                ?>
        </div>
    </div>
</div>
<?php require_once './templates/footer.html'?>