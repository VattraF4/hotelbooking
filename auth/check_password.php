<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Strength Checker</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css"> -->
    <style>
        .feedback {
            margin-top: 1rem;
        }

        .strength-meter {
            height: 5px;
            margin-top: 5px;
            background-color: #e9ecef;
            border-radius: 3px;
            overflow: hidden;
        }

        .strength-meter-fill {
            height: 100%;
            width: 0%;
            transition: width 0.3s ease;
        }

        .feedback-item {
            margin-bottom: 0.3rem;
        }

        .feedback-good {
            color: #28a745;
            /* green */
        }

        .feedback-bad {
            color: #dc3545;
            /* red */
        }

        .feedback-warning {
            color: #fd7e14;
            /* orange */
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
        }

        .password-container {
            position: relative;
        }

        .hidden {
            display: none;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <!-- <form>
            <div class="mb-3 password-container">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" aria-describedby="passwordFeedback">
                <button type="button" class="password-toggle" id="togglePassword">
                    <i class="bi bi-eye-slash" id="showPassword"></i>
                </button>
                <div class="strength-meter">
                    <div class="strength-meter-fill" id="strengthMeter"></div>
                </div>
                <div id="passwordFeedback" class="invalid-feedback">
                    Please enter a valid password.
                </div>
            </div>
        </form> -->
        <div class="feedback" id="feedbackContainer">
            <p id="strengthLevel" class="fw-bold"></p>
            <p id="strengthScore"></p>
            <ul id="strengthFeedback" class="list-unstyled"></ul>
        </div>
    </div>

    <script>
        const passwordInput = document.querySelector('#password');
        const usernameInput = document.querySelector('#username');
        const strengthLevel = document.querySelector('#strengthLevel');
        const strengthScore = document.querySelector('#strengthScore');
        const strengthFeedback = document.querySelector('#strengthFeedback');
        const strengthMeter = document.querySelector('#strengthMeter');
        const togglePassword = document.querySelector('#togglePassword');
        const showPassword = document.querySelector('#showPassword');
        const feedbackContainer = document.querySelector('#feedbackContainer');

        passwordInput.addEventListener('input', () => {
            const password = passwordInput.value;
            if (password.length === 0) {
                clearFeedback();
                return;
            }

            const result = checkPasswordStrength(password);
            updateFeedback(result);
        });

        // Hide feedback when clicking/tabbing to another input
        usernameInput.addEventListener('focus', () => {
            feedbackContainer.classList.add('hidden');
        });

        // Show feedback when returning to password input
        passwordInput.addEventListener('focus', () => {
            if (passwordInput.value.length > 0) {
                feedbackContainer.classList.remove('hidden');
            }
        });

        togglePassword.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle the eye / eye slash icon
            if (type === 'password') {
                showPassword.classList.remove('bi-eye');
                showPassword.classList.add('bi-eye-slash');
            } else {
                showPassword.classList.remove('bi-eye-slash');
                showPassword.classList.add('bi-eye');
            }
        });

        function clearFeedback() {
            strengthLevel.textContent = '';
            strengthScore.textContent = '';
            strengthFeedback.innerHTML = '';
            strengthMeter.style.width = '0%';
            feedbackContainer.classList.add('hidden');
        }

        function updateFeedback(result) {
            feedbackContainer.classList.remove('hidden');
            strengthLevel.textContent = `Password Strength: ${result.level}`;
            strengthScore.textContent = `Score: ${result.strength}/10`;
            strengthFeedback.innerHTML = '';

            // Update strength meter color and width
            const percentage = result.strength * 10;
            strengthMeter.style.width = `${percentage}%`;

            if (result.strength >= 8) {
                strengthMeter.style.backgroundColor = '#28a745';
                strengthLevel.style.color = '#28a745';
            } else if (result.strength >= 6) {
                strengthMeter.style.backgroundColor = '#17a2b8';
                strengthLevel.style.color = '#17a2b8';
            } else if (result.strength >= 4) {
                strengthMeter.style.backgroundColor = '#ffc107';
                strengthLevel.style.color = '#ffc107';
            } else if (result.strength >= 2) {
                strengthMeter.style.backgroundColor = '#fd7e14';
                strengthLevel.style.color = '#fd7e14';
            } else {
                strengthMeter.style.backgroundColor = '#dc3545';
                strengthLevel.style.color = '#dc3545';
            }

            result.feedback.forEach(item => {
                const li = document.createElement('li');
                li.textContent = item.text;
                li.className = 'feedback-item';

                if (item.type === 'good') {
                    li.classList.add('feedback-good');
                } else if (item.type === 'bad') {
                    li.classList.add('feedback-bad');
                } else if (item.type === 'warning') {
                    li.classList.add('feedback-warning');
                }

                strengthFeedback.appendChild(li);
            });
        }

        function checkPasswordStrength(password) {
            let strength = 0;
            const feedback = [];
            let level = "Very Weak";

            // Contains uppercase letters
            if (/[A-Z]/.test(password)) {
                strength += 2;
                feedback.push({ text: "✓ Contains uppercase letters", type: "good" });
            } else {
                feedback.push({ text: "✗ Consider adding uppercase letters", type: "bad" });
            }

            // Contains lowercase letters
            if (/[a-z]/.test(password)) {
                strength += 2;
                feedback.push({ text: "✓ Contains lowercase letters", type: "good" });
            } else {
                feedback.push({ text: "✗ Consider adding lowercase letters", type: "bad" });
            }

            // Length check
            if (password.length >= 12) {
                strength += 2;
                feedback.push({ text: "✓ Good password length (12+ characters)", type: "good" });
            } else if (password.length >= 8) {
                strength += 1;
                feedback.push({ text: "✓ Minimum length (8+ characters)", type: "good" });
            } else {
                feedback.push({ text: "✗ Password too short (minimum 8 characters required)", type: "bad" });
            }

            // Contains numbers
            if (/[0-9]/.test(password)) {
                strength += 2;
                feedback.push({ text: "✓ Contains numbers", type: "good" });
            } else {
                feedback.push({ text: "✗ Consider adding numbers", type: "bad" });
            }

            // Contains special characters
            if (/[^a-zA-Z0-9]/.test(password)) {
                strength += 2;
                feedback.push({ text: "✓ Contains special characters", type: "good" });
            } else {
                feedback.push({ text: "✗ Consider adding special characters", type: "bad" });
            }

            // Check for common patterns (weak passwords)
            const common = ['password', '123456', 'qwerty', 'letmein', 'welcome'];
            for (const commonPwd of common) {
                if (password.toLowerCase().includes(commonPwd)) {
                    strength = Math.max(1, strength - 2);
                    feedback.push({ text: "⚠ Warning: Contains common password pattern", type: "warning" });
                    break;
                }
            }

            // Determine strength level
            if (strength >= 8) {
                level = "Very Strong";
            } else if (strength >= 6) {
                level = "Strong";
            } else if (strength >= 4) {
                level = "Moderate";
            } else if (strength >= 2) {
                level = "Weak";
            }

            return {
                strength,
                level,
                feedback
            };
        }
    </script>
</body>
</html>