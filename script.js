function verifierConnexion() {
    let login = document.getElementById("login").value;
    let password = document.getElementById("mdp").value;
    let checkconnexion = document.getElementById("connexion").checked;
    let domaine = document.getElementById("domaine").value;
    let message = document.getElementById("messageErreur");

    message.textContent = "";

    if (login.length < 7) {
        message.textContent = "Le login doit contenir au moins 7 caractères.";
    }
    else if (password === "") {
        message.textContent = "Le mot de passe ne peut pas être vide.";
    }
    else if (!checkconnexion) {
        message.textContent = "La case à cocher Connexion sécurisée doit être cochée";
    }
    else if (domaine === "") {
        message.textContent = "Un domaine doit être sélectionné";
    }
    else if (domaine !== "Tec19") {
        message.textContent = "Le domaine sélectionné doit être Tec19";
    }
    else {
        alert("Connexion réussie !");
        location.href = "admin.php";
    }
}

function verifierCoordonnees() {
    let pseudo = document.getElementById("pseudo").value.trim();
    let mdp = document.getElementById("mdp").value;
    let mdp2 = document.getElementById("mdp2").value;
    let nom = document.getElementById("nom").value.trim();
    let prenom = document.getElementById("prenom").value.trim();
    let email = document.getElementById("mail").value.trim();
    let telephone = document.getElementById("telephone").value.replace(/[\s.-]/g, "");

    const regexEmail = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    const regexTelephone = /^0[1-9]\d{8}$/;

    if (pseudo === "" || mdp === "" || nom === "" || prenom === "") {
        alert("Veuillez remplir tous les champs obligatoires (*).");
        return false;
    }

    if (mdp !== mdp2) {
        alert("Les deux mots de passe ne sont pas identiques.");
        return false;
    }

    // Si l'e-mail est renseigné, on vérifie son format
    if (email !== "" && !regexEmail.test(email)) {
        alert("L'adresse e-mail n'est pas valide (ex: nom@domaine.fr).");
        return false;
    }

    // Si le téléphone est renseigné, on vérifie qu'il contient 10 chiffres valides
    if (telephone !== "" && !regexTelephone.test(telephone)) {
        alert("Le numéro de téléphone doit contenir 10 chiffres valides (ex: 0612345678).");
        return false;
    }

    return true;
}