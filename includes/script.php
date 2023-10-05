<script>
    function confirmDelete(id) {
    if (confirm("Are you sure you want to delete this record?")) {
        window.location.href = '../blog/delete.php?id=' + id;
    }
}

document.addEventListener("DOMContentLoaded", function() {
    const categorySelect = document.getElementById("category");
    const subcategorySelect = document.getElementById("subcategory");

    function updateSubcategories() {
        const selectedCategoryId = categorySelect.value;
        if (!selectedCategoryId) {
            subcategorySelect.innerHTML = '<option value="">No Sub-Category</option>';
            return;
        }

        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    try {
                        const subcategories = JSON.parse(xhr.responseText);
                        subcategorySelect.innerHTML = '<option value="">Select Sub-Category</option>';

                        subcategories.forEach(function(subcategory) {
                            const option = document.createElement("option");
                            option.value = subcategory.id;
                            option.textContent = subcategory.name;

                            if (subcategory.id == <?= json_encode($selecdtedSubcategory) ?>) {
                                option.selected = true;
                            }
                            subcategorySelect.appendChild(option);
                        });
                    } catch (error) {
                        console.error("Error parsing JSON response:", error);
                    }
                } else {
                    console.error("Request failed with status:", xhr.status);
                    console.error("Response text:", xhr.responseText);
                }
            }
        };
        xhr.open("GET", `/blogs-oops/category/getSubcategories.php?category_id=${selectedCategoryId}`, true);
        xhr.send();
    }
    updateSubcategories();

    categorySelect.addEventListener("change", updateSubcategories);
});
</script>