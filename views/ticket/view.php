<?= $head ?>

<div class="app-layout">
    <aside class="app-sidebar">
        <div class="app-sidebar__brand">AuthX</div>

        <nav class="app-sidebar__nav">
            <a href="/" class="app-sidebar__link">Home</a>
            <a href="/account" class="app-sidebar__link">Account</a>
            <a href="<?= $tickets ?>" class="app-sidebar__link app-sidebar__link--active">Tickets</a>
            <?php if ($admin) { ?>
                <a href="/users" class="app-sidebar__link">Users</a>
            <?php } ?>

            <a href="<?= $logout ?>" class="app-sidebar__link">Log out</a>
        </nav>
    </aside>

    <main class="app-content">
        <?php if (!empty($notification)) { ?>
            <div>
                <?= $notification ?>
            </div>
        <?php } ?>

        <div class="app-content__card">
            <h1 class="app-content__title">
                <?= htmlspecialchars($ticket['title']) ?>
            </h1>

            <p><strong>Status:</strong> <?= htmlspecialchars($ticket['status']) ?></p>
            <p><strong>Severity:</strong> <?= htmlspecialchars($ticket['severity']) ?></p>
            <p><strong>Created:</strong> <?= htmlspecialchars($ticket['created_at']) ?></p>
            <p><strong>Updated:</strong> <?= htmlspecialchars($ticket['updated_at']) ?></p>

            <?php if (!empty($ticket['description'])) { ?>
                <hr style="margin: 20px 0;">
                <p><?= nl2br(htmlspecialchars($ticket['description'])) ?></p>
            <?php } ?>
        </div>

        <?php if ($admin || $analyst) { ?>
            <hr style="margin: 20px 0;">

            <form method="post" action="<?= $ticket_edit_action ?? "" ?>">
                <div style="margin-bottom: 15px;">
                    <label for="status"><strong>Change status</strong></label><br>
                    <select class="auth-form__input" name="status" id="status"
                        style="margin-top: 6px; padding: 8px; min-width: 220px;">
                        <option value="open" <?= $ticket['status'] === 'open' ? 'selected' : '' ?>>Open</option>
                        <option value="in_progress" <?= $ticket['status'] === 'in_progress' ? 'selected' : '' ?>>In Progress
                        </option>
                        <option value="resolved" <?= $ticket['status'] === 'resolved' ? 'selected' : '' ?>>Resolved</option>
                    </select>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="message"><strong>Send message to ticket owner</strong></label><br>
                    <textarea class="auth-form__textarea auth-form__input" name="message" id="message" rows="6"
                        style="margin-top: 6px; padding: 10px; width: 100%; max-width: 700px; box-sizing: border-box;"
                        placeholder="Write an email message to the ticket owner..."></textarea>
                </div>

                <button type="submit" class="auth-card__button">Update ticket</button>
            </form>
        <?php } ?>

        <div style="margin-top:15px;">
            <?php if ($admin || $analyst): ?>
                <a href="<?= $tickets ?>" class="auth-card__button">Back to tickets</a>
            <?php else: ?>
                <a href="<?= $tickets ?>" class="auth-card__button">Back to your tickets</a>
            <?php endif; ?>
        </div>
    </main>
</div>

<?= $footer ?>