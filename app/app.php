<?php
    date_default_timezone_set('America/Los_Angeles');
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Book.php";
    require_once __DIR__."/../src/Author.php";
    require_once __DIR__."/../src/Checkout.php";
    require_once __DIR__."/../src/Patron.php";
    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();
    $server = 'mysql:host=localhost:8889;dbname=library';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);
    $app = new Silex\Application();
    $app['debug'] = true;
    $app->register(
        new Silex\Provider\TwigServiceProvider(),
        array('twig.path' => __DIR__.'/../views')
    );
    // Book Routes
    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.html.twig', array());
    });

    $app->get("/librarian", function() use ($app) {
        return $app['twig']->render('librarian.html.twig', array('books'=>Book::getAll(), 'copies'=>Copy::getAll()));
    });

    $app->post("/addbook", function() use ($app) {
        $book_name = $_POST['name'];
        $author_name = $_POST['author'];
        $result = Book::newBook($book_name, $author_name);
        // if ($result == 0)
        // {
        //     //render success created new copy
        // } elseif ($result == 1)
        // {
        //     //render success created new book & copy
        // } elseif ($result == 2)
        // {
        //     //render created new author, book, copy
        // }
        return $app['twig']->render('librarian.html.twig', array('result'=>$result));

    });

    return $app;
?>
