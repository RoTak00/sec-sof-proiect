<?= $head ?>

<div class="app-layout">
    <aside class="app-sidebar">
        <div class="app-sidebar__brand">AuthX</div>

        <nav class="app-sidebar__nav">
            <a href="/" class="app-sidebar__link app-sidebar__link--active">Home</a>
            <a href="/account" class="app-sidebar__link">Account</a>
            <a href="<?= $tickets ?>" class="app-sidebar__link">Tickets</a>
            <?php
            if ($admin) { ?>
                <a href="/users" class="app-sidebar__link">Users</a>
            <?php } ?>
            <a href="<?= $logout ?>" class="app-sidebar__link">Log out</a>

        </nav>
    </aside>

    <main class="app-content">
        <div class="app-content__card">
            <h1 class="app-content__title">hello <?= $email ?>
            </h1>
        </div>
        <?php if (!empty($notification)) { ?>
            <div>
                <?= $notification ?>
            </div>
        <?php } ?>

        <div class="app-content__card" style="margin-top:20px;">
            <h2 class="app-content__title" style="font-size:20px;">Create Ticket</h2>

            <form method="POST" action="<?= $ticket_action ?>" class="auth-form">
                <input class="auth-form__input" type="text" name="title" placeholder="Title" required>

                <textarea class="auth-form__textarea auth-form__input" name="description"
                    placeholder="Describe your issue..." required></textarea>

                <select class="auth-form__input" name="severity" required>
                    <option value="">Select severity</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>

                <button class="auth-form__button" type="submit">
                    Submit Ticket
                </button>
            </form>
        </div>

        <div style="margin-top:15px;">
            <?php if ($admin || $analyst): ?>
                <a href="<?= $tickets ?>" class="auth-card__button">
                    See all tickets
                </a>
            <?php else: ?>
                <a href="<?= $tickets ?>" class=" auth-card__button">
                    See your tickets
                </a>
            <?php endif; ?>
        </div>
    </main>
</div>
<?= $footer ?>