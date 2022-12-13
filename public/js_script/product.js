// selectImage permet de séletionner une des images sur la gauche afin de la voir en plus grand à côté. 
function selectImage(selectedImage, e) {

	// On enlève la classe selected de l'élément précédemment sélectionné et on l'ajoute sur l'élément cliqué
	// Le .selected permet de mettre 
	selectedImage.classList.remove("selected");
	e.classList.add("selected");

	// On retourne le lien (src) de l'image qui a été cliqué pour l'afficher en grand à côté
	let img = e.getElementsByTagName('img')[0];
	return img.getAttribute("src");
}

// selectColour sélectionne la couleur d'un produit
function selectColour(selectedColour, e) {
	selectedColour.classList.remove("selected");
	e.classList.add("selected");
}

// selectSize sélectionne la taille d'un produit
function selectSize(selectedSize, e) {
	selectedSize.classList.remove("selected");
	e.classList.add("selected");
}


let articleAjoutePopUp = null;
let articleAjouteTimerAnimation = null;


// On attent que le site soit chargé
document.addEventListener("DOMContentLoaded", function(event) { 
	// let imagesContainer = document.getElementById("product_images_container")
	let imagesContainer = document.getElementById("product_images_container");
	let selectedImage = imagesContainer.getElementsByClassName("selected")[0];
	let mainImage = document.getElementById("product_image");

	for (const e of imagesContainer.childNodes) {
		if (e.nodeType == 1 && !e.classList.contains("arrow_background")) {
			
			e.addEventListener('click', function(){
				mainImage.src = selectImage(selectedImage, e);
				selectedImage = e;
			});
		}
	}


	let coloursContainer = document.getElementById("colours_container");
	let selectedColour = coloursContainer.getElementsByClassName("selected")[0];
	let couleurInput = document.getElementById("couleur_input");

	for (const e of coloursContainer.childNodes) {
		if (e.nodeType == 1) {

			e.getElementsByClassName("colour_image_container")[0].addEventListener('click', function(){
				let eImage = e.getElementsByClassName("colour_image_container")[0];

				selectColour(selectedColour, eImage);
				selectedColour = eImage;

				couleurInput.value = e.dataset.couleur;				
			});
		}
	}


	let sizesContainer = document.getElementById("sizes_container");
	let selectedSize = sizesContainer.getElementsByClassName("selected")[0];
	let sizeInput = document.getElementById("taille_input");

	for (const e of sizesContainer.childNodes) {
		if (e.nodeType == 1) {

			e.addEventListener('click', function(){
				selectSize(selectedSize, e);
				selectedSize = e;
				
				sizeInput.value = e.dataset.taille;
			});
		}
	}


	let upArrow = document.getElementsByClassName("up_arrow")[0];
	let downArrow = document.getElementsByClassName("down_arrow")[0];

	upArrow.addEventListener('click', function(){
		imagesContainer.scrollTop -= 220;
	});

	downArrow.addEventListener('click', function(){
		imagesContainer.scrollTop += 220;
	});

	window.addEventListener("load", event => {
		let img = document.querySelector('#product_images_container img');

		let productIamgesContainer = document.getElementById('product_images_container');
		let productImage = document.getElementById('product_image');

		let imageWidth = productImage.clientWidth;
		let imageHeight = productImage.clientHeight;

		productIamgesContainer.style.height = imageHeight.toString() + "px";
		downArrow.style.marginTop = (imageHeight - 80).toString() + "px";

		productImage.style.width = imageWidth.toString() + "px";
	});


	articleAjoutePopUp = document.getElementById('article_ajoute');
	articleAjouteTimerAnimation = document.getElementById('timer_animation');
});


function AfficherProduitAjoutePanier() {
	if (articleAjoutePopUp && articleAjouteTimerAnimation) {
		articleAjoutePopUp.classList.remove("article_ajoute_hidden");
		articleAjouteTimerAnimation.classList.add("timer_animation_start");
	}

	setTimeout(() => {
		articleAjoutePopUp.classList.add("article_ajoute_hidden");
	}, 8000)
}