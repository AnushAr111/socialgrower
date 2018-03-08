<?php
/**
 * @var array $users
 */
?>

<table class="table table-striped">
    <thead>
    <tr>
        <th>Profile pic</th>
        <th>ID</th>
        <th>Name</th>
        <th>Birthday</th>
        <th>Gender</th>
        <th>Location</th>
        <th>Email</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user) :?>
    <tr>
        <td><img src="<?= $user['picture']['data']['url'] ?>"></td>
        <td><?= $user['id'] ?></td>
        <td><?= $user['name'] ?></td>
        <td><?= $user['birthday'] ?></td>
        <td><?= $user['gender'] ?></td>
        <td><?= $user['location']['name'] ?></td>
        <td><?= $user['email'] ?? 'Not set' ?></td>
    </tr>
    <?php endforeach;?>
    </tbody>
</table>
