<?php
namespace Boxalino\RealTimeUserExperienceApi\Service\ErrorHandler;

class WrongDependencyTypeException extends \Error
{
    /**
     * {@inheritdoc}
     */
    public function __construct(string $message)
    {
        parent::__construct($message, 0, null);
    }
}
