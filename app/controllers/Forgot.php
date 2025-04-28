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
            if ($_POST['selection'] == 'doctor') {
                $nicID = $_POST['nicID'] . "d";
            } elseif ($_POST['selection'] == 'patient') {
                $nicID = $_POST['nicID'] . "p";
            } elseif ($_POST['selection'] == 'pharmacist') {
                $nicID = $_POST['nicID'] . "h";
            } elseif ($_POST['selection'] == 'lab_technician') {
                $nicID = $_POST['nicID'] . "l";
            } elseif ($_POST['selection'] == 'administrative_staff') {
                $nicID = $_POST['nicID'] . "a";
            } elseif ($_POST['selection'] == 'receptionist') {
                $nicID = $_POST['nicID'] . "r";
            }

            $userEmail = $_POST['email'];
            $table = $_POST['selection'];

            $validTables = ['doctor', 'patient', 'pharmacist', 'lab_technician', 'administrative_staff', 'receptionist'];
            if (!in_array($table, $validTables)) {
                $errorMessage = 'Invalid selection.';
                $this->view('forgot', 'forgot', ['errorMessage' => $errorMessage]);
                return;
            }

            $model = new checkValues();
            $result = $model->check($nicID, $userEmail, $table);

            if ($result['found'] === 'true') {
                $verificationCode = sprintf("%06d", mt_rand(100000, 999999));

                $_SESSION['reset_email'] = $userEmail;
                $_SESSION['reset_nic'] = $nicID;
                $_SESSION['reset_table'] = $table;
                $_SESSION['verification_code'] = $verificationCode;
                $_SESSION['code_expiry'] = time() + 360;
                $_SESSION['code_verified'] = false;

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
                if ($_SESSION['code_verified']) {
                    $newPassword = $_POST['new_password'];
                    $confirmPassword = $_POST['confirm_password'];

                    if ($newPassword === $confirmPassword) {
                        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                        $model = new checkValues();
                        $updateResult = $model->updatePassword($_SESSION['reset_nic'], $_SESSION['reset_table'], $hashedPassword);

                        if ($updateResult) {
                            $subject = 'Password Changed Successfully';
                            $message = "
                                <h3>Password Changed</h3>
                                <p>Your password has been successfully updated.</p>
                                <p><a href='" . ROOT . "/login'>Click here to login</a> with your new password.</p>
                            ";

                            $emailService = new Email();
                            $emailService->send($_SESSION['reset_email'], $_SESSION['reset_email'], $message, $_SESSION['reset_email']);

                            unset($_SESSION['reset_email'], $_SESSION['reset_nic'], $_SESSION['reset_table'], $_SESSION['verification_code'], $_SESSION['code_expiry'], $_SESSION['code_verified']);

                            echo "<script>console.log('Password updated successfully!'); window.location.href='" . ROOT . "/login';</script>";
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

            $validTables = ['doctor', 'patient', 'pharmacist', 'lab_technician', 'administrative_staff', 'receptionist'];
            if (!in_array($table, $validTables)) {
                $entryErrorMessage = 'Invalid selection.';
            } else {
                $model = new checkValues();
                $result = $model->check($nicID, $userEmail, $table);

                if ($result['found'] === 'true') {
                    $verificationCode = sprintf("%06d", mt_rand(100000, 999999));

                    $_SESSION['reset_email'] = $userEmail;
                    $_SESSION['reset_nic'] = $nicID;
                    $_SESSION['reset_table'] = $table;
                    $_SESSION['verification_code'] = $verificationCode;
                    $_SESSION['code_expiry'] = time() + 360;
                    $_SESSION['code_verified'] = false;

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
            $verificationCode = sprintf("%06d", mt_rand(100000, 999999));

            $_SESSION['verification_code'] = $verificationCode;
            $_SESSION['code_expiry'] = time() + 360;
            $_SESSION['code_verified'] = false;

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
