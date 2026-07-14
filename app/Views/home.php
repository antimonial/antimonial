<h1>Usuarios activos</h1>

<ul>
<?php foreach ($users as $user): ?>
    <li><?= e($user->name) ?></li>
<?php endforeach; ?>
</ul>
