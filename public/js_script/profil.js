let modifierProfilPopUp = null;

document.addEventListener("DOMContentLoaded", function(event) { 
    modifierProfilPopUp = document.getElementById("modifier_profil_pop-up");
});

function ClosePopUp() {
    if (modifierProfilPopUp) {
        modifierProfilPopUp.style.display = "none";
    }
}

function ShowPopUp() {
    // while (!modifierProfilPopUp) {
    //     setTimeout(() => {
	// 		console.log("attendre");
	// 	}, 500)
    // }

    if (modifierProfilPopUp) {
        modifierProfilPopUp.style.display = "flex";
    }
}