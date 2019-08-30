<?php 
	require_once dirname(__FILE__).'/../inc/globals.php';
	use Psr\Http\Message\ResponseInterface as Response;
	use Psr\Http\Message\ServerRequestInterface as Request;
	use Slim\Factory\AppFactory;
	require __DIR__ . '/../vendor/autoload.php';

	$app = AppFactory::create();

	$app->addRoutingMiddleware();
	$errorMiddleware = $app->addErrorMiddleware(true, true, true);

	$app->setBasePath('/sac');

	$app->get('/', 'App\Controllers\HomeController:home');

	$app->get('/api/payment/', 'App\Controllers\PaymentController:apiIndex');
	$app->post('/api/attending-event/update-subscription', 'App\Controllers\AttendingEventController:updateSubscription');

	$app->get('/login', 'App\Controllers\AuthController:loginForm');
	$app->get('/inscricao', 'App\Controllers\AuthController:subscriptionForm');
	$app->post('/login', 'App\Controllers\AuthController:login');
	$app->get('/logout', 'App\Controllers\AuthController:logout');

	$app->get('/admin/evento', 'App\Controllers\EventController:adminIndex');
	$app->post('/admin/evento', 'App\Controllers\EventController:create');
	$app->get('/admin/campeonato', 'App\Controllers\CompetitionController:index');
	$app->post('/admin/campeonato', 'App\Controllers\CompetitionController:create');
	$app->get('/admin/inscricoes', 'App\Controllers\SubscribeController:index');
	$app->get('/admin/pagamento', 'App\Controllers\SubscribeController:payment');
	$app->post('/admin/pagamento', 'App\Controllers\SubscribeController:paymentCreate');

	$app->get('/times', 'App\Controllers\TeamController:index');

	// Run app
	$app->run();
?>