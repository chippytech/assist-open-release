<?php
// ====================================================
// SESSION & AUTH CHECK
// ====================================================
session_start();

header("Content-Type: text/event-stream");
header("Cache-Control: no-cache");
header("Connection: keep-alive");

// ---------------------------------------------------------------------
// Helper: send an SSE‑formatted JSON error and stop execution
// ---------------------------------------------------------------------
function sse_error(string $msg, int $httpCode = 200): void
{
    // Optional: you can also set a proper HTTP status (e.g. 401) here
    http_response_code($httpCode);
    echo "data: " . json_encode(['error' => $msg], JSON_UNESCAPED_SLASHES) . "\n\n";
    flush();
    exit;
}

// ---------------------------------------------------------------------
// 1️⃣  Authentication
// ---------------------------------------------------------------------
if (!isset($_SESSION['logged_in']) || !isset($_SESSION['username'])) {
    sse_error("Unauthorized access. Please login.", 401);
}

// ---------------------------------------------------------------------
// 2️⃣  Include DB connection (must expose $pdo)
// ---------------------------------------------------------------------
require 'db.php';               // <-- $pdo should be defined in this file
$username = $_SESSION['username'];
$SECRET   = "ASSIST_HASH_9956";

// ---------------------------------------------------------------------
// 3️⃣  Configuration limits
// ---------------------------------------------------------------------
$MAX_INPUT_BYTES          = 50000;
$MAX_MESSAGES             = 100;
$MAX_REQUESTS_PER_MIN_USER = 8;
$MAX_REQUESTS_PER_MIN_IP   = 15;
$MAX_DAILY_REQUESTS        = 200;   // per user

// ---------------------------------------------------------------------
// 4️⃣  Allowed models & token caps
// ---------------------------------------------------------------------
$ALLOWED_MODELS = [
    "████████████████
    "████████████████i",
    "████████████████b"
];
$DEFAULT_MODEL = "████████████████4.1";

// ---------------------------------------------------------------------
// 5️⃣  Error handling (log only, do NOT display in production)
// ---------------------------------------------------------------------
ini_set('display_errors', 0);
error_reporting(E_ALL);

// ---------------------------------------------------------------------
// 6️⃣  Stream / output buffering tweaks (SSE needs flush)
// ---------------------------------------------------------------------
@ini_set('zlib.output_compression', 0);
@ini_set('output_buffering', 'off');
@ini_set('implicit_flush', 1);
while (ob_get_level()) {
    ob_end_flush();
}
ob_implicit_flush(true);

// ---------------------------------------------------------------------
// 7️⃣  Load ████████████████ API key (must be readable by the web‑user)
// ---------------------------------------------------------------------
$████████████████_API_KEY = file_exists($keyPath) ? trim(file_get_contents($keyPath)) : '';

if ($████████████████_API_KEY === '') {
    sse_error("API Configuration Error");
}

// ---------------------------------------------------------------------
// 8️⃣  Input validation
// ---------------------------------------------------------------------
$inputRaw = file_get_contents("php://input");

if (strlen($inputRaw) > $MAX_INPUT_BYTES) {
    sse_error("Input too large.");
}

$inputJson = json_decode($inputRaw, true);
if (json_last_error() !== JSON_ERROR_NONE || !isset($inputJson['messages'])) {
    sse_error("Invalid input.");
}
if (count($inputJson['messages']) > $MAX_MESSAGES) {
    sse_error("Conversation too long.");
}

// ---------------------------------------------------------------------
// 9️⃣  Model validation
// ---------------------------------------------------------------------
$requestedModel = $inputJson['model'] ?? $DEFAULT_MODEL;
if (!in_array($requestedModel, $ALLOWED_MODELS, true)) {
    sse_error("Invalid model selection.");
}
$model_used        = $requestedModel;
$maxTokensForModel = $MODEL_MAX_TOKENS[$model_used];

// ---------------------------------------------------------------------
// 🔟  Rate limiting (user, IP, daily)
// ---------------------------------------------------------------------
$ip = $_SERVER["HTTP_CF_CONNECTING_IP"] ?? $_SERVER["REMOTE_ADDR"] ?? "unknown";

