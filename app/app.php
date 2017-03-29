<?php
  date_default_timezone_set('America/Los_Angeles');
  require_once __DIR__."/../vendor/autoload.php";
  require_once __DIR__."/../src/Person.php";

  use Symfony\Component\Debug\Debug;
  Debug::enable();

  $app = new Silex\Application();

  $app['debug'] = true;

  $server = 'mysql:host=localhost:8889;dbname=update_test';
  $user = 'root';
  $pass = 'root';
  $db = new PDO($server, $user, $pass);

  $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
  ));

  use Symfony\Component\HttpFoundation\Request;
  Request::enableHttpMethodParameterOverride();

  $app->get("/", function () use ($app) {
    return $app['twig']->render('index.html.twig');
  });

  $app->post("/getall", function () use ($app) {
    $results = Person::getAll();
    return $app['twig']->render('lists.html.twig', array ('results'=>$results));
  });

  $app->get("/person/{id}", function ($id) use ($app) {
    $result = Person::find($id);
    return $app['twig']->render('person.html.twig', array ('result'=>$result));
  });

  $app->get("/person/{id}/edit", function ($id) use ($app) {
    $person = Person::find($id);
    return $app['twig']->render('person_edit.html.twig', array ('person'=>$person));
  });

  $app->patch("/person/{id}", function ($id) use ($app) {
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $result = Person::find($id);
    $result->update($name,$gender);
    var_dump($result);
    return $app['twig']->render('person.html.twig', array('result'=>$result));
  });

  $app->delete("/person/{id}", function ($id) use ($app) {
    $person = Person::find($id);
    $person->delete();
    return $app['twig']->render('index.html.twig');
  });

  return $app;
?>
