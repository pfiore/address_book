<?php
	require_once __DIR__."/../vendor/autoload.php";
	require_once __DIR__."/../src/address_book.php";

	session_start();
	if (empty($_SESSION['contact_list'])) {
		$_SESSION['contact_list'] = array();
	}

	$app = new Silex\Application();
	$app->register(new Silex\Provider\TwigServiceProvider(), array(
		'twig.path' => __DIR__.'/../views'
	));

	$app->get("/", function() use ($app) {
		return $app['twig']->render('address_book_home.twig', array('contacts' => Contact::getAll()));
	});

	$app->post("/create_contact", function() use ($app) {
		$contact = new Contact($_POST['name'], $_POST['phone'], $_POST['address']);
		$contact ->save();
		return $app['twig']->render('create_contact.twig', array('newcontact' => $contact));
	});

	$app->post("/delete_all", function() use ($app) {
		Contact::deleteAll();
		return $app['twig']->render('delete_all.twig');
	});

	return $app;
?>
