<?php
    $content="<div class='row justify-content-center pt-4'>
    <div class='col-7 form-vp'>
        <h3>Formulaire de contact</h3><br>
        <div class='row pt-3'>
            <div class='col-4'>
            Auteur
            </div>
            <div class='col-8'>
            <input type='text' name='auteur' id='auteur_contact'>
            </div>
        </div>
        <div class='row'>
            <div class='col-4'>
            E-mail*
            </div>
            <div class='col-8'>
            <input type='text' name='adresse' id='adresse_contact'><span class='error-message'></span>
            </div>
        </div>
        <div class='row'>
            <div class='col-4'>
            Titre
            </div>
            <div class='col-8'>
            <input type='text' name='titre' id='titre_contact'>
            </div>
        </div>
        <div class='row'>
            <div class='col-4'>
            Message*
            </div>
            <div class='col-8'>
            <textarea name='message' id='message_contact' rows='6'></textarea>
            <span style='' class='error-message' id='message_erreur'></span>
            </div>
        </div>
        <div class='row'>
            <div class='col-4'>
            Veuillez recopier le code*
            </div>
            <div class='col-8'>
            <input type='text' id='captcha' name='captcha' />
            </div>
        </div> 
        <div class='row'>
            <div class='col-4'>
				<button class='btn' style='background-color:var(--menu-gray);' title=\"changer le captcha\" onClick='reload_captcha()'>
				<i class='bi bi-arrow-clockwise'style='font-size:1.6rem;color:white;'></i>
				</button>&nbsp;<span id='captcha_txt'><img src='captcha.php' alt='CAPTCHA'></span>
            </div>
        </div>
        <div class='row'>
            <div class='col-4'>
            </div>
            <div class='col-8'>
            <button type='button' class='btn btn-secondary' onClick='submit_contact()'>Go</button>
            </div>
        </div>
    </div>
    <div class='col-1'>
        <img src='../img/logo_arbre.png'>
    </div>
</div>";
?>