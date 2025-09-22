<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Login - ALI Portfolio</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="{{ asset('favicon/favicon.ico') }}" rel="icon">

    <!-- Gen Z Modern Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Modern Icon Libraries -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            /* Gen Z Color Palette */
            --electric-purple: #8b5cf6;
            --cyber-pink: #ec4899;
            --neon-green: #10b981;
            --aurora-blue: #06b6d4;
            --neon-yellow: #fbbf24;
            --dark-surface: #0f0f23;
            --card-surface: #1a1a2e;

            /* Gradients */
            --gradient-cyber: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%);
            --gradient-aurora: linear-gradient(135deg, #06b6d4 0%, #8b5cf6 50%, #ec4899 100%);
            --gradient-glow: linear-gradient(135deg, #8b5cf6 0%, #ec4899 50%, #06b6d4 100%);

            /* Effects */
            --glow-purple: 0 0 20px rgba(139, 92, 246, 0.4), 0 0 40px rgba(139, 92, 246, 0.2);
            --glow-pink: 0 0 20px rgba(236, 72, 153, 0.4), 0 0 40px rgba(236, 72, 153, 0.2);
        }

        body {
            font-family: 'Space Grotesk', system-ui, sans-serif;
            margin: 0;
            padding: 0;
        }

        .login-container {
            min-height: 100vh;
            background: var(--dark-surface);
            background-image:
                radial-gradient(circle at 20% 20%, rgba(139, 92, 246, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(236, 72, 153, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(6, 182, 212, 0.2) 0%, transparent 50%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%238b5cf6" fill-opacity="0.05"><circle cx="7" cy="7" r="1"/><circle cx="27" cy="7" r="1"/><circle cx="47" cy="7" r="1"/><circle cx="7" cy="27" r="1"/><circle cx="27" cy="27" r="1"/><circle cx="47" cy="27" r="1"/><circle cx="7" cy="47" r="1"/><circle cx="27" cy="47" r="1"/><circle cx="47" cy="47" r="1"/></g></g></svg>');
            animation: float-pattern 20s ease-in-out infinite;
        }

        @keyframes float-pattern {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-10px, -10px); }
        }

        .login-card {
            background: rgba(26, 26, 46, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1.5rem;
            box-shadow:
                0 25px 50px rgba(0, 0, 0, 0.5),
                0 0 0 1px rgba(139, 92, 246, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 420px;
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .login-card:hover {
            transform: translateY(-4px);
            box-shadow:
                0 35px 70px rgba(0, 0, 0, 0.6),
                0 0 0 1px rgba(139, 92, 246, 0.2),
                var(--glow-purple);
        }

        .login-header {
            background: var(--gradient-cyber);
            background-size: 200% 200%;
            animation: gradient-shift 4s ease infinite;
            color: white;
            text-align: center;
            padding: 2.5rem 2rem;
            position: relative;
            overflow: hidden;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.8s ease;
        }

        .login-header:hover::before {
            left: 100%;
        }

        .login-header .icon-container {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            width: 4rem;
            height: 4rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            animation: float-gentle 6s ease-in-out infinite;
        }

        @keyframes float-gentle {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-8px) rotate(2deg); }
            66% { transform: translateY(4px) rotate(-2deg); }
        }

        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .login-header h3 {
            margin: 0;
            font-weight: 700;
            font-size: 1.75rem;
            font-family: 'Poppins', sans-serif;
            letter-spacing: -0.025em;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .login-header p {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
            font-weight: 500;
            font-size: 1rem;
        }

        .login-body {
            padding: 2.5rem 2rem;
            background: rgba(15, 15, 35, 0.8);
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-floating {
            position: relative;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.75rem;
            color: white;
            font-size: 1rem;
            padding: 1rem 1.25rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            width: 100%;
            box-sizing: border-box;
            height: auto;
            min-height: 3.5rem;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--electric-purple);
            background: rgba(255, 255, 255, 0.08);
            box-shadow:
                0 0 0 3px rgba(139, 92, 246, 0.2),
                var(--glow-purple);
            transform: translateY(-2px);
        }

        .form-floating label {
            position: absolute;
            top: 50%;
            left: 1.25rem;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.6);
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            pointer-events: none;
            background: transparent;
            padding: 0;
        }

        .form-floating .form-control:focus + label,
        .form-floating .form-control:not(:placeholder-shown) + label {
            top: 0;
            left: 1rem;
            transform: translateY(-50%) scale(0.85);
            color: var(--electric-purple);
            background: var(--card-surface);
            padding: 0 0.5rem;
            font-weight: 600;
        }

        .btn-login {
            background: var(--gradient-cyber);
            background-size: 200% 200%;
            border: none;
            border-radius: 0.75rem;
            padding: 1rem 2rem;
            font-weight: 600;
            width: 100%;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            animation: gradient-shift 4s ease infinite;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: var(--glow-pink);
            background-position: 100% 0;
        }

        .btn-login:active {
            transform: translateY(-1px);
        }

        .alert {
            border-radius: 0.75rem;
            border: none;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            font-weight: 500;
            backdrop-filter: blur(10px);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #6ee7b7;
        }

        .remember-me {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 1.5rem 0;
            font-size: 0.9rem;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-check-input {
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 0.375rem;
            background: transparent;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-check-input:checked {
            background: var(--electric-purple);
            border-color: var(--electric-purple);
            box-shadow: var(--glow-purple);
        }

        .form-check-label {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
            cursor: pointer;
        }

        .back-link {
            text-align: center;
            margin-top: 2rem;
        }

        .back-link a {
            color: var(--electric-purple);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            background: rgba(139, 92, 246, 0.1);
            border: 1px solid rgba(139, 92, 246, 0.3);
            transition: all 0.3s ease;
        }

        .back-link a:hover {
            background: rgba(139, 92, 246, 0.2);
            transform: translateY(-2px);
            box-shadow: var(--glow-purple);
        }

        .text-muted {
            color: rgba(255, 255, 255, 0.6) !important;
            font-size: 0.875rem;
        }

        .text-muted:hover {
            color: var(--cyber-pink) !important;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .login-container {
                padding: 1rem;
            }

            .login-card {
                max-width: 100%;
                margin: 0;
            }

            .login-header {
                padding: 2rem 1.5rem;
            }

            .login-body {
                padding: 2rem 1.5rem;
            }

            .login-header h3 {
                font-size: 1.5rem;
            }
        }

        /* Accessibility */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="icon-container">
                    <i class="fas fa-shield-halved fa-2x"></i>
                </div>
                <h3>Admin Panel</h3>
                <p>ALI Portfolio Dashboard</p>
            </div>
            
            <div class="login-body">
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </div>
                @endif

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    <div class="form-group">
                        <div class="form-floating">
                            <input type="email" class="form-control" id="email" name="email"
                                   placeholder=" " value="{{ old('email') }}" required autofocus
                                   aria-describedby="email-help">
                            <label for="email">Email Address</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-floating">
                            <input type="password" class="form-control" id="password" name="password"
                                   placeholder=" " required
                                   aria-describedby="password-help">
                            <label for="password">Password</label>
                        </div>
                    </div>

                    <div class="remember-me">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>
                        
                        <a href="#" class="text-muted small" aria-label="Reset your password">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn-login" aria-describedby="login-help">
                        <i class="fas fa-rocket me-2"></i>
                        Launch Dashboard
                    </button>
                </form>
                
                <div class="back-link">
                    <a href="{{ route('home') }}" aria-label="Return to main website">
                        <i class="fas fa-arrow-left"></i>
                        Back to Portfolio
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Enhanced form functionality with modern UX
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const submitButton = document.querySelector('.btn-login');

            // Auto focus with smooth animation
            setTimeout(() => {
                emailInput.focus();
            }, 300);

            // Enhanced form validation
            function validateEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            function showError(message) {
                // Remove existing error alerts
                const existingAlert = document.querySelector('.alert-validation');
                if (existingAlert) {
                    existingAlert.remove();
                }

                // Create new error alert
                const alert = document.createElement('div');
                alert.className = 'alert alert-danger alert-validation';
                alert.innerHTML = `
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    ${message}
                `;

                // Insert before form
                form.parentNode.insertBefore(alert, form);

                // Animate in
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    alert.style.transition = 'all 0.3s ease';
                    alert.style.opacity = '1';
                    alert.style.transform = 'translateY(0)';
                }, 10);
            }

            // Real-time validation feedback
            emailInput.addEventListener('input', function() {
                const isValid = this.value === '' || validateEmail(this.value);
                this.style.borderColor = isValid ? '' : '#ef4444';
            });

            // Form submission with loading state
            form.addEventListener('submit', function(e) {
                const email = emailInput.value.trim();
                const password = passwordInput.value;

                // Validation
                if (!email || !password) {
                    e.preventDefault();
                    showError('Please fill in all fields');
                    return false;
                }

                if (!validateEmail(email)) {
                    e.preventDefault();
                    showError('Please enter a valid email address');
                    emailInput.focus();
                    return false;
                }

                // Show loading state
                submitButton.innerHTML = `
                    <i class="fas fa-spinner fa-spin me-2"></i>
                    Launching...
                `;
                submitButton.disabled = true;

                // Allow form to submit normally
                return true;
            });

            // Keyboard navigation
            emailInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    passwordInput.focus();
                }
            });

            passwordInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    submitButton.click();
                }
            });

            // Enhanced accessibility
            const inputs = [emailInput, passwordInput];
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.setAttribute('aria-describedby', this.id + '-help');
                });

                input.addEventListener('invalid', function() {
                    this.setAttribute('aria-invalid', 'true');
                });

                input.addEventListener('input', function() {
                    this.removeAttribute('aria-invalid');
                });
            });
        });

        // Add subtle particle effect
        function createParticle() {
            const particle = document.createElement('div');
            particle.style.cssText = `
                position: absolute;
                width: 4px;
                height: 4px;
                background: linear-gradient(45deg, #8b5cf6, #ec4899);
                border-radius: 50%;
                pointer-events: none;
                opacity: 0.6;
                animation: particle-float 8s linear infinite;
                left: ${Math.random() * 100}%;
                top: 100%;
            `;

            document.querySelector('.login-container').appendChild(particle);

            setTimeout(() => {
                if (particle.parentNode) {
                    particle.parentNode.removeChild(particle);
                }
            }, 8000);
        }

        // Create particles periodically
        setInterval(createParticle, 3000);
    </script>

    <style>
        @keyframes particle-float {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 0.6;
            }
            90% {
                opacity: 0.6;
            }
            100% {
                transform: translateY(-100vh) rotate(360deg);
                opacity: 0;
            }
        }
    </style>
</body>

</html>
