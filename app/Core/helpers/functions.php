<?php

/**
 * Dumps a variable's content and terminates the script.
 * Useful for quick debugging.
 *
 * @param mixed $var The variable to display.
 * @return void
 */
function dd($var): void
{
  echo "<pre>";
  print_r($var);
  echo "</pre>";
  die;
}

/**
 * Builds an absolute URL using the given relative path.
 *
 * @param string $path The relative path.
 * @return string The full URL.
 */
function base_url(string $path): string
{
  return BASE_PATH . ltrim($path, '/');
}

/**
 * Sanitizes a string to prevent XSS attacks and injections.
 *
 * @param string $data The data to sanitize.
 * @return string The sanitized data.
 */
function clean(string $data): string
{
  return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Includes a view file and passes optional data to it.
 *
 * @param string $view The view file name (without extension).
 * @param array $data The data to pass to the view.
 * @throws Exception if the view file is not found.
 * @return void
 */
function view(string $view, array $data = []): void
{
  $viewFile = base_url('/app/Views/' . $view . '.php');

  if (is_readable($viewFile)) {
    extract($data);
    require $viewFile;
  } else {
    throw new \Exception("View file '$viewFile' not found.");
  }
}

/**
 * Instantiates and returns a model object.
 *
 * @param string $model The model class name.
 * @throws Exception if the model class does not exist.
 * @return object The model instance.
 */
function model(string $model): object
{
  $modelClass = "App\\Models\\" . $model;

  if (class_exists($modelClass)) {
    return new $modelClass();
  } else {
    throw new \Exception("Model class '$modelClass' not found.");
  }
}

/**
 * Retrieves and decodes JSON input from the request body.
 *
 * @return array|null The decoded JSON as an associative array or null if empty.
 */
function getRequestBody(): ?array
{
  $json = file_get_contents("php://input");

  return $json ? json_decode($json, true) : null;
}

/**
 * Sends a JSON response with a given status code.
 *
 * @param mixed $data The data to send in the response.
 * @param int $status The HTTP status code (default: 200).
 * @return void
 */
function response($data, int $status = 200): void
{
  http_response_code($status);
  header('Content-Type: application/json');
  echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
  exit();
}
