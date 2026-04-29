<?php $page_title = 'Users'; require_once __DIR__ . '/includes/header.php'; ?>

            <section class="content-section">
                <h1>User Directory</h1>
                <p>Create new RiftMind users or search existing users by name, email, home phone, or cell phone.</p>

                <div class="user-actions">
                    <a class="user-action-card" href="user-create.php">
                        <span>Create user</span>
                        <small>Add a new customer record with contact details.</small>
                    </a>
                    <a class="user-action-card" href="user-search.php">
                        <span>Search users</span>
                        <small>Find users by names, email addresses, or phone numbers.</small>
                    </a>
                </div>
            </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
