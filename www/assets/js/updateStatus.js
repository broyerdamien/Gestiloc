document.addEventListener('DOMContentLoaded', function () {
    const selects = document.querySelectorAll('.form-select');

    selects.forEach(select => {
        // Capture the initial status when the page loads
        const initialStatus = select.value;
        const avisEcheanceId = select.dataset.id;
        const row = select.closest('tr');

        // Remove any existing status classes
        row.classList.remove('status-en-attente', 'status-partiel', 'status-paye');

        // Add the initial status class based on the current value
        const initialStatusClass = `status-${initialStatus.toLowerCase().replace(/ /g, '-')}`;
        row.classList.add(initialStatusClass);

        // Add change event listener
        select.addEventListener('change', function () {
            const newStatus = this.value;

            // Remove all existing status classes
            row.classList.remove('status-en-attente', 'status-partiel', 'status-paye');

            // Add the new status class
            const statusClass = `status-${newStatus.toLowerCase().replace(/ /g, '-')}`;
            row.classList.add(statusClass);

            // Send update to server
            fetch(`/avis/echeance/${avisEcheanceId}/update-payment-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    payment_status: newStatus
                })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur lors de la mise à jour');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Mise à jour réussie:', data.message);
                })
                .catch(error => {
                    console.error('Erreur:', error);
                });
        });
    });
});