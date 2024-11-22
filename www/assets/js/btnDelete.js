document.addEventListener('DOMContentLoaded', function() {
    setupDeleteButtons();
    setupCheckboxListeners();
});

function setupDeleteButtons() {
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', handleDeleteClick);
    });
}

function handleDeleteClick(event) {
    event.preventDefault();
    const avisEcheanceId = this.dataset.id;
    const csrfToken = this.dataset.token;

    if (confirm('Are you sure you want to delete this item?')) {
        fetch(`/avis/echeance/${avisEcheanceId}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': csrfToken
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                this.closest('tr').remove();
            })
            .catch(error => {
                console.error('Erreur lors de la suppression:', error);
                alert('An error occurred: ' + error.message);
            });
    }
}

function setupCheckboxListeners() {
    const checkboxes = document.querySelectorAll(".delete-checkbox");
    const deleteButton = document.querySelector(".delete-selected-btn");

    function toggleDeleteButton() {
        const isAnyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
        deleteButton.style.display = isAnyChecked ? 'inline-block' : 'none';
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', toggleDeleteButton);
    });

    toggleDeleteButton();
}
