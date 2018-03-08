<?php

namespace App\Controller;

use App\Core\App;
use App\Core\Controller;
use App\Core\Exception\HttpException;
use App\Core\Url;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Google_Client;
use Google_Service_Books;

class IndexController extends Controller
{
    protected $layout = 'app';

    public function indexAction()
    {
        return $this->render('index', [
            'title' => 'login'
        ]);
    }

    public function loginAction($code = null)
    {
        $helper = $this->getFbClient()->getRedirectLoginHelper();

        if ($code) {
            try {
                $accessToken = $helper->getAccessToken();
            } catch (FacebookSDKException $e) {
                throw new HttpException($e->getMessage());
            }

            App::$session->set('access_token', $accessToken->getValue());

            return $this->redirect(Url::to('/recommendations'));
        } else {
            return $this->redirect($helper->getLoginUrl(Url::to('/login', true), ['email', 'public_profile', 'user_friends', 'user_location', 'user_birthday', 'user_actions.books']));
        }
    }

    public function logoutAction()
    {
        App::$session->remove('access_token');

        return $this->redirect(Url::to('/'));
    }

    public function recommendationsAction()
    {
        if (!App::$session->has('access_token')) {
            return $this->redirect(Url::to('/'));
        }

        $client = $this->getFbClient();
        $client->setDefaultAccessToken(App::$session->get('access_token'));

        $this->saveUserInfo($client->get('/me?fields=email,id,name,birthday,gender,location,picture')->getDecodedBody());

        $books = [];

        foreach (array_column($client->get('/me/friends')->getDecodedBody()['data'], 'id') as $friendId) {
            foreach (array_column($client->get("/$friendId/books")->getDecodedBody()['data'], 'name') as $bookName) {
                foreach ( $this->getGoogleBookService()->volumes->listVolumes($bookName)->getItems() as $readBook) {
                    if ($readBook->getVolumeInfo()->getAuthors()) {
                        $authors = implode(',', $readBook->getVolumeInfo()->getAuthors());
                        foreach ($this->getGoogleBookService()->volumes->listVolumes('inauthor:' . $authors)->getItems() as $book) {
                            $books[] = $book->getVolumeInfo();
                        }
                    }
                }
            }
        }

        return $this->render('recommendations', [
            'title' => 'recommendations',
            'books' => $books
        ]);
    }

    public function errorAction($error)
    {
        echo $error;
    }

    private function getFbClient()
    {
        $client = new Facebook([
            'app_id' => getenv('facebook_app_id'),
            'app_secret' => getenv('facebook_app_secret'),
            'default_graph_version' => 'v2.2',
        ]);

        return $client;
    }

    private function getGoogleBookService()
    {
        $client = new Google_Client();
        $client->setApplicationName("Google Books with PHP Tutorial Application");
        $client->setDeveloperKey( getenv('google_api_key') );

        return new Google_Service_Books($client);
    }

    private function saveUserInfo($data)
    {
        $dbPath = __DIR__ . '/../../db/users.json';

        if (!file_exists($dbPath)) {
            file_put_contents($dbPath, json_encode([]));
        }

        $users = json_decode(file_get_contents($dbPath), true);

        if (!in_array($data['id'], array_column($users, 'id'))) {
            $users[] = $data;
        }

        file_put_contents($dbPath, json_encode($users));
    }
}