<?= $head ?>

<div class="app-layout">
    <?= $navbar ?>

    <main class="app-content">
        <?= $notification ?>

        <div class="app-content__card">
            <h1 class="app-content__title">Edit User</h1>

            <form method="post" action="<?= $action ?>">
                <div style="margin-bottom: 15px;">
                    <label for="email"><strong>Email</strong></label><br>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($edited_user['email']) ?>"
                        style="margin-top: 6px; padding: 10px; width: 100%; max-width: 500px; box-sizing: border-box;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="role"><strong>Role</strong></label><br>
                    <select name="role" id="role"
                        style="margin-top: 6px; padding: 10px; width: 100%; max-width: 500px; box-sizing: border-box;">
                        <option value="user" <?= $edited_user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                        <option value="analyst" <?= $edited_user['role'] === 'analyst' ? 'selected' : '' ?>>Analyst
                        </option>
                        <option value="admin" <?= $edited_user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="verified"><strong>Verified</strong></label><br>
                    <select name="verified" id="verified"
                        style="margin-top: 6px; padding: 10px; width: 100%; max-width: 500px; box-sizing: border-box;">
                        <option value="0" <?= $edited_user['is_verified'] == '0' ? 'selected' : '' ?>>No</option>
                        <option value="1" <?= $edited_user['is_verified'] == '1' ? 'selected' : '' ?>>Yes</option>
                    </select>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="password"><strong>New password</strong></label><br>
                    <input type="text" name="password" id="password" value=""
                        style="margin-top: 6px; padding: 10px; width: 100%; max-width: 500px; box-sizing: border-box;">
                </div>

                <button type="submit" class="auth-card__button">Save changes</button>
            </form>
        </div>
    </main>
</div>

<script>
    document.querySelector('form[action="<?= $action ?>"]').addEventListener('submit', function (e) {
        const password = document.querySelector('#password').value;

        function showError(msg) {
            let notif = document.querySelector('.auth-card__notification');
            if (!notif) {
                notif = document.createElement('div');
                notif.className = 'auth-card__notification';
                document.querySelector('.app-content__card').prepend(notif);
            }
            notif.innerText = msg;
        }

        if (password !== '' && password.length < 10) {
            e.preventDefault();
            showError('New password must be at least 10 characters long');
        }
    });
</script>

<?= $footer ?>