<aside class="app-sidebar">
    <div class="app-sidebar__brand">AuthX</div>

    <nav class="app-sidebar__nav">
        <a href="<?= $home_link ?>"
            class="app-sidebar__link <?= $active === 'home' ? 'app-sidebar__link--active' : '' ?>">Home</a>
        <a href="<?= $account_link ?>"
            class="app-sidebar__link <?= $active === 'account' ? 'app-sidebar__link--active' : '' ?>">Account</a>
        <a href="<?= $tickets_link ?>"
            class="app-sidebar__link <?= $active === 'tickets' ? 'app-sidebar__link--active' : '' ?>">Tickets</a>

        <?php if ($admin) { ?>
            <a href="<?= $users_link ?>"
                class="app-sidebar__link <?= $active === 'users' ? 'app-sidebar__link--active' : '' ?>">Users</a>
        <?php } ?>

        <a href="<?= $logout ?>" class="app-sidebar__link">Log out</a>
    </nav>
</aside>