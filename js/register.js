document.getElementById("registerForm").addEventListener("submit", function(event) {
    event.preventDefault();

    let formData = new FormData(this);

    fetch("register.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "error") {
            document.getElementById("errorMsg").innerText = data.message;
            document.getElementById("errorMsg").style.color = "red";
        } else {
            alert(data.message);
            window.location.href = "dashboard.php"; // Redirect on success
        }
    })
    .catch(error => console.error("Error:", error));
});
