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
    let nom = document.getElementById("nom").value.trim();
    let prénom = document.getElementById("pré").value.trim();
    let email = document.getElementById("email").value.trim();
    let téléphone = document.getElementById("domaine").value.trim();
    let newsletter = document.getElementById("connexion").checked;
    let message = document.getElementById("messageErreur");

    message.textContent = "";

    const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (nom === "") {
        message.textContent = "Le champ Nom doit être rempli.";
    }
    else if (prénom === "") {
        message.textContent = "Le champ Prénom doit être rempli.";
    }
    else if (email === "") {
        message.textContent = "Le champ E-mail doit être rempli.";
    }
    else if (!regexEmail.test(email)) {
        message.textContent = "L'adresse e-mail n'est pas valide.";
    }
    else if (téléphone === "") {
        message.textContent = "Le champ Téléphone doit être rempli.";
    }
    else if (!/^\d{10}$/.test(téléphone)) {
        message.textContent = "Le numéro de téléphone doit contenir exactement 10 chiffres.";
    }
    else {
        alert("Formulaire envoyé avec succès !");
    }
}

