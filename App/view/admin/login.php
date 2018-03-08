<?php
/**
 * @var string|null $error
 * @var string|null $username
 * @var string|null $password
 */
?>
<?php if ($error) : ?>
    <div class="alert alert-danger" role="alert">
        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
        <span class="sr-only">Error:</span>
        <?= $error ?>
    </div>
<?php endif; ?>
<form method="post" action="">
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" value="<?= $username ?>" name="username" class="form-control" id="username" placeholder="Username">
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" value="<?= $password ?>" name="password" class="form-control" id="password" placeholder="Password">
    </div>
    <button type="submit" class="btn btn-default">Login</button>
</form>
