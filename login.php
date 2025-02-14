<?php
require_once 'config.php';  // Include database connection

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Login</h3>
                    </div>
                    <div class="card-body">
                        <!-- Login Form -->
                        <form id="loginForm">
                            <!-- Email input -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <!-- Password input -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <!-- Remember me checkbox -->
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>

                            <!-- Error message container -->
                            <div id="errorMessage" class="alert alert-danger d-none"></div>

                            <!-- Submit button -->
                            <button type="submit" class="btn btn-primary w-100" id="loginButton">
                                <span class="button-text">Login</span>
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('loginForm').addEventListener('submit', async function(e) {
        e.preventDefault(); // Prevent form from submitting normally

        // Get form elements
        const form = this;
        const errorMessage = document.getElementById('errorMessage');
        const button = document.getElementById('loginButton');
        const buttonText = button.querySelector('.button-text');
        const spinner = button.querySelector('.spinner-border');

        // Show loading state
        button.disabled = true;
        buttonText.textContent = 'Logging in...';
        spinner.classList.remove('d-none');
        errorMessage.classList.add('d-none');

        try {
            // Send form data to process_login.php
            const response = await fetch('process_login.php', {
                method: 'POST',
                body: new FormData(form)
            });

            const data = await response.json();

            if (data.success) {
                // If login successful, redirect to dashboard
                window.location.href = data.redirect;
            } else {
                // If login failed, show error message
                errorMessage.textContent = data.message;
                errorMessage.classList.remove('d-none');
            }
        } catch (error) {
            // If there's an error, show generic error message
            errorMessage.textContent = 'An error occurred. Please try again.';
            errorMessage.classList.remove('d-none');
        } finally {
            // Reset button state
            button.disabled = false;
            buttonText.textContent = 'Login';
            spinner.classList.add('d-none');
        }
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
