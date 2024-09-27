<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e,$request){
            $code = ResponseAlias::HTTP_NOT_ACCEPTABLE;
            if ($e->getCode() >= 100 && $e->getCode() <= 599)
                $code = $e->getCode();
            if($e instanceof  ValidationException){
                return new JsonResponse($e->errors(), $code);
            }
            else if($e instanceof RouteNotFoundException){
                return new JsonResponse(array("errors" =>"Unauthorized" ), \Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED);

            }
            else {
                return new JsonResponse(array("errors" => $e->getMessage()), is_numeric($code) ? $code : \Symfony\Component\HttpFoundation\Response::HTTP_NOT_IMPLEMENTED);
            }
        });
    })->create();
