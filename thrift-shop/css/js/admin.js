// Admin Panel JavaScript

// Check if user is logged in
function checkAuth() {
    fetch("backend/admin-auth.php", {
        method: "GET",
        credentials: "same-origin"
    })
    .then(response => response.json())
    .then(data => {
        if (!data.logged_in) {
            window.location.href = "admin-login.html";
        }
    })
    .catch(() => {
        window.location.href = "admin-login.html";
    });
}

// Load applications
function loadApplications() {
    fetch("backend/get-applications.php")
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById("applications-table");
            
            if (data.applications && data.applications.length > 0) {
                tableBody.innerHTML = data.applications.map(app => `
                    <tr>
                        <td>${app.id || '-'}</td>
                        <td>${new Date(app.date).toLocaleDateString()}</td>
                        <td>${escapeHtml(app.job)}</td>
                        <td>${escapeHtml(app.name)}</td>
                        <td>${escapeHtml(app.email)}</td>
                        <td>${escapeHtml(app.phone)}</td>
                        <td>${escapeHtml(app.availability)}</td>
                        <td>${escapeHtml(app.experience || '-')}</td>
                        <td>${escapeHtml(app.why || '-')}</td>
                        <td>${app.resume ? `<a href="${escapeHtml(app.resume)}" target="_blank">View</a>` : '-'}</td>
                        <td>
                            <select class="select-status" data-id="${app.id}" onchange="updateStatus(${app.id}, this.value)">
                                <option value="pending" ${app.status === 'pending' ? 'selected' : ''}>Pending</option>
                                <option value="reviewed" ${app.status === 'reviewed' ? 'selected' : ''}>Reviewed</option>
                                <option value="interview" ${app.status === 'interview' ? 'selected' : ''}>Interview</option>
                                <option value="hired" ${app.status === 'hired' ? 'selected' : ''}>Hired</option>
                                <option value="rejected" ${app.status === 'rejected' ? 'selected' : ''}>Rejected</option>
                            </select>
                        </td>
                        <td>
                            <span class="status-badge status-${app.status || 'pending'}">
                                ${app.status || 'Pending'}
                            </span>
                        </td>
                    </tr>
                `).join("");
            } else {
                tableBody.innerHTML = '<tr><td colspan="12" style="text-align: center;">No applications yet.</td></tr>';
            }
        })
        .catch(error => {
            console.error("Error loading applications:", error);
            document.getElementById("applications-table").innerHTML = '<tr><td colspan="12" style="text-align: center;">Error loading applications. Please refresh.</td></tr>';
        });
}

// Update application status
function updateStatus(id, status) {
    fetch("backend/update-application-status.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ id: id, status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadApplications();
        }
    });
}

function refreshApplications() {
    loadApplications();
}

// Helper function to escape HTML
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Run on page load
document.addEventListener("DOMContentLoaded", () => {
    checkAuth();
    loadApplications();
});