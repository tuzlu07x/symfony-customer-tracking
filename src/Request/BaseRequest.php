<?php

namespace App\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseRequest
{
    public function __construct(protected ValidatorInterface $validator)
    {
        $this->populate();
    }

    public function getRequest(): Request
    {
        return Request::createFromGlobals();
    }

    public function validated()
    {
        $errors = $this->validator->validate($this);
        $messages = ['message' => 'validation_failed', 'errors' => []];

        /** @var \Symfony\Component\Validator\ConstraintViolation  */
        foreach ($errors as $message) {
            $messages['errors'][] = [
                'property' => $message->getPropertyPath(),
                'value' => $message->getInvalidValue(),
                'message' => $message->getMessage(),
            ];
        }

        if (count($messages['errors']) > 0) {
            $response = new JsonResponse($messages, 201);
            $response->send();

            exit;
        }
        return $this->getRequest()->toArray();
    }

    protected function populate(): void
    {
        $request = $this->getRequest()->toArray();

        foreach ($request as $property => $value) {
            if (property_exists($this, $property)) {
                $this->{$property} = $value;
            }
        }
    }
}
