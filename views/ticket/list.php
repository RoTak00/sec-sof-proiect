<?= $head ?>

<div class="app-layout">
    <aside class="app-sidebar">
        <div class="app-sidebar__brand">AuthX</div>

        <nav class="app-sidebar__nav">
            <a href="/" class="app-sidebar__link">Home</a>
            <a href="/account" class="app-sidebar__link">Account</a>
            <a href="<?= $tickets_link ?>" class="app-sidebar__link app-sidebar__link--active">Tickets</a>
            <?php if ($admin) { ?>
                <a href="/users" class="app-sidebar__link">Users</a>
            <?php } ?>
            <a href="<?= $logout ?>" class="app-sidebar__link">Log out</a>
        </nav>
    </aside>

    <main class="app-content">
        <?= $notification ?>

        <div class="app-content__card">
            <h1 class="app-content__title">Tickets</h1>

            <?php if (!empty($tickets)) { ?>
                <?php foreach ($tickets as $ticket) { ?>
                    <div style="padding: 15px 0; border-bottom: 1px solid #eee;">
                        <h3 style="margin: 0 0 8px;">
                            <a href="/tickets/ticket/view/<?= (int) $ticket['ticket_id'] ?>">
                                <?= htmlspecialchars($ticket['title']) ?>
                            </a>
                        </h3>

                        <div>
                            <strong>Status:</strong> <?= htmlspecialchars($ticket['status']) ?>
                            |
                            <strong>Severity:</strong> <?= htmlspecialchars($ticket['severity']) ?>
                            |
                            <strong>Created:</strong> <?= htmlspecialchars($ticket['created_at']) ?>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p>No tickets found.</p>
            <?php } ?>
        </div>
    </main>
</div>

<?= $footer ?>