<?= $head ?>

<div class="auth-layout">
    <div class="auth-card">
        <h2 class="auth-card__title">AuthX - Forgot Password</h2>

        <?php if (!empty($notification)): ?>
            <div class="auth-card__notification"><?= $notification ?></div>
        <?php endif; ?>

        <div class="auth-card__text">
            Enter your email address and we’ll send you a password reset link.
        </div>

        <form method="POST" action="<?= $action ?>" class="auth-form">
            <input class="auth-form__input" type="email" name="email" placeholder="Email" required>

            <button class="auth-form__button" type="submit">Send Reset Link</button>
        </form>

        <div class="auth-card__links">
            <a class="auth-card__link" href="<?= $back ?>">Back to login</a>
        </div>
    </div>
</div>

<?= $footer ?>