<?php
class Forgot extends Controller
{
    public function index()
    {
        $this->view('forgot');
    }

    public function check()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
            $userEmail = $_POST['email'];
            $model = new checkValues();
            $result = $model->check($userEmail);

            if ($result['found'] === 'true') {
                // Generate a 6-digit verification code
                $verificationCode = sprintf("%06d", mt_rand(100000, 999999));
                
                // Store the code and email in session for verification
                $_SESSION['reset_email'] = $userEmail;
                $_SESSION['verification_code'] = $verificationCode;
                $_SESSION['code_expiry'] = time() + 600; // Code valid for 10 minutes
                $_SESSION['code_verified'] = false; // Initialize code verification flag

                // Send verification code via email
                $subject = 'Password Reset Verification Code';
                $message = "
                    <h3>Password Reset Request</h3>
                    <p>Your verification code is: <strong>$verificationCode</strong></p>
                    <p>This code is valid for 10 minutes.</p>
                    <p>If you did not request a password reset, please ignore this email.</p>
                ";

                $emailService = new Email();
                $response = $emailService->send($userEmail, $userEmail, $message, $userEmail);

                if (strpos($response, 'successfully') !== false) {
                    // Redirect to verification page
                    header('Location: ' . ROOT . '/forgot/verify');
                    exit();
                } else {
                    $errorMessage = 'Failed to send verification email. Please try again.';
                }
            } else {
                $errorMessage = 'Email not found!';
            }

            $this->view('forgot', 'forgot', ['errorMessage' => $errorMessage]);
        }
    }

    public function verify()
    {
        $errorMessage = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check if session data exists and code is still valid
            if (
                !isset($_SESSION['reset_email']) ||
                !isset($_SESSION['verification_code']) ||
                !isset($_SESSION['code_expiry']) ||
                time() >= $_SESSION['code_expiry']
            ) {
                $errorMessage = 'Verification code expired or invalid session. Please try again.';
                unset($_SESSION['reset_email'], $_SESSION['verification_code'], $_SESSION['code_expiry'], $_SESSION['code_verified']);
                header('Location: ' . ROOT . '/forgot');
                exit();
            }

            if (isset($_POST['verify_code'])) {
                // Step 1: Verify the code
                $enteredCode = $_POST['code'];
                if ($enteredCode === $_SESSION['verification_code']) {
                    $_SESSION['code_verified'] = true; // Mark code as verified
                } else {
                    $errorMessage = 'Invalid verification code.';
                }
            } elseif (isset($_POST['update_password'])) {
                // Step 2: Update password if code was verified
                if ($_SESSION['code_verified']) {
                    $newPassword = $_POST['new_password'];
                    $confirmPassword = $_POST['confirm_password'];

                    if ($newPassword === $confirmPassword) {
                        // Hash the new password
                        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                        // Update password in the database
                        $model = new checkValues();
                        $updateResult = $model->updatePassword($_SESSION['reset_email'], $hashedPassword);

                        if ($updateResult) {
                            // Send confirmation email
                            $subject = 'Password Changed Successfully';
                            $message = "
                                <h3>Password Changed</h3>
                                <p>Your password has been successfully updated.</p>
                                <p><a href='" . ROOT . "/login'>Click here to login</a> with your new password.</p>
                            ";

                            $emailService = new Email();
                            $emailService->send($_SESSION['reset_email'], $_SESSION['reset_email'], $message, $_SESSION['reset_email']);

                            // Clear session
                            unset($_SESSION['reset_email'], $_SESSION['verification_code'], $_SESSION['code_expiry'], $_SESSION['code_verified']);
                            
                            // Redirect to login with success message
                            echo "<script>alert('Password updated successfully!'); window.location.href='" . ROOT . "/login';</script>";
                            exit();
                        } else {
                            $errorMessage = 'Failed to update password. Please try again.';
                        }
                    } else {
                        $errorMessage = 'Passwords do not match.';
                    }
                } else {
                    $errorMessage = 'Please verify the code first.';
                }
            }
        }

        $this->view('verify', 'verify', ['errorMessage' => $errorMessage, 'codeVerified' => isset($_SESSION['code_verified']) && $_SESSION['code_verified']]);
    }
}