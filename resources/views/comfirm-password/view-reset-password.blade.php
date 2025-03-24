<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --error: #ef4444;
            --success: #10b981;
            --background: #f9fafb;
            --card-bg: #ffffff;
            --text: #1f2937;
            --text-secondary: #6b7280;
            --border: #e5e7eb;
            --input-focus: rgba(79, 70, 229, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background);
            color: var(--text);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 1rem;
        }

        .container {
            width: 100%;
            max-width: 450px;
            background-color: var(--card-bg);
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            padding: 2.5rem;
            transition: transform 0.3s ease;
        }

        .container:hover {
            transform: translateY(-5px);
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 2rem;
            color: var(--text);
            text-align: center;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text);
        }

        .input-wrapper {
            position: relative;
        }

        input {
            width: 100%;
            padding: 0.875rem 1rem;
            padding-right: 3rem;
            border: 2px solid var(--border);
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.2s ease;
            background-color: var(--card-bg);
            color: var(--text);
        }

        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--input-focus);
        }

        .toggle-password {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toggle-password:hover {
            color: var(--primary);
        }

        .toggle-password svg {
            width: 1.25rem;
            height: 1.25rem;
        }

        .validation-message {
            display: flex;
            align-items: center;
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .validation-message svg {
            width: 1rem;
            height: 1rem;
            margin-right: 0.5rem;
            flex-shrink: 0;
        }

        .validation-message.error {
            color: var(--error);
        }

        .validation-message.success {
            color: var(--success);
        }

        .buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        button {
            flex: 1;
            padding: 0.875rem 1.5rem;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-cancel {
            background-color: transparent;
            border: 2px solid var(--border);
            color: var(--text-secondary);
        }

        .btn-cancel:hover {
            background-color: var(--background);
        }

        .btn-reset {
            background-color: var(--primary);
            border: 2px solid var(--primary);
            color: white;
        }

        .btn-reset:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        .btn-reset:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        /* Password strength indicator */
        .password-strength {
            height: 4px;
            background-color: var(--border);
            border-radius: 2px;
            margin-top: 0.75rem;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            width: 0;
            transition: width 0.3s ease, background-color 0.3s ease;
        }

        .password-strength-text {
            font-size: 0.75rem;
            margin-top: 0.25rem;
            text-align: right;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-group {
            animation: fadeIn 0.3s ease forwards;
        }

        .form-group:nth-child(2) {
            animation-delay: 0.1s;
        }

        .buttons {
            animation: fadeIn 0.3s ease forwards;
            animation-delay: 0.2s;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .container {
                padding: 1.5rem;
            }

            h1 {
                font-size: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Đặt lại mật khẩu của bạn</h1>
        <form id="resetPasswordForm">
            <div class="form-group">
                <label for="password">Mật Khẩu Mới</label>
                <input type="hidden" name="email" id="email" value="{{ $email ?? '' }}">
                <input type="hidden" name="token" id="token" value="{{ $token ?? '' }}">

                <div class="input-wrapper">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        autocomplete="new-password"
                        required
                    >
                    <button type="button" class="toggle-password" data-target="password">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                <div class="password-strength">
                    <div class="password-strength-bar"></div>
                </div>
                <div class="password-strength-text"></div>
                <div class="validation-message" id="passwordValidation">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Phải có ít nhất 8 ký tự
                </div>
            </div>

            <div class="form-group">
                <label for="confirmPassword">Xác nhận mật khẩu</label>
                <div class="input-wrapper">
                    <input 
                        type="password" 
                        id="confirmPassword" 
                        name="confirmPassword" 
                        autocomplete="new-password"
                        required
                    >
                    <button type="button" class="toggle-password" data-target="confirmPassword">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                <div class="validation-message" id="confirmPasswordValidation">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Cả hai mật khẩu phải trùng nhau
                </div>
            </div>

            <div class="buttons">
                <button type="button" class="btn-cancel">Hủy bỏ</button>
                <button type="submit" class="btn-reset" id="resetButton" disabled>Xác Nhận</button>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Fix for the toggle password functionality
            $('.toggle-password').click(function () {
                const targetId = $(this).data('target');
                const input = $('#' + targetId);
                
                // Toggle between password and text
                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    $(this).html(`
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    `);
                } else {
                    input.attr('type', 'password');
                    $(this).html(`
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    `);
                }
            });
    
            $('#password, #confirmPassword').on('input', function () {
                const password = $('#password').val();
                const confirmPassword = $('#confirmPassword').val();
                let isValid = true;
    
                if (password.length < 8) {
                    $('#passwordValidation').removeClass('success').addClass('error');
                    $('#passwordValidation').html(`
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Phải có ít nhất 8 ký tự
                    `);
                    isValid = false;
                } else {
                    $('#passwordValidation').removeClass('error').addClass('success');
                    $('#passwordValidation').html(`
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Mật khẩu hợp lệ
                    `);
                }
    
                if (password !== confirmPassword || confirmPassword === '') {
                    $('#confirmPasswordValidation').removeClass('success').addClass('error');
                    $('#confirmPasswordValidation').html(`
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Cả hai mật khẩu phải trùng nhau
                    `);
                    isValid = false;
                } else {
                    $('#confirmPasswordValidation').removeClass('error').addClass('success');
                    $('#confirmPasswordValidation').html(`
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Mật khẩu trùng khớp
                    `);
                }
    
                // Update password strength
                const strength = checkPasswordStrength(password);
                updatePasswordStrength(strength);
    
                $('#resetButton').prop('disabled', !isValid);
            });
    
            function checkPasswordStrength(password) {
                let strength = 0;
    
                if (password.length >= 8) strength += 25;
                if (password.match(/[a-z]+/)) strength += 25;
                if (password.match(/[A-Z]+/)) strength += 25;
                if (password.match(/[0-9]+/) || password.match(/[^a-zA-Z0-9]+/)) strength += 25;
    
                return strength;
            }
    
            function updatePasswordStrength(strength) {
                $('.password-strength-bar').css('width', strength + '%');
    
                if (strength < 25) {
                    $('.password-strength-bar').css('backgroundColor', '#ef4444');
                    $('.password-strength-text').text('Yếu').css('color', '#ef4444');
                } else if (strength < 50) {
                    $('.password-strength-bar').css('backgroundColor', '#f97316');
                    $('.password-strength-text').text('Trung bình').css('color', '#f97316');
                } else if (strength < 75) {
                    $('.password-strength-bar').css('backgroundColor', '#eab308');
                    $('.password-strength-text').text('Tốt').css('color', '#eab308');
                } else {
                    $('.password-strength-bar').css('backgroundColor', '#10b981');
                    $('.password-strength-text').text('Mạnh').css('color', '#10b981');
                }
            }
    
            $('#resetPasswordForm').submit(function (e) {
                e.preventDefault();
    
                const email = $('#email').val();
                const token = $('#token').val();
                const password = $('#password').val();
                const confirmPassword = $('#confirmPassword').val();
                
                $.ajax({
                    url: '/api/reset-password',
                    type: 'POST',
                    data: {
                        email: email,
                        token: token,
                        password: password,
                        password_confirmation: confirmPassword, 
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        alert(response.message);
                        window.location.href = '/api/login'; 
                    },
                    error: function (xhr) {
                        const error = xhr.responseJSON.error || 'Đã có lỗi xảy ra.';
                        alert(error);
                    }
                });
            });
        });
    </script>
</body>
</html>

