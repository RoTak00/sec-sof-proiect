<?= $head ?>

<div class="app-layout">
    <?= $navbar ?>

    <main class="app-content">
        <?= $notification ?>

        <div class="app-content__card">
            <h1 class="app-content__title">Tickets</h1>

            <?php if (!empty($tickets)) { ?>
                <table style="width:100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid #ddd; text-align: left;">
                            <th style="padding: 10px;">ID</th>
                            <th style="padding: 10px;">Title</th>
                            <th style="padding: 10px;">Status</th>
                            <th style="padding: 10px;">Severity</th>
                            <th style="padding: 10px;">Created</th>
                            <th style="padding: 10px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tickets as $ticket) { ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 10px;"><?= (int) $ticket['ticket_id'] ?></td>
                                <td style="padding: 10px;">
                                    <?= htmlspecialchars($ticket['title']) ?>
                                </td>
                                <td style="padding: 10px;">
                                    <?= htmlspecialchars($ticket['status']) ?>
                                </td>
                                <td style="padding: 10px;">
                                    <?= htmlspecialchars($ticket['severity']) ?>
                                </td>
                                <td style="padding: 10px;">
                                    <?= htmlspecialchars($ticket['created_at']) ?>
                                </td>
                                <td style="padding: 10px;">
                                    <a href="/tickets/ticket/view/<?= (int) $ticket['ticket_id'] ?>">View</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p>No tickets found.</p>
            <?php } ?>
        </div>
    </main>
</div>

<?= $footer ?>