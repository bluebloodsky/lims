<?php
$app->get('/', function ($request, $response, $args) {
    $response->write("Welcome to use!");
	return $response;
});
