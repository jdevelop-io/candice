<?php

declare(strict_types=1);

namespace Candice\Organization\Application\RegistrationNumberValidation;

use Candice\Organization\Domain\Exception\InvalidRegistrationNumberException;
use Candice\Organization\Domain\Factory\RegistrationNumberFactory;

final readonly class RegistrationNumberValidationService
{
    public function __construct(private RegistrationNumberFactory $registrationNumberFactory)
    {
    }

    public function execute(RegistrationNumberValidationRequest $request): RegistrationNumberValidationResponse
    {
        try {
            $this->registrationNumberFactory->create($request->getRegistrationNumber());

            return new RegistrationNumberValidationResponse(valid: true);
        } catch (InvalidRegistrationNumberException $exception) {
            return new RegistrationNumberValidationResponse(valid: false, reason: $exception->getMessage());
        }
    }
}
