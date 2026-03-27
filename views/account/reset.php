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

<?= $footer ?>