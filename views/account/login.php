<?= $head ?>

<div class="auth-layout">
    <div class="auth-card">
        <h2 class="auth-card__title">AuthX - Login</h2>

        <?php if (!empty($notification)): ?>
            <div class="auth-card__notification"><?= $notification ?></div>
        <?php endif; ?>

        <form method="POST" action="" class="auth-form">
            <input class="auth-form__input" type="email" name="email" placeholder="Email" required>
            <input class="auth-form__input" type="password" name="password" placeholder="Password" required>

            <button class="auth-form__button" type="submit">Login</button>
        </form>

        <div class="auth-card__links">
            <a class="auth-card__link" href="<?= $register ?>">No account? Register now</a>
            <a class="auth-card__link" href="<?= $forgot ?>">Forgot your password?</a>
        </div>
    </div>
</div>
<?= $footer ?>