function OrdreImageModifie(e) {
    console.log(e.srcElement.value);
    const produitAEchanger = document.getElementById("produit" + e.srcElement.value.toString());
    
    const tmp = e.srcElement.id.charAt(e.srcElement.id.length - 1);;
    e.srcElement.value = produitAEchanger.value;
    produitAEchanger.value = tmp;
}

window.addEventListener('DOMContentLoaded', (event) => {
    const produit1 = document.getElementById("produit1");
    const produit2 = document.getElementById("produit2");
    const produit3 = document.getElementById("produit3");
    const produit4 = document.getElementById("produit4");
    const produit5 = document.getElementById("produit5");
    const produit6 = document.getElementById("produit6");
    const produit7 = document.getElementById("produit7");
    const produit8 = document.getElementById("produit8");

    if (produit1 != null) { produit1.onchange = (e) => { OrdreImageModifie(e); }}
    if (produit2 != null) { produit2.onchange = (e) => { OrdreImageModifie(e); }}
    if (produit3 != null) { produit3.onchange = (e) => { OrdreImageModifie(e); }}
    if (produit4 != null) { produit4.onchange = (e) => { OrdreImageModifie(e); }}
    if (produit5 != null) { produit5.onchange = (e) => { OrdreImageModifie(e); }}
    if (produit6 != null) { produit6.onchange = (e) => { OrdreImageModifie(e); }}
    if (produit1 != null) { produit1.onchange = (e) => { OrdreImageModifie(e); }}
    if (produit7 != null) { produit7.onchange = (e) => { OrdreImageModifie(e); }}
});