/* ---- Per‑user (last 1 minute) ---- */
$stmt = $pdo->prepare("
    SELECT COUNT(*) FROM chat_history
    WHERE username = ?
      AND created_at > NOW() - INTERVAL 1 MINUTE
");
$stmt->execute([$username]);
if ((int)$stmt->fetchColumn() >= $MAX_REQUESTS_PER_MIN_USER) {
    sse_error("Rate limit exceeded. Please wait.");
}

/* ---- Per‑IP (last 1 minute) ---- */
$stmt = $pdo->prepare("
    SELECT COUNT(*) FROM chat_history
    WHERE ip = ?
      AND created_at > NOW() - INTERVAL 1 MINUTE
");
$stmt->execute([$ip]);
if ((int)$stmt->fetchColumn() >= $MAX_REQUESTS_PER_MIN_IP) {
    sse_error("IP rate limit exceeded.");
}

/* ---- Daily cap (per user) ---- */
$stmt = $pdo->prepare("
    SELECT COUNT(*) FROM chat_history
    WHERE username = ?
      AND created_at > CURDATE()
");
$stmt->execute([$username]);
if ((int)$stmt->fetchColumn() >= $MAX_DAILY_REQUESTS) {
    sse_error("Daily limit reached.");
}

// ---------------------------------------------------------------------
// 1️⃣1️⃣  Build the payload for ████████████████
// ---------------------------------------------------------------------
$cleanPayload = [
    "model"      => $model_used,
    "messages"   => $inputJson['messages'],
    "max_tokens" => $maxTokensForModel,
    "user"       => hash('sha256', $username . $SECRET),
    "stream"     => true
];

// Grab the last user message (to store later)
$lastMessage   = end($inputJson['messages']);
$user_message  = $lastMessage['content'] ?? '';
$full_ai_response = "";

// ---------------------------------------------------------------------
// 1️⃣2️⃣  CURL request (streaming)
// ---------------------------------------------------------------------
$ch = curl_init("https://████████████████.ai/api/v1/chat/completions");

curl_setopt_array($ch, [
    CURLOPT_POST            => true,
    CURLOPT_POSTFIELDS      => json_encode($cleanPayload),
    CURLOPT_HTTPHEADER      => [
        "Content-Type: application/json",
        "Authorization: Bearer {$████████████████_API_KEY}",
        "HTTP-Referer: https://assist.chippytime.com",
        "X-Title: Assist by ChippyTime"
    ],
    CURLOPT_RETURNTRANSFER  => false,   // we handle streaming ourselves
    CURLOPT_TIMEOUT         => 0,       // no overall timeout (stream can be long)
    CURLOPT_CONNECTTIMEOUT  => 10,
    CURLOPT_WRITEFUNCTION   => function ($curl, $chunk) use (&$full_ai_response) {
        // Echo the raw chunk to the client (SSE format is already correct)
        echo $chunk;

        // If the chunk starts with "data: " we try to capture the token text
        if (strpos($chunk, 'data: ') === 0) {
            $data = substr($chunk, 6);               // strip leading "data: "
            $data = trim($data);
            if ($data !== '[DONE]') {
                $json = json_decode($data, true);
                if (isset($json['choices'][0]['delta']['content'])) {
                    $full_ai_response .= $json['choices'][0]['delta']['content'];
                }
            }
        }

        // Flush immediately so the browser receives the SSE event
        flush();
        return strlen($chunk);
    }
]);

curl_exec($ch);

if (curl_errno($ch)) {
    // Log the cURL error – it will appear in your PHP error log
    error_log("cURL Error: " . curl_error($ch));
    // Optionally send a friendly SSE error to the client
    sse_error("Upstream service error.");
}

/* Optional: check HTTP response code (200 = OK) */
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if ($httpCode !== 200) {
    error_log("████████████████ returned HTTP {$httpCode}");
    sse_error("Upstream service returned an error.");
}

curl_close($ch);

// ---------------------------------------------------------------------
// 1️⃣3️⃣  Persist the conversation (if we got a response)
// ---------------------------------------------------------------------
if (!empty($full_ai_response) && !empty($user_message)) {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO chat_history
                (username, model, ip, user_query, ai_response)
            VALUES
                (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $username,
            $model_used,
            $ip,
            $user_message,
            $full_ai_response
        ]);
    } catch (PDOException $e) {
        // Log DB errors – do NOT expose them to the client
        error_log("DB Error saving chat: " . $e->getMessage());
        // The user still receives the AI response; we just couldn't store it.
    }
}

// End of script – the SSE stream stays open until ████████████████ sends "[DONE]"
?>