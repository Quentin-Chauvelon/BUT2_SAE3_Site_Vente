let utilisateurs = null;
let produits = null;
let exemplaires = null;

window.addEventListener('DOMContentLoaded', (event) => {
    utilisateurs = document.getElementById('utilisateurs');
    produits = document.getElementById('produits');
    exemplaires = document.getElementById('exemplaires');
});

function UtilisateursClicked() {
    utilisateurs.classList.remove("hidden");
    produits.classList.add("hidden");
    exemplaires.classList.add("hidden");
}

function ProduitsClicked() {
    utilisateurs.classList.add("hidden");
    produits.classList.remove("hidden");
    exemplaires.classList.add("hidden");
}

function ExemplairesClicked() {
    utilisateurs.classList.add("hidden");
    produits.classList.add("hidden");
    exemplaires.classList.remove("hidden");
}