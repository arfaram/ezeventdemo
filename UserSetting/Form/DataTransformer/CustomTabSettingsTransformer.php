<?php

declare(strict_types=1);

namespace EzSystems\DemoBundle\UserSetting\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Class CustomTabSettingsTransformer
 * @package EzSystems\DemoBundle\UserSetting\Form\DataTransformer
 */
class CustomTabSettingsTransformer implements DataTransformerInterface
{
    /**
     * @param mixed $value
     * @return array|null
     */
    public function transform($value): ?array
    {
        if (null === $value) {
            return null;
        }

        if (!is_string($value)) {
            throw new TransformationFailedException(
                sprintf('Expected a %s, got %s instead', 'string', gettype($value))
            );
        }

        return [
            'pagination_limit' => $value,
        ];
    }

    /**
     * @param mixed $value
     * @return int|null
     */
    public function reverseTransform($value): ?int
    {
        if (empty($value)) {
            return null;
        }

        return (int) $value['pagination_limit'];

    }
}