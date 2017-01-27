<!-- Formulaire d'inscription -->

<form action="inscription.php" class="login_form modal" id="inscription" 
style="display:none;" method="post">

			<h1>Inscription</h1>';

            <div class="form-group">
				<label>Pseudo<span class="mandatory">*</span></label><br/>
				<input type="text" name="pseudo" class="form-control" value="<?php if(isset($_POST['pseudo'])){echo $_POST['pseudo'];} ?>" />
<!--				<input pattern="[a-zA-Z0-9-_.]{5,20}" title="Voici les formats autorisés : De A à Z et de 0 à 9" type="text" name="pseudo" class="form-control" value="<?php if(isset($_POST['pseudo'])){echo $_POST['pseudo'];} ?>" />
	-->		</div>

            <div class="form-group">
				<label>Mot de passe<span class="mandatory">*</span><span class="mandatory">*</span></label>
				<input type="password" name="mdp" class="form-control" value="<?php if(isset($_POST['mdp'])){echo $_POST['mdp'];} ?>"/>
			</div>
			
            <div class="form-group">
				<label>Nom<span class="mandatory">*</span></label>
				<input type="text" name="nom" class="form-control" required="required" value="<?php if(isset($_POST['nom'])){echo $_POST['nom'];} ?>"/>
			</div>
	
            <div class="form-group">
				<label>Prénom<span class="mandatory">*</span></label><br/>
				<input type="text" name="prenom" class="form-control" required="required" value="<?php if(isset($_POST['prenom'])){echo $_POST['prenom'];} ?>"/>
			</div>
	
            <div class="form-group">
				<label>Email<span class="mandatory">*</span></label><br/>
				<input type="text" name="email" class="form-control" required="required" value="<?php if(isset($_POST['email'])){echo $_POST['email'];} ?>"  />
			</div>

            <div class="form-group">
				<select name="civilite" class="form-control">
				<option value="m" <?php if(isset($_POST['civilite']) && $_POST['civilite'] == 'm' ){echo 'selected';} ?>>Homme</option>
				<option value="f" <?php if(isset($_POST['civilite']) && $_POST['civilite'] == 'f' ){echo 'selected';} ?>>Femme</option>
				</select>
			</div>

			<!-- Le statut et la date d'enregistrement ne sont pas demandés à l'utilisateur--> 
	
            <div class="form-group">
				<input type="submit" value="Inscription"/>
			</div>

		</form>

<!-- Formulaire de Connexion -->
<form action="connexion.php" class="login_form modal" id="connexion" 
style="display:none;" method="post">

			<h1>Connexion</h1>
  
            <div class="form-group">
				<label>Pseudo</label><br/>
				<input pattern="[a-zA-Z0-9-_.]{5,20}" title="Voici les formats autorisés : De A à Z et de 0 à 9" type="text" required="required" name="pseudo" class="form-control" value="<?php if(isset($_POST['pseudo'])){echo $_POST['pseudo'];} ?>" />
			</div>

            <div class="form-group">
				<label>Mot de passe</label>
				<input type="password" name="mdp" class="form-control"  value="<?php if(isset($_POST['mdp'])){echo $_POST['mdp'];} ?>"/>
			</div>

            <div class="form-group">
				<input type="submit" value="Connexion"/>
			</div>
	
</form>

<!-- Formulaire de Contact -->
<form action="contact.php" class="login_form modal" id="contact" 
style="display:none;" method="post">

			<h1>Contact</h1>

            <div class="form-group">
				<label class="control-label">Nom<span class="mandatory">*</span></label>
				<input type="text" name="nom" class="form-control" value="<?php if(isset($_POST['nom'])){echo $_POST['nom'];} ?>"/>
			</div>
	
            <div class="form-group">
				<label>Prénom</label><br/>
				<input type="text" name="prenom" class="form-control"  value="<?php if(isset($_POST['prenom'])){echo $_POST['prenom'];} ?>"/>
			</div>
	
            <div class="form-group">
				<label class="control-label">Email<span class="mandatory">*</span></label><br/>
				<input type="text" name="email" class="form-control"  value="<?php if(isset($_POST['email'])){echo $_POST['email'];} ?>"/>
			</div>

            <div class="form-group">
				<label class="control-label">Sujet<span class="mandatory">*</span></label><br/>
                    <select id="Suje" name="sujet" class="form-control" required="">
                        <option value="Question(s) sur un achat" >Question(s) sur un achat</option>
                        <option value="problème de connexion" >Problème de connexion</option>
                        <option value="Question générale" >Question générale</option>
                    </select>
    		</div>

            <div class="form-group">
				<label>Message</label>
                <textarea class="form-control" id="Message" name="message" placeholder="Comment pouvons-nous vous aider ?" ></textarea>
			</div>

            <div class="form-group">
				<input type="submit" value="Envoi"/>
			</div>
	
</form>


<!-- Formulaire dépôt commentaire -->

<form action="depot_commentaire.php" class="login_form modal" id="depot_commentaire" 
style="display:none;" method="post">

			<h1>Dépôt Commentaire</h1>
			
			<input  type="hidden" name="id_livre" value="<?php echo $livre; ?>" />
			<input  type="hidden" name="id_personne" value="<?php echo $_SESSION['membre']['id_personne']; ?>" />
		
			<div class="form-group">
				<label>Note<span class="mandatory">*</span></label>
				<select class="form-control" name="note">
					<?php
					$i= 1;
					while ($i <= 20) {
						echo '<option value="' . $i . '">' . $i . '</option>';
							$i++;
						} ?>
				</select>
			</div>

            <div class="form-group">
				<label>Commentaire</label>
                <textarea class="form-control" id="commentaire" name="commentaire" required="required" placeholder="Saisissez votre commentaire" ></textarea>
			</div>

			<div class="form-group">
				<button type="submit" class="btn btn-default">Enregistrer</button>
				<button type="reset" class="btn btn-default">Remise à zéro</button>
            </div>

	
</form>

