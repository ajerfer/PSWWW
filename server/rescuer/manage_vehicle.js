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
                $("#storageContent").load(location.href + " #storageContent" );
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

function callLoadItem (userId, itemId, quantity, max) {
    
    if (quantity < 1 || max < quantity){
        alert("The quantity must be in the interval [1-" + quantity + "].");
    } else {
        // Perform AJAX call
        $.ajax({
            type: "POST",
            url: "../edit_mongo.php", // Change to the correct URL
            data: {
                action: "loadItem", 
                payload: {
                    userId: userId,
                    itemId: itemId,
                    quantity: quantity
                }
            },
            success: function(response) {
                console.log(response);
                // Reload the page after successful creation
                location.reload();
                alert("Item loaded.");
            },
            error: function(error) {
                console.error(error);
            }
        });
    }
}