<?php
require_once 'config.php';

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
    <title>Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .password-requirements {
            font-size: 0.875rem;
            color: #6c757d;
        }
        .requirement-met {
            color: #198754;
        }
        .password-toggle {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Create Account</h3>
                    </div>
                    <div class="card-body">
                        <form id="signupForm" novalidate>
                            <!-- Full Name -->
                            <div class="mb-3">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullName" name="fullName" required>
                                <div class="invalid-feedback">Please enter your full name.</div>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <div class="invalid-feedback">Please enter a valid email address.</div>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <span class="input-group-text password-toggle" onclick="togglePassword('password')">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                                <div class="invalid-feedback">Please enter a valid password.</div>
                                <div class="password-requirements mt-2">
                                    <p class="mb-1" id="length">• Minimum 8 characters</p>
                                    <p class="mb-1" id="uppercase">• At least one uppercase letter</p>
                                    <p class="mb-1" id="lowercase">• At least one lowercase letter</p>
                                    <p class="mb-1" id="number">• At least one number</p>
                                    <p class="mb-1" id="special">• At least one special character</p>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                                    <span class="input-group-text password-toggle" onclick="togglePassword('confirmPassword')">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                                <div class="invalid-feedback">Passwords do not match.</div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the Terms and Conditions
                                </label>
                                <div class="invalid-feedback">You must agree to the terms and conditions.</div>
                            </div>

                            <!-- Error message container -->
                            <div id="errorMessage" class="alert alert-danger d-none"></div>

                            <!-- Submit button -->
                            <button type="submit" class="btn btn-primary w-100" id="signupButton">
                                <span class="button-text">Create Account</span>
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                            </button>

                            <div class="mt-3 text-center">
                                Already have an account? <a href="login.php">Login here</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('signupForm');
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirmPassword');
        const errorMessage = document.getElementById('errorMessage');

        // Password validation requirements
        const requirements = {
            length: /.{8,}/,
            uppercase: /[A-Z]/,
            lowercase: /[a-z]/,
            number: /[0-9]/,
            special: /[!@#$%^&*(),.?":{}|<>]/
        };

        // Password input validation
        password.addEventListener('input', function() {
            const value = this.value;
            
            // Check each requirement
            for (const [requirement, regex] of Object.entries(requirements)) {
                const element = document.getElementById(requirement);
                if (regex.test(value)) {
                    element.classList.add('requirement-met');
                } else {
                    element.classList.remove('requirement-met');
                }
            }
        });

        // Form submission
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Reset error states
            errorMessage.classList.add('d-none');
            form.classList.remove('was-validated');

            // Validate password requirements
            let isValid = true;
            for (const [requirement, regex] of Object.entries(requirements)) {
                if (!regex.test(password.value)) {
                    isValid = false;
                    break;
                }
            }

            // Check if passwords match
            if (password.value !== confirmPassword.value) {
                showError('Passwords do not match');
                return;
            }

            if (!isValid) {
                showError('Please meet all password requirements');
                return;
            }

            // Show loading state
            setLoadingState(true);

            try {
                const response = await fetch('process_signup.php', {
                    method: 'POST',
                    body: new FormData(form)
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    showError(data.message);
                }
            } catch (error) {
                showError('An error occurred. Please try again.');
            } finally {
                setLoadingState(false);
            }
        });
    });

    function setLoadingState(isLoading) {
        const button = document.getElementById('signupButton');
        const buttonText = button.querySelector('.button-text');
        const spinner = button.querySelector('.spinner-border');

        button.disabled = isLoading;
        if (isLoading) {
            buttonText.textContent = 'Creating Account...';
            spinner.classList.remove('d-none');
        } else {
            buttonText.textContent = 'Create Account';
            spinner.classList.add('d-none');
        }
    }

    function showError(message) {
        const errorMessage = document.getElementById('errorMessage');
        errorMessage.textContent = message;
        errorMessage.classList.remove('d-none');
    }

    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = input.nextElementSibling.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
    </script>
</body>
</html>
