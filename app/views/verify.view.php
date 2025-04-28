<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Verify Code and Reset Password</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/forgot.css">
</head>
<body>
    <div class="main">
        <div class="form-wrapper">
            <?php if (!isset($data['codeVerified']) || !$data['codeVerified']): ?>
                <button type="button" class="arrow-button left-arrow" id="left-arrow" onclick="toggleForms('entry-form')"><</button>
            <?php endif; ?>
            <div class="form-container">
                <!-- Verification Code Form -->
                <?php if (!isset($data['codeVerified']) || !$data['codeVerified']): ?>
                    <form id="verify-form" method="POST" action="<?= ROOT ?>/forgot/verify" style="display: block;">
                        <h1>Verify Your Code</h1>
                        <div class="field">
                            <input type="text" name="code" placeholder="Verification Code" required>
                        </div>
                        <?php if (!empty($data['errorMessage']) && !$data['codeVerified']): ?>
                            <div class="forgot-message"><?= htmlspecialchars($data['errorMessage']) ?></div>
                        <?php endif; ?>
                        <?php if (isset($data['codeExpired']) && $data['codeExpired']): ?>
                            <div class="forgot-message"></div>
                        <?php endif; ?>
                        <div class="resend-link">
                            <a href="<?= ROOT ?>/forgot/resend">Resend Code</a>
                        </div>
                        <input type="submit" name="verify_code" value="Verify Code">
                    </form>

                    <!-- NIC ID and Email Entry Form -->
                    <form id="entry-form" method="POST" action="<?= ROOT ?>/forgot/recheck" style="display: none;">
                        <h1>Enter Your NIC & Email</h1>
                        <div class="field">
                            <input type="text" name="nicID" placeholder="NIC ID" required>
                        </div>
                        <div class="field">
                            <input type="email" name="email" placeholder="Email" required>
                        </div>
                        <?php if (!empty($data['entryErrorMessage'])): ?>
                            <div class="forgot-message"><?= htmlspecialchars($data['entryErrorMessage']) ?></div>
                        <?php endif; ?>
                        <div class="signup_link">
                            Back to login? <a href="<?= ROOT ?>/login">Login</a>
                        </div>
                        <input type="submit" name="recheck_entries" value="Submit Entries">
                    </form>
                <?php endif; ?>

                <!-- Password Reset Form (shown after code verification) -->
                <?php if (isset($data['codeVerified']) && $data['codeVerified']): ?>
                    <form method="POST" action="<?= ROOT ?>/forgot/verify">
                        <h1>Reset Your Password</h1>
                        <div class="field">
                            <input type="password" name="new_password" placeholder="New Password" required>
                        </div>
                        <div class="field">
                            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                        </div>
                        <?php if (!empty($data['errorMessage'])): ?>
                            <div class="forgot-message"><?= htmlspecialchars($data['errorMessage']) ?></div>
                        <?php endif; ?>
                        <input type="submit" name="update_password" value="Update Password">
                    </form>
                <?php endif; ?>
            </div>
            <?php if (!isset($data['codeVerified']) || !$data['codeVerified']): ?>
                <button type="button" class="arrow-button right-arrow disabled" id="right-arrow" onclick="toggleForms('verify-form')">></button>
            <?php endif; ?>
        </div>

    </div>

    <script>
        function toggleForms(formId) {
            const verifyForm = document.getElementById('verify-form');
            const entryForm = document.getElementById('entry-form');
            const leftArrow = document.getElementById('left-arrow');
            const rightArrow = document.getElementById('right-arrow');

            verifyForm.style.display = formId === 'verify-form' ? 'block' : 'none';
            entryForm.style.display = formId === 'entry-form' ? 'block' : 'none';

            if (formId === 'verify-form') {
                leftArrow.classList.remove('disabled');
                rightArrow.classList.add('disabled');
            } else {
                leftArrow.classList.add('disabled');
                rightArrow.classList.remove('disabled');
            }
        }
    </script>
</body>
</html>