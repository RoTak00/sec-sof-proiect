<?= $head ?>

<div class="auth-layout">
    <div class="auth-card">
        <h2 class="auth-card__title">AuthX - Reset Password</h2>

        <?php if (!empty($notification)): ?>
            <div class="auth-card__notification"><?= $notification ?></div>
        <?php endif; ?>

        <div class="auth-card__text">
            Hello, <?= $email ?>. Set your new password below.
        </div>

        <form method="POST" action="<?= $action ?>" class="auth-form">
            <input class="auth-form__input" type="password" name="password" placeholder="Password" required>

            <button class="auth-form__button" type="submit">Reset Password</button>
        </form>

        <div class="auth-card__links">
            <a class="auth-card__link" href="<?= $back ?>">Back to login</a>
        </div>
    </div>
</div>

<script>
    document.querySelector('.auth-form').addEventListener('submit', function (e) {
        const password = document.querySelector('input[name="password"]').value;

        function showError(msg) {
            let notif = document.querySelector('.auth-card__notification');
            if (!notif) {
                notif = document.createElement('div');
                notif.className = 'auth-card__notification';
                document.querySelector('.auth-card').prepend(notif);
            }
            notif.innerText = msg;
        }

        if (password.length < 10) {
            e.preventDefault();
            showError('Password must be at least 10 characters long');
        }
    });
</script>

<?= $footer ?>