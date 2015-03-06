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

	$app->post("/create_address", function() use ($app) {
		$contact = new Contact($_POST['name'], $_POST['phone'], $_POST['address']);
		$contact ->save();
		return $app['twig']->render('create_address.twig', array('newcontact' => $contact));
	});

	$app->post("/delete_all", function() use ($app) {
		Contact::deleteAll();
		return $app['twig']->render('delete_all.twig');
	});

	$app->post("/search_results", function() use ($app) {
		$all_contacts = Contact::getAll();
		$contact_matching_search = array();
		foreach ($all_contacts as $contact) {
			if ($contact->searchName($_POST['name_search'])) {
				array_push($contact_matching_search, $contact);
			}
		}
		return $app['twig']->render('search_results.twig', array('results' => $contact_matching_search));
	});
	return $app;
?>