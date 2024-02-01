// Include jQuery library
document.write('<script src="../../lib/jquery-3.7.1.js"><\/script>');

// Initialize the actualSection variable with the current section value
var actualSection = '<?= $section; ?>';

function showSection(newSection) {
    // Check if the selected section is different from the current section
    if (actualSection !== newSection) {
        // Update the actualSection variable
        actualSection = newSection;

        // Perform an AJAX call to change the section
        $.post("", { section: newSection })
            .done(function () {
                // Reload the content of the div with id "offersContent" and change the button style
                $("#offersContent").load(location.href + " #offersContent" );
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
