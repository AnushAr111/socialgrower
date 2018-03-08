<?php

namespace App\Core;

use App\Controller\IndexController;
use App\Core\Exception\HttpException;
use FastRoute\Dispatcher;
use function FastRoute\simpleDispatcher;
use FastRoute\RouteCollector;
use Symfony\Component\Dotenv\Dotenv;

class App
{
    /**
     * @var Session
     */
    public static $session;

    public function start()
    {
        $this->init();
        $request = $this->getRequest();
        $this->dispatch($request);
    }

    public function init()
    {
        self::$session = new Session();

        $dotenv = new Dotenv();
        $dotenv->load(__DIR__.'/../../.env');
    }

    private function getRequest()
    {
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['PATH_INFO'] ?? '/';

        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        return [$httpMethod, $uri];
    }

    private function dispatch($request)
    {
        $dispatcher = simpleDispatcher(require __DIR__ . '/../routes.php');
        list($httpMethod, $uri) = $request;
        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                $this->handle([new IndexController(), 'errorAction'], ['error' => 'page not found'], 404);
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                $this->handle([new IndexController(), 'errorAction'], ['error' => 'bad request'], 405);
                break;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                $this->handleRequest($handler, $vars);
                break;
        }
    }

    public function handleRequest($handler, $vars = [])
    {
        list($controller, $action) = explode('@', $handler);

        try {
            $controller = ('\App\Controller\\' . $controller);
            if (!class_exists($controller)) {
                throw new HttpException(404);
            }
            $controller = new $controller;

            if (!method_exists($controller, $action)) {
                throw new HttpException(404);
            }

            $this->handle([$controller, $action], $vars);
        } catch (HttpException $e) {
            $this->handle([new IndexController(), 'errorAction'], ['error' => 'page not found'], $e->getMessage());
            return;
        }

        exit(1);
    }

    private function handle($callBack, $vars, $code = 200)
    {
        http_response_code($code);
        $return = call_user_func_array($callBack, $vars + $_GET);

        if ($return instanceof Redirect) {
            $this->redirect($return);
        } elseif ($return instanceof View) {
            $this->render($return);
        }

        exit(0);
    }

    private function redirect(Redirect $redirect)
    {
        header(sprintf('location: %s', $redirect->to));
    }

    private function render(View $view)
    {
        ob_start();
        extract($view->vars);
        include __DIR__ . '/../view/' . $view->controller . '/' . $view->view;
        $content = ob_get_clean();
        if ($view->layout) {
            ob_start();
            extract(['view' => $view, 'content' => $content]);
            include __DIR__ . '/../view/layout/' . $view->layout . '.php';

            $content = ob_get_clean();
        }

        echo $content;
    }
}