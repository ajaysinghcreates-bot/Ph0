<?php
require_once __DIR__ . '/../templates/header.php';

$passcode_submitted = isset($_POST['passcode']);
$correct_passcode = $passcode_submitted && $_POST['passcode'] === '623264';

?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Administrator Registration</h4>
            </div>
            <div class="card-body">
                <?php if (!$correct_passcode): ?>
                    <form action="register.php" method="POST">
                        <div class="mb-3">
                            <label for="passcode" class="form-label">Enter Registration Passcode</label>
                            <input type="password" class="form-control" id="passcode" name="passcode" required>
                        </div>
                        <?php if ($passcode_submitted): ?>
                            <div class="alert alert-danger">Invalid Passcode.</div>
                        <?php endif; ?>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                <?php else: ?>
                    <form action="../src/register_handler.php" method="POST">
                        <?php csrf_field(); ?>
                        <input type="hidden" name="passcode" value="623264">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Register Admin Account</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>