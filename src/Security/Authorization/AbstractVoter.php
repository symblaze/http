<?php

declare(strict_types=1);

namespace Symblaze\Bundle\Http\Security\Authorization;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Abstract Voter
 * All Voters should extend this class. It provides some basic functionality
 * and makes sure that the voter source code is descriptive to the developer.
 *
 * @template TAttribute of string
 * @template TSubject of mixed
 *
 * @extends Voter<TAttribute, TSubject>
 */
abstract class AbstractVoter extends Voter
{
    public const LIST = 'list';
    public const GET = 'get';
    public const CREATE = 'create';
    public const UPDATE = 'update';
    public const DELETE = 'delete';

    /**
     * @var string[] the attributes that this voter supports
     */
    protected array $supportedAttributes = [];

    /**
     * @var class-string the subject that this voter supports
     */
    protected string $supportedClass;

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $method = 'can'.ucfirst($attribute);
        $user = $token->getUser();

        return ! is_null($user) && $this->$method($subject, $user);
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (! in_array($attribute, $this->supportedAttributes, true)) {
            return false;
        }

        if (! is_a($subject, $this->supportedClass, true)) {
            return false;
        }

        return true;
    }
}
