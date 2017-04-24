<?php
// Routes
use App\Controller\iotLogger;

$app->get('/', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

  $logger = new iotLogger($this->db);
  $args = $logger->homepage();

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

$app->post('/', function ($request, $response, $args) {
	$data = $request->getParsedBody();

    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route data:".print_r($data, TRUE));

    $logger = new iotLogger($this->db);
    $logger->save($data);

    // Render index view
    return $response;
});
