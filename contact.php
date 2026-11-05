<body style="background: linear-gradient(135deg, #0f172a, #1e3a8a);">
    <main class="container py-3" style="font-size:25px; text-align: center;">
        <!-- Bandeau compact -->
        <div class="p-2 mb-2 bg-primary text-white" style="font-size:30px;">Page de contact</div>
        <form id="formLogin">
            <div style="color: white">
                <label for="nom">Nom :</label><br>
                <input type="Nom" id="nom"><br><br>
            </div>
            <div style="color: white">
                <label for="pré">Prénom :</label><br>
                <input type="prénom" id="pré"><br>
            </div>
            <div style="color: white">
                <label for="mail">E-mail :</label><br>
                <input type="email" id="email"><br>
            </div>
            <div style="color: white">
                <label for="mail">Téléphone :</label><br>
                <input type="text" id="domaine"><br>
            </div>
            <div style="color: white">
                <label><br>
                    <input type="checkbox" id="connexion">Abonnement à la newsletter ?
                </label><br><br>
            </div>
            <div id="messageErreur" class="erreur"></div><br>
            <button type="button" onclick="verifierCoordonnees()">Valider
            </button>
        </form>

</body>