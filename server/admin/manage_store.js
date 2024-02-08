

// Function to handle category filtering
function handleCategoryFilter(categoryId) {
    const checkboxCategory = document.querySelector('#cat_'+categoryId);
    var style = (checkboxCategory.checked) ? 'block' : 'none';

    // Get all category checkboxes
    const itemBoxes = document.querySelectorAll('.item-box');

    // Loop through each item box
    itemBoxes.forEach(function (itemBox) {
        // Check if the item belongs to the selected category
        const categoryIdAttribute = itemBox.dataset.category;
        if (categoryIdAttribute === categoryId) {
            itemBox.style.display = style;
        }
    });
}

// Function to select all filters
function selectAllFilters() {
    const checkboxes = document.querySelectorAll('.category-checkbox');
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = true;
        handleCategoryFilter(checkbox.id.split('_')[1]); // Call filter handler for each checkbox
    });
}

// Function to clear all filters
function clearAllFilters() {
    const checkboxes = document.querySelectorAll('.category-checkbox');
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = false;
        handleCategoryFilter(checkbox.id.split('_')[1]); // Call filter handler for each checkbox
    });
}

function toggleCategories() {
    var categories = document.getElementById('categories');
    categories.style.display = (categories.style.display === 'none') ? 'block' : 'none';
    var button = document.getElementById('buttonToggleCategories');
    button.textContent = (categories.style.display === 'none') ? 'Show Categories' : 'Hide Categories';
}
