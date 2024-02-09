// Include jQuery library
document.write('<script src="../../lib/jquery-3.7.1.js"><\/script>');

// Initialize the actualSection variable with the current section value
var actualSection = '<?= $section; ?>';

// Function to open a popup box
function openPopupBox(id) {
    const modal = document.getElementById('modal' + id);
    modal.style.display = 'block';
}

// Function to close a popup box
function closePopupBox(id) {
    // Clear the content
    if (id === "newRequest" && selectedProducts.length != 0) {
        selectedProducts = [];
        document.getElementById("selectedProductsContainer").innerHTML = "";
    }

    const modal = document.getElementById('modal' + id);
    modal.style.display = 'none';
}


function showSection(newSection) {
    // Check if the selected section is different from the current section
    if (actualSection !== newSection) {
        // Update the actualSection variable
        actualSection = newSection;

        // Perform an AJAX call to change the section
        $.post("", { section: newSection })
            .done(function () {
                // Reload the content of the div with id "requestsContent" and change the button style
                $("#requestsContent").load(location.href + " #requestsContent" );
                changeButtonStyle(newSection);
            })
            .fail(function (xhr, status, error) {
                // Handle errors here
                console.error("Error changing section:", status, error);
            });
    }
}

function changeButtonStyle(section) {
    // Change the style of the buttons based on the selected section
    var botones = document.querySelectorAll(".seccionButton");
            
    botones.forEach(function (boton) {
        if (boton.getAttribute("onclick").includes(actualSection)) {
            boton.style.backgroundColor = "#333";
            boton.style.color = "white";
        } else {
            boton.style.backgroundColor = "";
            boton.style.color = "";
        }
    });
}


// Function to call the createAnnouncement PHP function using AJAX
function callCreateRequest(userId) {
    
    const nPersons = parseInt(document.getElementById('persons_input_modal').value);

    // Get the selected value from the dropdown
    var selectedValue = document.getElementById("productDropdown").value;
    var valuesArray = selectedValue.split('|');
    const selectedProductId = valuesArray[0];
    const selectedProduct = valuesArray[1];
    
    if (nPersons < 1){
        alert("Persons must be equal or higher than 1.");
    } else {
        // Perform AJAX call
        $.ajax({
            type: "POST",
            url: "../edit_mongo.php",
            data: {
                action: "createRequest", 
                payload: {
                    userId: userId,
                    selectedProductId: selectedProductId,
                    selectedProduct: selectedProduct,
                    nPersons: nPersons
                }
            },
            success: function(response) {
                console.log(response);
                // Reload the page after successful creation
                location.reload();
                alert("Request created.");
            },
            error: function(error) {
                console.error(error);
            }
        });
    }
}

// Function to call the deleteRequest PHP function using AJAX
function callDeleteRequest(userId, requestId) {
    // Use the JavaScript confirm() function
    var confirmation = confirm("Are you sure you want to delete the request?");

    // Check the user's response
    if (confirmation) {
        // Perform AJAX call
        $.ajax({
            type: "POST",
            url: "../edit_mongo.php",
            data: {
                action: "deleteRequest", 
                payload: {
                    userId: userId,
                    requestId: requestId
                }
            },
            success: function(response) {
                console.log(response);
                // Reload the page after successful creation
                location.reload();
            },
            error: function(error) {
                console.error(error);
            }
        });
    } 
}


