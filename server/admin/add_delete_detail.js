function addDetail() {
    console.log("addDetail() called");
    var detailsList = document.getElementById("detailsList");
    var newDetail = document.createElement("li");

    newDetail.innerHTML = `
        <label for="newDetailName">Detail name:</label>
        <input type="text" id="newDetailName" name="detailName[]" required>
        <br><br>
        <label for="newDetailValue">Detail value:</label>
        <input type="text" id="newDetailValue" name="detailValue[]" required>
        <button type="button" class="deleteDetailBtn" onclick="deleteDetail(this)">Delete</button>
        <br><br>
    `;

    detailsList.appendChild(newDetail);
}
function deleteDetail(button) {
    var index = button.getAttribute("data-index");
    var detailItem = button.closest("li");
    detailItem.parentNode.removeChild(detailItem);
}