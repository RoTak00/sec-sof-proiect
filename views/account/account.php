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

<script>
    document.querySelector('form[action="<?= $action ?>"]').addEventListener('submit', function (e) {
        const oldPassword = document.querySelector('#old_password').value;
        const newPassword = document.querySelector('#new_password').value;

        function showError(msg) {
            let notif = document.querySelector('.auth-card__notification');
            if (!notif) {
                notif = document.createElement('div');
                notif.className = 'auth-card__notification';
                document.querySelector('.app-content__card').prepend(notif);
            }
            notif.innerText = msg;
        }

        if (oldPassword !== '' || newPassword !== '') {
            if (oldPassword === '') {
                e.preventDefault();
                showError('Old password is required');
                return;
            }

            if (newPassword === '') {
                e.preventDefault();
                showError('New password is required');
                return;
            }

            if (newPassword.length < 10) {
                e.preventDefault();
                showError('New password must be at least 10 characters long');
                return;
            }
        }
    });
</script>

<?= $footer ?>