<?= $head ?>

<div class="app-layout">
    <aside class="app-sidebar">
        <div class="app-sidebar__brand">AuthX</div>

        <nav class="app-sidebar__nav">
            <a href="/" class="app-sidebar__link app-sidebar__link--active">Home</a>
            <a href="/account" class="app-sidebar__link">Account</a>
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
    </main>
</div>
<?= $footer ?>