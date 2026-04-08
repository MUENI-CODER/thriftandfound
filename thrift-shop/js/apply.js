// Job application form handling

document.addEventListener("DOMContentLoaded", () => {
    const urlParams = new URLSearchParams(window.location.search);
    const jobParam = urlParams.get("job");
    const jobSelect = document.getElementById("job");
    
    if (jobParam && jobSelect) {
        for (let i = 0; i < jobSelect.options.length; i++) {
            if (jobSelect.options[i].value === decodeURIComponent(jobParam)) {
                jobSelect.selectedIndex = i;
                break;
            }
        }
    }

    const form = document.getElementById("application-form");
    if (form) {
        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            
            const formData = new FormData(form);
            const messageDiv = document.getElementById("form-message");
            
            try {
                const response = await fetch("backend/send-application.php", {
                    method: "POST",
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    messageDiv.innerHTML = `<p style="color: #2c6e3c; background: #e0f2e6; padding: 12px; border-radius: 8px;">✓ Application submitted successfully!</p>`;
                    form.reset();
                } else {
                    messageDiv.innerHTML = `<p style="color: #721c24; background: #f8d7da; padding: 12px; border-radius: 8px;">✗ ${result.message || "Error submitting application."}</p>`;
                }
            } catch (error) {
                messageDiv.innerHTML = "<p style='color: #721c24; background: #f8d7da; padding: 12px; border-radius: 8px;'>✗ Connection error.</p>";
            }
            
            setTimeout(() => {
                messageDiv.innerHTML = "";
            }, 5000);
        });
    }
});