<?= $head ?>
<div class="app-layout">
    <?= $navbar ?>

    <main class="app-content">
        <?= $notification ?>

        <div class="app-content__card">
            <h1 class="app-content__title">Users</h1>

            <?php if (!empty($users)) { ?>
                <table style="width:100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid #ddd; text-align: left;">
                            <th style="padding: 10px;">ID</th>
                            <th style="padding: 10px;">Email</th>
                            <th style="padding: 10px;">Role</th>
                            <th style="padding: 10px;">Verified</th>
                            <th style="padding: 10px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user) { ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 10px;"><?= (int) $user['user_id'] ?></td>
                                <td style="padding: 10px;">
                                    <?= htmlspecialchars($user['email']) ?>
                                </td>
                                <td style="padding: 10px;">
                                    <?= !empty($user['role']) ? htmlspecialchars($user['role']) : '-' ?>
                                </td>
                                <td style="padding: 10px;">
                                    <?= $user['is_verified'] ? 'Yes' : 'No' ?>
                                </td>
                                <td style="padding: 10px;">
                                    <a href="<?= $user['edit'] ?>">Edit</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p>No users found.</p>
            <?php } ?>
        </div>
    </main>
</div>

<?= $footer ?>