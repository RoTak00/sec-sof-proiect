<?= $head ?>

<div class="app-layout">
    <?= $navbar ?>

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