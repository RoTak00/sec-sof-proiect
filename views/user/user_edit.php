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
                    <label for="password"><strong>New password</strong></label><br>
                    <input type="text" name="password" id="password" value=""
                        style="margin-top: 6px; padding: 10px; width: 100%; max-width: 500px; box-sizing: border-box;">
                </div>

                <button type="submit" class="auth-card__button">Save changes</button>
            </form>
        </div>
    </main>
</div>

<?= $footer ?>