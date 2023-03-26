<?php

namespace App\Security;

use App\Entity\Statement;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class StatementVoter extends Voter
{
    public function __construct(
        private readonly Security $security,
    ) {
    }

    public const EDIT = 'edit';
    public const DELETE = 'delete';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::EDIT, self::DELETE], true)) {
            return false;
        }
        // only vote on `Post` objects
        if (!$subject instanceof Statement) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        // admin can do anything
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        $user = $token->getUser();
        /* @var Statement $subject */
        return $user === $subject->getAuthor();
    }
}
