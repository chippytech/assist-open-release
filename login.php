<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ==============================
    // VERIFY CLOUDFLARE TURNSTILE
    // ==============================

    $turnstileSecret = "███████████████████████████████████████████";
    $turnstileResponse = $_POST['cf-turnstile-response'] ?? '';

    if (!$turnstileResponse) {
        header("Location: /auth?error=" . urlencode("Captcha verification failed."));
        exit;
    }

    $ch = curl_init("https://challenges.cloudflare.com/turnstile/v0/siteverify");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([
            'secret' => $turnstileSecret,
            'response' => $turnstileResponse,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ])
    ]);

    $verifyResponse = curl_exec($ch);
    curl_close($ch);

    $captchaResult = json_decode($verifyResponse, true);

    if (!$captchaResult || empty($captchaResult['success'])) {
        header("Location: /auth?error=" . urlencode("Captcha verification failed."));
        exit;
    }

    // ==============================
    // LOGIN LOGIC
    // ==============================

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id, email, password_hash, is_verified FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {

        // If NOT verified → resend verification email
        if ((int)$user['is_verified'] !== 1) {

            $token = ██████████████████
            $expires = ██████████████████

            $update = $pdo->prepare("UPDATE users SET verification_token = ?, token_expires = ? WHERE id = ?");
            $update->execute([$token, $expires, $user['id']]);

        }

        // Verified → allow login
        s██████████████████d(true);

        header("Location: /");
        exit;

    } else {
        header("Location: /██████████████████?error=" . urlencode("Invalid username or password."));
        exit;
    }
}
?>