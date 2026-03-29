<?= $head ?>

<div class="auth-layout">
    <div class="auth-card">
        <h2 class="auth-card__title">AuthX - Register</h2>

        <?php if (!empty($notification)): ?>
            <div class="auth-card__notification"><?= $notification ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= $action ?>" class="auth-form">
            <input class="auth-form__input" type="email" name="email" placeholder="Email" required>
            <input class="auth-form__input" type="password" name="password" placeholder="Password" required>

            <button class="auth-form__button" type="submit">Create Account</button>
        </form>

        <div class="auth-card__links">
            <a class="auth-card__link" href="<?= $login ?>">Already have an account? Login</a>
        </div>
    </div>
</div>

<script>
    document.querySelector('.auth-form').addEventListener('submit', function (e) {
        const password = document.querySelector('input[name="password"]').value;

        if (password.length < 10) {
            e.preventDefault();

            let notif = document.querySelector('.auth-card__notification');
            if (!notif) {
                notif = document.createElement('div');
                notif.className = 'auth-card__notification';
                document.querySelector('.auth-card').prepend(notif);
            }

            notif.innerText = 'Password must be at least 10 characters long';
        }
    });
</script>

<?= $footer ?>