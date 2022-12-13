// selectImage permet de séletionner une des images sur la gauche afin de la voir en plus grand à côté. 
function selectImage(selectedImage, e) {

	// On enlève la classe selected de l'élément précédemment sélectionné et on l'ajoute sur l'élément cliqué
	// Le .selected permet de mettre 
	selectedImage.classList.remove("selected");
	e.classList.add("selected");

	// On retourne le lien (src) de l'image qui a été cliqué pour l'afficher en grand à côté
	var img = e.getElementsByTagName('img')[0];
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


// On attent que le site soit chargé
document.addEventListener("DOMContentLoaded", function(event) { 
	// var imagesContainer = document.getElementById("product_images_container")
	var imagesContainer = document.getElementById("product_images_container");
	var selectedImage = imagesContainer.getElementsByClassName("selected")[0];
	var mainImage = document.getElementById("product_image");

	for (const e of imagesContainer.childNodes) {
		if (e.nodeType == 1 && !e.classList.contains("arrow_background")) {
			
			e.addEventListener('click', function(){
				mainImage.src = selectImage(selectedImage, e);
				selectedImage = e;
			});
		}
	}


	// var coloursContainer = document.getElementById("colours_container");
	// var selectedColour = coloursContainer.getElementsByClassName("selected")[0];

	// for (const e of coloursContainer.childNodes) {
	// 	if (e.nodeType == 1) {

	// 		e.getElementsByClassName("colour_image_container")[0].addEventListener('click', function(){
	// 			var eImage = e.getElementsByClassName("colour_image_container")[0];

	// 			selectColour(selectedColour, eImage);
	// 			selectedColour = eImage;				
	// 		});
	// 	}
	// }


	var sizesContainer = document.getElementById("sizes_container");
	var selectedSize = sizesContainer.getElementsByClassName("selected")[0];

	for (const e of sizesContainer.childNodes) {
		if (e.nodeType == 1) {

			e.addEventListener('click', function(){
				selectSize(selectedSize, e);
				selectedSize = e;				
			});
		}
	}


	var upArrow = document.getElementsByClassName("up_arrow")[0];
	var downArrow = document.getElementsByClassName("down_arrow")[0];

	upArrow.addEventListener('click', function(){
		imagesContainer.scrollTop -= 220;
	});

	downArrow.addEventListener('click', function(){
		imagesContainer.scrollTop += 220;
	});

	window.addEventListener("load", event => {
		var img = document.querySelector('#product_images_container img');

		var productIamgesContainer = document.getElementById('product_images_container');
		var productImage = document.getElementById('product_image');

		var imageWidth = productImage.clientWidth;
		var imageHeight = productImage.clientHeight;

		productIamgesContainer.style.height = imageHeight.toString() + "px";
		downArrow.style.marginTop = (imageHeight - 80).toString() + "px";

		productImage.style.width = imageWidth.toString() + "px";
	});
});