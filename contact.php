<?php

require 'vendor/autoload.php';

function loadEnv($path)
{
    if (!file_exists($path)) return;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        list($key, $value) = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $phone = strip_tags(trim($_POST["phone"]));
    $service = strip_tags(trim($_POST["service"]));
    $message = trim($_POST["message"]);

    if (empty($name) || empty($email) || empty($service) || empty($message)) {
        http_response_code(400);
        echo "error";
        exit;
    }

    $email_content = "Name: $name\nEmail: $email\nPhone: $phone\nService: $service\nMessage:\n$message\n";

    loadEnv(__DIR__ . '/.env');

    $sendgridApiKey = $_ENV['SENDGRID_API_KEY'];

    $sendgrid = new \SendGrid($sendgridApiKey);

    $emailToSend = new \SendGrid\Mail\Mail();
    $emailToSend->setFrom("noreply@brogrammersagency.com", "Brogrammers Agency");
    $emailToSend->setSubject("New Message From creationsbyelo.com");
    $emailToSend->addTo("nagolpj@gmail.com", "Jay Logan");
    $emailToSend->addTo("creationsbyelo@gmail.com", "Creations By ELO");
    $emailToSend->setReplyTo($email, $name);
    $emailToSend->addContent("text/plain", $email_content);

    try {
        $response = $sendgrid->send($emailToSend);
        if ($response->statusCode() >= 200 && $response->statusCode() < 300) {
            echo "success";
        } else {
            http_response_code(500);
            echo "error";
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo "error";
    }
}
?>