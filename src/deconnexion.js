            const deconnexion = document.getElementById("deconnecter")
            console.log('deconnexion');
    

           // Écoutez le clic sur le lien de déconnexion
            deconnexion.addEventListener("click", function (event) {
                // Empêchez le comportement par défaut du lien
                event.preventDefault();
            
                // Supprimez le token d'authentification du local storage
                localStorage.removeItem("authToken");
            
                // Redirigez l'utilisateur vers la page de déconnexion
 //           window.location.href = "http://php-dev-1.online/index.php"; 
            window.location.href = "http://php-dev-1.online/logout.php";
            });
            