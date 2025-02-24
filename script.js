$(document).ready(function () {
    $("form").submit(function (event) {
        let password = $("#password").val();
        let rpassword = $("#rpassword").val();

        if (password !== rpassword) {
            alert("Passwords do not match!");
            event.preventDefault();
        }
    });
});