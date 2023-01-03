let utilisateurs = null;
let produits = null;
let exemplaires = null;
let collections = null;
let commandes = null;

window.addEventListener('DOMContentLoaded', (event) => {
    utilisateurs = document.getElementById('utilisateurs');
    produits = document.getElementById('produits');
    exemplaires = document.getElementById('exemplaires');
    collections = document.getElementById('collections');
    commandes = document.getElementById('commandes');
});

function UtilisateursClicked() {
    utilisateurs.classList.remove("hidden");
    produits.classList.add("hidden");
    exemplaires.classList.add("hidden");
    collections.classList.add("hidden");
    commandes.classList.add("hidden");
}

function ProduitsClicked() {
    utilisateurs.classList.add("hidden");
    produits.classList.remove("hidden");
    exemplaires.classList.add("hidden");
    collections.classList.add("hidden");
    commandes.classList.add("hidden");
}

function ExemplairesClicked() {
    utilisateurs.classList.add("hidden");
    produits.classList.add("hidden");
    exemplaires.classList.remove("hidden");
    collections.classList.add("hidden");
    commandes.classList.add("hidden");
}

function CollectionsClicked() {
    utilisateurs.classList.add("hidden");
    produits.classList.add("hidden");
    exemplaires.classList.add("hidden");
    collections.classList.remove("hidden");
    commandes.classList.add("hidden");
}

function CollectionsClicked() {
    utilisateurs.classList.add("hidden");
    produits.classList.add("hidden");
    exemplaires.classList.add("hidden");
    collections.classList.add("hidden");
    commandes.classList.remove("hidden");
}