function changePrice(){
    let selectElement = document.querySelector('.form-select');
    let selectedOption = selectElement.options[selectElement.selectedIndex];
    // Obtenez la valeur de la remise à partir de l'attribut data-remise
    let reduce = parseFloat(selectedOption.getAttribute('data-reduce'));

    // Mettez à jour le texte du montant de la remise
    document.querySelectorAll('.price').forEach((priceElement) => {
        reducePrice = priceElement.textContent - reduce;
        let reducePriceElement = priceElement.nextElementSibling;
        priceElement.classList.add('d-none')
        reducePriceElement.classList.remove('d-none');
        reducePriceElement.textContent = reducePrice.toFixed(2);
    })
    
};