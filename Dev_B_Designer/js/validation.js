// Exemple simple pour valider un formulaire
document.addEventListener("DOMContentLoaded", function() {
    const forms = document.querySelectorAll("form");
    
    forms.forEach(form => {
        form.addEventListener("submit", function(event) {
            let valid = true;
            const inputs = form.querySelectorAll("input[required], textarea[required]");
            
            inputs.forEach(input => {
                if (input.value.trim() === "") {
                    valid = false;
                    input.style.border = "2px solid red";
                } else {
                    input.style.border = "";
                }
            });

            if (!valid) {
                event.preventDefault(); // Empêche l'envoi
                alert("Veuillez remplir tous les champs obligatoires.");
            }
        });
    });
});