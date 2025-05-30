<?php
require '../vendor/autoload.php';

class Mailer {
    private $mailer;
    private $config = [
        'host' => 'mail.ranavattra.com',
        'username' => 'ra.vattra.official@ranavattra.com',
        'password' => 'v$Is$0f7s4aC',
        'port' => 587,
        // 'encryption' => 'tls',
        'encryption' => 'PHPMailer::ENCRYPTION_STARTTLS',
        'from_email' => 'ra.vattra.official@ranavattra.com',
        'from_name' => 'Ra Vattra Official'
    ];

    public function __construct($config = []) {
        $this->config = array_merge($this->config, $config);
        $this->mailer = new PHPMailer\PHPMailer\PHPMailer(true);
        $this->configureMailer();
    }

    private function configureMailer() {
        $this->mailer->isSMTP();
        $this->mailer->Host = $this->config['host'];
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $this->config['username'];
        $this->mailer->Password = $this->config['password'];
        $this->mailer->SMTPSecure = $this->config['encryption'];
        $this->mailer->Port = $this->config['port'];
        $this->mailer->setFrom($this->config['from_email'], $this->config['from_name']);
    }

    /**
     * Capture a page's output and send as email
     * 
     * @param string $pagePath Path to the page to capture
     * @param string $email Recipient email address
     * @param string $subject Email subject
     * @param array $variables Variables to extract for the included page
     * @return bool True if mail sent successfully, false otherwise
     */
    public function mailBuffer($pagePath, $email, $subject, $variables = []) {
        try {
            // Start output buffering
            ob_start();
            
            // Extract variables for use in the included page
            extract($variables);
            
            // Include the page - its output will be captured
            include $pagePath;
            
            // Get the buffered content
            $emailBody = ob_get_clean();
            
            // Set up email
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($email);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $emailBody;
            $this->mailer->AltBody = strip_tags($emailBody);
            
            // Send email
            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Mailer Error: " . $e->getMessage());
            ob_end_clean(); // Clean buffer in case of error
            return false;
        }
    }
}