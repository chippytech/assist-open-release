<style>
        :root {
            --brand-dark: #17272c;
            --brand-primary: #007bff;
        }
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .hero-section { padding: 100px 0; background: linear-gradient(135deg, #fff 0%, #e9ecef 100%); }
        .btn-brand { background-color: var(--brand-primary); color: white; border-radius: 8px; font-weight: 600; transition: transform 0.2s; }
        .btn-brand:hover { transform: translateY(-2px); color: white; opacity: 0.9; }
        .feature-icon { font-size: 2rem; color: var(--brand-primary); margin-bottom: 1rem; }
    </style>
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="/███████_logo.png" height="40" alt="Logo" class="me-2">
                <span class="fw-bold" style="color: var(--brand-dark);">███████</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link mx-2" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link mx-2" href="https://███████.com/███████">About</a></li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-outline-primary px-4" href="/auth">Sign In</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 text-center text-lg-start mb-5 mb-lg-0">
                    <h1 class="display-4 fw-bold mb-3" style="color: var(--brand-dark);">
                        Bringing reliable AI experiences to everyone.
                    </h1>
                    <p class="lead text-muted mb-4">
                        ███████ brings the most powerful AI models together in one clean, seamless interface. 
                        No multiple accounts. No switching platforms. Just fast, intelligent conversations.
                    </p>
                    <div class="d-grid gap-3 d-sm-flex justify-content-sm-center justify-content-lg-start">
                        <a href="/auth" class="btn btn-brand btn-lg px-5">Get Started Free</a>
                        <a href="#features" class="btn btn-light btn-lg px-5 border">Explore Features</a>
                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1">
                    <div class="card border-0 shadow-lg text-center p-4 p-md-5" style="border-radius: 22px;">
                        <img src="/███████_logo.png" height="50" alt="███████ logo" class="mx-auto mb-3">
                        <h2 class="h4 fw-bold">Try ███████ Now</h2>
                        <p class="text-muted small">One unified chat for all your favorite models.</p>
                        <hr class="my-4 mx-auto" style="width: 30%;">
                        <div class="text-start mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-success-subtle text-success rounded-pill me-2">✓</span>
                                <small>No Credit Card Required</small>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-success-subtle text-success rounded-pill me-2">✓</span>
                                <small>GPT-4 & Open Source Models</small>
                            </div>
                        </div>
                        <a href="/auth" class="btn btn-primary w-100 py-3 fw-bold" style="border-radius: 12px;">Create Account</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="py-5 bg-white">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Why use ███████?</h2>
                <p class="text-muted">Built for speed, flexibility, and simplicity.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4 text-center">
                    <div class="p-4">
                        <div class="feature-icon">🚀</div>
                        <h4>Zero Friction</h4>
                        <p class="text-muted">Start chatting in seconds. We've removed the hurdles between you and the world's best AI.</p>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="p-4">
                        <div class="feature-icon">🛡️</div>
                        <h4>Privacy First</h4>
                        <p class="text-muted">Your data is yours. We focus on providing a secure, reliable environment for your ideas.</p>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="p-4">
                        <div class="feature-icon">🌐</div>
                        <h4>Unified Access</h4>
                        <p class="text-muted">Switch between open-source and premium models without ever logging out.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white py-5">
        <div class="container text-center">
            <img src="https://███████.com/images/Untitled%20design%20(2).png?v=1769220226" height="40" alt="███████" class="mb-3 filter-white">
            <p class="mb-4">███████.com Web Services</p>
            <div class="mb-4">
                <a href="/tos" class="text-white-50 mx-2 text-decoration-none">Terms</a>
                <a href="/privacy" class="text-white-50 mx-2 text-decoration-none">Privacy</a>
                <a href="mailto:contact@███████.com" class="text-white-50 mx-2 text-decoration-none">Contact</a>
            </div>
            <p class="small text-white-50">&copy; 2026 ███████.com. All rights reserved.</p>
        </div>
    </footer>