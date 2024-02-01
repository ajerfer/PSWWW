// Include jQuery library
document.write('<script src="../lib/jquery-3.7.1.js"><\/script>');

// Array to store selected products
var selectedProducts = [];

// Function to open a popup box
function openPopupBox(id) {
    const modal = document.getElementById('modal' + id);
    modal.style.display = 'block';
}

// Function to close a popup box
function closePopupBox(id) {
    // Clear the content
    if (id === "newAnnouncement" && selectedProducts.length != 0) {
        selectedProducts = [];
        document.getElementById("selectedProductsContainer").innerHTML = "";
    }

    const modal = document.getElementById('modal' + id);
    modal.style.display = 'none';
}

// Function to get quantities from modal inputs
function getQuantities(announcementId, n) {
    const quantities = [];
    for (let i = 0; i < n; i++) {
        var input = document.querySelector(`[name="${announcementId}_${i}_input_modal"]`);
        // Verificar si el valor es NaN, nulo o negativo, y en ese caso, asignar 0
        const value = (isNaN(parseInt(input.value)) || input.value<0) ? 0 : parseInt(input.value);
        quantities.push(value);
    }
    console.log(quantities);
    return quantities;
}

// Function to create an offer
function callCreateOffer(userId, announcementId, quantities) {
    
    console.log(quantities);
    var isEmpty = true;
    for (var i=0; i<quantities.length && isEmpty; i++)
        if (quantities[i] > 0)
            isEmpty = false; 

    if (isEmpty) {
        alert("No cuantity selected.");
    } else {
        // Perform AJAX call
        $.ajax({
            type: "POST",
            url: "edit_mongo.php", // Change to the correct URL
            data: {
                action: "createOffer", 
                payload: {
                    userId: userId,
                    announcementId: announcementId,
                    quantities: quantities                    
                } 
            },
            success: function(response) {
                console.log(response);
                // Reload the page after successful creation
                window.location.href = "citizen/offers.php";
                alert("Offer created.");
            },
            error: function(error) {
                console.error(error);
            }
        });
    }}

// Function to add a product to the selected products list
function addProduct() {
    // Get the selected value from the dropdown
    const selectedProduct = document.getElementById('productDropdown').value;

    // Check if the product is not already in the list
    if (!selectedProducts.includes(selectedProduct)) {
        // Add the product to the list
        selectedProducts.push(selectedProduct);

        // Update the visualization of the list in the container
        showProducts();
    } else {
        alert("Product already selected.");
    }
}

// Function to display selected products in the container
function showProducts() {
    const productsContainer = document.getElementById('selectedProductsContainer');
    
    // Limpiamos el contenido actual del contenedor
    productsContainer.innerHTML = '';

    // Creamos un contenedor div para los productos
    const productsListContainer = document.createElement('div');
    productsListContainer.style.overflowY = 'auto'; // Agregamos scroll vertical
    productsListContainer.style.maxHeight = '100px'; // Establecemos una altura máxima (ajusta según tus necesidades)
    productsListContainer.style.border = '1px solid #ccc'; // Agregamos un borde

    // Creamos un elemento strong para "Selected Products:"
    const strongElement = document.createElement('strong');
    strongElement.textContent = 'Selected Products:';

    // Añadimos strongElement y productsListContainer al contenedor principal
    productsContainer.appendChild(strongElement);
    productsContainer.appendChild(productsListContainer);

    // Iteramos sobre los productos y los añadimos al contenedor interno
    selectedProducts.forEach(product => {
        const productItem = document.createElement('p');
        productItem.textContent = product;
        productsListContainer.appendChild(productItem);
    });
}

// Function to call the createAnnouncement PHP function using AJAX
function callCreateAnnouncement() {
    if (selectedProducts.length === 0) {
        // console.log("No products selected.");
        alert("No products selected.");
    } else {
        // Perform AJAX call
        $.ajax({
            type: "POST",
            url: "edit_mongo.php", // Change to the correct URL
            data: {
                action: "createAnnouncement", 
                payload: selectedProducts 
            },
            success: function(response) {
                console.log(response);
                // Reload the page after successful creation
                window.location.href = "announcements.php";
                alert("Announcement created.");
            },
            error: function(error) {
                console.error(error);
            }
        });
    }
}

// Function to call the deletheAnouncement PHP function using AJAX
function callDeleteAnnouncement(announcementId) {
    // Use the JavaScript confirm() function
    var confirmation = confirm("Are you sure you want to delete the announcement?");

    // Check the user's response
    if (confirmation) {
        // Perform AJAX call
        $.ajax({
            type: "POST",
            url: "edit_mongo.php", // Change to the correct URL
            data: {
                action: "deleteAnnouncement", 
                payload: announcementId 
            },
            success: function(response) {
                console.log(response);
                // Reload the page after successful creation
                window.location.href = "announcements.php";
                //alert("Announcement deleted.");
            },
            error: function(error) {
                console.error(error);
            }
        });
    } 
}
