<?php
class Forgot extends Controller
{
    public function index()
    {
        $this->view('forgot');
    }

    public function check()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nicID'], $_POST['email'], $_POST['selection'])) {
            $nicID = $_POST['nicID'];
            $userEmail = $_POST['email'];
            $table = $_POST['selection'];

            // Validate the selected table to prevent invalid values
            $validTables = ['doctor', 'patient', 'pharmacist', 'lab_technician', 'administrative_staff', 'receptionist'];
            if (!in_array($table, $validTables)) {
                $errorMessage = 'Invalid selection.';
                $this->view('forgot', 'forgot', ['errorMessage' => $errorMessage]);
                return;
            }

            $model = new checkValues();
            $result = $model->check($nicID, $userEmail, $table);

            if ($result['found'] === 'true') {
                // Generate a 6-digit verification code
                $verificationCode = sprintf("%06d", mt_rand(100000, 999999));

                // Store the code, email, and table in session for verification
                $_SESSION['reset_email'] = $userEmail;
                $_SESSION['reset_nic'] = $nicID;
                $_SESSION['reset_table'] = $table;
                $_SESSION['verification_code'] = $verificationCode;
                $_SESSION['code_expiry'] = time() + 360; // Code valid for 6 minutes
                $_SESSION['code_verified'] = false;

                // Send verification code via email
                $subject = 'Password Reset Verification Code';
                $message = "
                    <h3>Password Reset Request</h3>
                    <p>Your verification code is: <strong>$verificationCode</strong></p>
                    <p>This code is valid for 6 minutes.</p>
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
                $errorMessage = 'NIC ID or email not found in our records.';
            }

            $this->view('forgot', 'forgot', ['errorMessage' => $errorMessage]);
        }
    }

    public function verify()
    {
        $errorMessage = '';
        $entryErrorMessage = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check if session data exists
            if (
                !isset($_SESSION['reset_email']) ||
                !isset($_SESSION['reset_nic']) ||
                !isset($_SESSION['reset_table']) ||
                !isset($_SESSION['verification_code']) ||
                !isset($_SESSION['code_expiry'])
            ) {
                $errorMessage = 'Invalid session. Please try again.';
                unset($_SESSION['reset_email'], $_SESSION['reset_nic'], $_SESSION['reset_table'], $_SESSION['verification_code'], $_SESSION['code_expiry'], $_SESSION['code_verified']);
                header('Location: ' . ROOT . '/forgot');
                exit();
            }

            if (isset($_POST['verify_code'])) {
                // Step 1: Verify the code
                if (time() >= $_SESSION['code_expiry']) {
                    $errorMessage = 'Verification code has expired. Please request a new code.';
                } else {
                    $enteredCode = $_POST['code'];
                    if ($enteredCode === $_SESSION['verification_code']) {
                        $_SESSION['code_verified'] = true;
                    } else {
                        $errorMessage = 'Invalid verification code.';
                    }
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
                        $updateResult = $model->updatePassword($_SESSION['reset_nic'], $_SESSION['reset_table'], $hashedPassword);

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
                            unset($_SESSION['reset_email'], $_SESSION['reset_nic'], $_SESSION['reset_table'], $_SESSION['verification_code'], $_SESSION['code_expiry'], $_SESSION['code_verified']);

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

        $this->view('verify', 'verify', [
            'errorMessage' => $errorMessage,
            'entryErrorMessage' => $entryErrorMessage,
            'codeVerified' => isset($_SESSION['code_verified']) && $_SESSION['code_verified'],
            'codeExpired' => isset($_SESSION['code_expiry']) && time() >= $_SESSION['code_expiry']
        ]);
    }

    public function recheck()
    {
        $errorMessage = '';
        $entryErrorMessage = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nicID'], $_POST['email'], $_POST['selection'])) {
            $nicID = $_POST['nicID'];
            $userEmail = $_POST['email'];
            $table = $_POST['selection'];

            // Validate the selected table
            $validTables = ['doctor', 'patient', 'pharmacist', 'lab_technician', 'administrative_staff', 'receptionist'];
            if (!in_array($table, $validTables)) {
                $entryErrorMessage = 'Invalid selection.';
            } else {
                $model = new checkValues();
                $result = $model->check($nicID, $userEmail, $table);

                if ($result['found'] === 'true') {
                    // Generate a new 6-digit verification code
                    $verificationCode = sprintf("%06d", mt_rand(100000, 999999));

                    // Update session with new data
                    $_SESSION['reset_email'] = $userEmail;
                    $_SESSION['reset_nic'] = $nicID;
                    $_SESSION['reset_table'] = $table;
                    $_SESSION['verification_code'] = $verificationCode;
                    $_SESSION['code_expiry'] = time() + 360; // 6 minutes
                    $_SESSION['code_verified'] = false;

                    // Send verification code via email
                    $subject = 'Password Reset Verification Code';
                    $message = "
                        <h3>Password Reset Request</h3>
                        <p>Your new verification code is: <strong>$verificationCode</strong></p>
                        <p>This code is valid for 6 minutes.</p>
                        <p>If you did not request a password reset, please ignore this email.</p>
                    ";

                    $emailService = new Email();
                    $response = $emailService->send($userEmail, $userEmail, $message, $userEmail);

                    if (strpos($response, 'successfully') !== false) {
                        $errorMessage = 'New verification code sent successfully.';
                    } else {
                        $entryErrorMessage = 'Failed to send verification email. Please try again.';
                    }
                } else {
                    $entryErrorMessage = 'NIC ID or email not found in our records.';
                }
            }

            $this->view('verify', 'verify', [
                'errorMessage' => $errorMessage,
                'entryErrorMessage' => $entryErrorMessage,
                'codeVerified' => isset($_SESSION['code_verified']) && $_SESSION['code_verified'],
                'codeExpired' => isset($_SESSION['code_expiry']) && time() >= $_SESSION['code_expiry']
            ]);
        }
    }

    public function resend()
    {
        if (
            isset($_SESSION['reset_email']) &&
            isset($_SESSION['reset_nic']) &&
            isset($_SESSION['reset_table'])
        ) {
            // Generate a new 6-digit verification code
            $verificationCode = sprintf("%06d", mt_rand(100000, 999999));

            // Update session with new code and expiry
            $_SESSION['verification_code'] = $verificationCode;
            $_SESSION['code_expiry'] = time() + 360; // 6 minutes
            $_SESSION['code_verified'] = false;

            // Send new verification code via email
            $subject = 'Password Reset Verification Code';
            $message = "
                <h3>Password Reset Request</h3>
                <p>Your new verification code is: <strong>$verificationCode</strong></p>
                <p>This code is valid for 6 minutes.</p>
                <p>If you did not request a password reset, please ignore this email.</p>
            ";

            $emailService = new Email();
            $response = $emailService->send($_SESSION['reset_email'], $_SESSION['reset_email'], $message, $_SESSION['reset_email']);

            if (strpos($response, 'successfully') !== false) {
                $errorMessage = 'New verification code sent successfully.';
            } else {
                $errorMessage = 'Failed to send new verification code. Please try again.';
            }

            $this->view('verify', 'verify', [
                'errorMessage' => $errorMessage,
                'entryErrorMessage' => '',
                'codeVerified' => false,
                'codeExpired' => false
            ]);
        } else {
            header('Location: ' . ROOT . '/forgot');
            exit();
        }
    }
}
