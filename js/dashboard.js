document.getElementById("selectAll").addEventListener("change", function () {
    let checkboxes = document.querySelectorAll(".userCheckbox");
    checkboxes.forEach(cb => cb.checked = this.checked);
});

// Toggle User Status
document.querySelectorAll(".toggle-status-btn").forEach(button => {
    button.addEventListener("click", function () {
        let userId = this.getAttribute("data-id");
        let newStatus = this.getAttribute("data-status") === 't' ? 'f' : 't';

        fetch("toggle_status.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `id=${userId}&status=${newStatus}`
        })
            .then(response => response.text())
            .then(data => location.reload());
    });
});

// Bulk Reject Users
document.getElementById("bulkRejectBtn").addEventListener("click", function () {
    let selectedIds = [];
    document.querySelectorAll(".userCheckbox:checked").forEach(cb => selectedIds.push(cb.value));

    if (selectedIds.length > 0) {
        fetch("bulk_reject.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `ids=${JSON.stringify(selectedIds)}`
        })
            .then(response => response.text())
            .then(data => location.reload());
    }
});

document.getElementById("bulkApproveBtn").addEventListener("click", function () {
    let selectedIds = [];
    document.querySelectorAll(".userCheckbox:checked").forEach(cb => selectedIds.push(cb.value));

    if (selectedIds.length > 0) {
        fetch("bulk_approve.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `ids=${JSON.stringify(selectedIds)}`
        })
            .then(response => response.text())
            .then(data => location.reload());
    }
});

document.getElementById("saveChangesBtn").addEventListener("click", function () {
    alert("Table changes saved (Implement backend logic).");
});