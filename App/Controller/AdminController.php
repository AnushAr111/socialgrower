<?php

namespace App\Controller;


use App\Core\App;
use App\Core\Controller;
use App\Core\Url;

class AdminController extends Controller
{
    protected $layout = 'admin';

    public function loginAction()
    {
        if (!empty($_POST)) {
            $username = $_POST['username'] ?? null;
            $password = $_POST['password'] ?? null;

            if ($username == getenv('admin_username') && $password == getenv('admin_password')) {
                App::$session->set('admin', true);
                return $this->redirect(Url::to('/admin'));
            } else {
                $error = 'Invalid username and/or password.';
            }
        }

        return $this->render('login', [
            'title' => 'Admin Login',
            'error' => $error ?? null,
            'username' => $username ?? null,
            'password' => $password ?? null,
        ]);
    }

    public function logoutAction()
    {
        App::$session->remove('admin');

        return $this->redirect(Url::to('/admin/login'));
    }

    public function indexAction()
    {
        if (!App::$session->has('admin')) {
            return $this->redirect(Url::to('/admin/login'));
        }

        return $this->render('users', [
            'title' => 'Recent users',
            'users' => json_decode(file_get_contents( __DIR__ . '/../../db/users.json'), true) ?? []
        ]);
    }
}