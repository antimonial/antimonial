<h1>Active users</h1>

<ul>
<?php foreach ($users as $user): ?>
    <li><?= e($user->name) ?></li>
<?php endforeach; ?>
</ul>
