

function handleCategoryFilter(categoryId) {
    const checkboxCategory = document.querySelector('#cat_'+categoryId);
    var style = (checkboxCategory.checked) ? 'block' : 'none';

    const itemBoxes = document.querySelectorAll('.item-box');


    itemBoxes.forEach(function (itemBox) {
        const categoryIdAttribute = itemBox.dataset.category;
        if (categoryIdAttribute === categoryId) {
            itemBox.style.display = style;
        }
    });
}

function selectAllFilters() {
    const checkboxes = document.querySelectorAll('.category-checkbox');
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = true;
        handleCategoryFilter(checkbox.id.split('_')[1]); 
    });
}

function clearAllFilters() {
    const checkboxes = document.querySelectorAll('.category-checkbox');
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = false;
        handleCategoryFilter(checkbox.id.split('_')[1]); 
    });
}

function toggleCategories() {
    var categories = document.getElementById('categories');
    categories.style.display = (categories.style.display === 'none') ? 'block' : 'none';
    var button = document.getElementById('buttonToggleCategories');
    button.textContent = (categories.style.display === 'none') ? 'Show Categories' : 'Hide Categories';
}
