<?= $head ?>

<div class="app-layout">
    <?= $navbar ?>

    <main class="app-content">
        <?= $notification ?>

        <div class="app-content__card">
            <h1 class="app-content__title">Account</h1>

            <form method="post" action="<?= $action ?>">
                <div style="margin-bottom: 15px;">
                    <label for="email"><strong>Email</strong></label><br>
                    <input type="email" name="email" id="email" value="<?= $email ?>"
                        style="margin-top: 6px; padding: 10px; width: 100%; max-width: 500px; box-sizing: border-box;">
                </div>

                <hr style="margin: 20px 0;">

                <div style="margin-bottom: 15px;">
                    <label for="old_password"><strong>Old password</strong></label><br>
                    <input type="password" name="old_password" id="old_password"
                        style="margin-top: 6px; padding: 10px; width: 100%; max-width: 500px; box-sizing: border-box;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="new_password"><strong>New password</strong></label><br>
                    <input type="password" name="new_password" id="new_password"
                        style="margin-top: 6px; padding: 10px; width: 100%; max-width: 500px; box-sizing: border-box;">
                </div>

                <button type="submit" class="auth-card__button">Save changes</button>
            </form>
        </div>
    </main>
</div>

<?= $footer ?>