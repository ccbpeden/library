<?php
    date_default_timezone_set('America/Los_Angeles');
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Book.php";
    require_once __DIR__."/../src/Author.php";
    require_once __DIR__."/../src/Checkout.php";
    require_once __DIR__."/../src/Patron.php";
    require_once __DIR__."/../src/Copy.php";
    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();
    $server = 'mysql:host=localhost:8889;dbname=library';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);
    $app = new Silex\Application();
    $app['debug'] = true;

    use Symfony\Component\Debug\Debug;
    Debug::enable();

    $app->register(
        new Silex\Provider\TwigServiceProvider(),
        array('twig.path' => __DIR__.'/../views')
    );
    // Book Routes
    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.html.twig', array());
    });

    $app->get("/librarian", function() use ($app) {
        return $app['twig']->render('librarian.html.twig', array('foundauthor'=>null, 'foundbooks'=>null,'foundbook'=>null,'foundauthors'=>null, 'foundcopies'=>null, 'foundcheckouts'=>null, 'result'=>null));
    });

    $app->post("/addbook", function() use ($app) {
        $book_name = $_POST['name'];
        $author_name = $_POST['author'];
        $result = Book::newBook($book_name, $author_name);
        return $app['twig']->render('librarian.html.twig', array('foundauthor'=>null, 'foundbooks'=>null,'foundbook'=>null, 'foundauthors'=>null, 'foundcopies'=>null, 'foundcheckouts'=>null, 'result'=>$result));

    });
    $app->post("/searchtitle", function() use($app){

        $foundbook = Book::findbyName($_POST['title']);
        $foundauthors = $foundbook->getAuthors();
        $foundbookid = $foundbook->getId();
        $foundcopies = Copy::findbookid($foundbookid);
        $foundcheckouts = array();
        foreach($foundcopies as $foundcopy)
        {
            $foundcheckout = Checkout::findcopyid($foundcopy->getId());
            if (isset($foundcheckout))
            {
                array_push($foundcheckouts, $foundcheckout);
            }
        }
        var_dump($foundcheckouts);
        return $app['twig']->render('librarian.html.twig', array('foundauthor'=>null, 'foundbooks'=>null,'foundbook'=>$foundbook,'foundauthors'=>$foundauthors, 'foundcopies'=>$foundcopies, 'foundcheckouts'=>$foundcheckouts, 'result'=>null));
    });

    $app->post("/searchauthor", function() use ($app){
        $foundauthor = Author::findbyName($_POST['author']);
        $foundbooks = $foundauthor->getBooks();
        return $app['twig']->render('librarian.html.twig', array('foundauthor'=>$foundauthor, 'foundbooks'=>$foundbooks,'foundbook'=>null,'foundauthors'=>null, 'foundcopies'=>null, 'foundcheckouts'=>null, 'result'=>null));

    });
    $app->get('/bookinfo/{id}', function($id) use($app){
        $foundbook = Book::find($id);
        $foundcopies = Copy::findbookid($foundbook->getId());
        $foundcheckouts = array();
        foreach($foundcopies as $foundcopy)
        {
            $foundcheckout = Checkout::findcopyid($foundcopy->getId());
            if (isset($foundcheckout))
            {
                array_push($foundcheckouts, $foundcheckout);
            }
        }
        return $app['twig']->render('bookinfo.html.twig',array( 'foundbooks'=>null,'foundbook'=>$foundbook,'foundauthors'=>null, 'foundcopies'=>$foundcopies, 'foundcheckouts'=>$foundcheckouts, 'result'=>null));
    });
    $app->get("/patron", function() use ($app){
        return $app['twig']->render('patron.html.twig');
    });

    return $app;
?>
