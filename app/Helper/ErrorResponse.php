<?php

use App\Exceptions\Types\CustomException;
use App\Helper\ExceptionResponder;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Exception\RfcComplianceException;


function localizedError(string $key, Throwable $e, int $status): JsonResponse
{
    return ExceptionResponder::Error(
        [],
        __($key, ['message' => $e->getMessage()]),
        $status
    );
}

function handleModelNotFoundException(ModelNotFoundException $e): JsonResponse
{
    return localizedError('auth.model_not_found', $e, 404);
}

function handleNotFoundHttpException(NotFoundHttpException $e): JsonResponse
{
    return localizedError('auth.not_found', $e, 404);
}

function handleAuthenticationException(AuthenticationException $e): JsonResponse
{
    return localizedError('auth.authentication_failed', $e, 401);
}

function handleAuthorizationException(AuthorizationException $e): JsonResponse
{
    return localizedError('auth.authorization_failed', $e, 403);
}

function handleQueryException(QueryException $e): JsonResponse
{
    return localizedError('auth.query_failed', $e, 500);
}

function handlePostTooLargeException(PostTooLargeException $e): JsonResponse
{
    return localizedError('auth.post_too_large', $e, 413);
}

function handleValidationException(ValidationException $e): JsonResponse
{
    return ExceptionResponder::Validation([], $e->errors(), __('auth.validation_failed'));
}

function handleMethodNotAllowedHttpException(MethodNotAllowedHttpException $e): JsonResponse
{
    return localizedError('auth.method_not_allowed', $e, 405);
}

function handleTooManyRequestsHttpException(TooManyRequestsHttpException $e): JsonResponse
{
    return localizedError('auth.too_many_requests', $e, 429);
}

function handleTransportException(TransportException $e): JsonResponse
{
    return localizedError('auth.transport_exception', $e, 502);
}

function handleTransportExceptionInterface(TransportExceptionInterface $e): JsonResponse
{
    return localizedError('auth.transport_exception_interface', $e, 502);
}

function handleRfcComplianceException(RfcComplianceException $e): JsonResponse
{
    return localizedError('auth.rfc_compliance', $e, 422);
}

function handleCustomException(CustomException $e): JsonResponse
{
    return localizedError('auth.custom_exception', $e, $e->getStatusCode());
}
