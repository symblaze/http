<?php

declare(strict_types=1);

namespace Symblaze\Bundle\Http\Tests\Security\Authorization;

use stdClass;
use Symblaze\Bundle\Http\Security\Authorization\AbstractVoter;
use Symblaze\Bundle\Http\Tests\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class AbstractVoterTest extends TestCase
{
    /** @test */
    public function vote_with_empty_attributes(): void
    {
        $sut = new class() extends AbstractVoter {
            protected array $supportedAttributes = [];
            protected string $supportedClass = stdClass::class;
        };
        $token = $this->createMock(TokenInterface::class);

        $actual = $sut->vote($token, new stdClass(), []);

        $this->assertSame(VoterInterface::ACCESS_ABSTAIN, $actual);
    }

    /** @test */
    public function vote_with_non_supported_attribute(): void
    {
        $sut = new class() extends AbstractVoter {
            protected array $supportedAttributes = [self::CREATE];
            protected string $supportedClass = stdClass::class;
        };
        $token = $this->createMock(TokenInterface::class);

        $actual = $sut->vote($token, new stdClass(), [AbstractVoter::UPDATE]);

        $this->assertSame(VoterInterface::ACCESS_ABSTAIN, $actual);
    }

    /** @test */
    public function vote_with_non_supported_subject(): void
    {
        $sut = new class() extends AbstractVoter {
            protected array $supportedAttributes = [self::CREATE];
            protected string $supportedClass = stdClass::class;
        };
        $token = $this->createMock(TokenInterface::class);

        $actual = $sut->vote(
            $token,
            new class() {
            },
            [AbstractVoter::CREATE]
        );

        $this->assertSame(VoterInterface::ACCESS_ABSTAIN, $actual);
    }

    /** @test */
    public function vote_with_grantable_resource(): void
    {
        $sut = new class() extends AbstractVoter {
            protected array $supportedAttributes = [self::CREATE];
            protected string $supportedClass = stdClass::class;

            public function canCreate(stdClass $subject, UserInterface $user): bool
            {
                return true;
            }
        };
        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($this->createMock(UserInterface::class));

        $actual = $sut->vote($token, new stdClass(), [AbstractVoter::CREATE]);

        $this->assertSame(VoterInterface::ACCESS_GRANTED, $actual);
    }

    /** @test */
    public function vote_on_a_non_accessible_resource(): void
    {
        $sut = new class() extends AbstractVoter {
            protected array $supportedAttributes = [self::CREATE];
            protected string $supportedClass = stdClass::class;

            public function canCreate(stdClass $subject, UserInterface $user): bool
            {
                return false;
            }
        };
        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($this->createMock(UserInterface::class));

        $actual = $sut->vote($token, new stdClass(), [AbstractVoter::CREATE]);

        $this->assertSame(VoterInterface::ACCESS_DENIED, $actual);
    }

    /** @test */
    public function vote_on_a_class_string(): void
    {
        $sut = new class() extends AbstractVoter {
            protected array $supportedAttributes = [self::CREATE];
            protected string $supportedClass = stdClass::class;

            public function canCreate(string $subject, UserInterface $user): bool
            {
                return true;
            }
        };
        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($this->createMock(UserInterface::class));

        $actual = $sut->vote($token, stdClass::class, [AbstractVoter::CREATE]);

        $this->assertSame(VoterInterface::ACCESS_GRANTED, $actual);
    }
}
