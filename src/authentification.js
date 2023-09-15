
    // Récupérer le token du local storage
    const authToken = localStorage.getItem("authToken");
    
    // Vérifier si authToken est null ou vide
    if(authToken){
    
        // Créez un objet avec le token d'authentification à envoyer au serveur
        const data = {
            authToken: authToken,
        };

        // Effectuez une requête AJAX POST vers le serveur PHP
        fetch("connectauto.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
        })
        .then(response => response.json())
        .then(data => {

                if (data.authenticated) {
                    
                    // Authentification réussie, redirigez l'utilisateur vers le tableau de bord
                    window.location.href = "http://php-dev-1.online/dashbord.php";
                }

            })
            .catch(error => {
                console.error(error);
            });
        }        
            





