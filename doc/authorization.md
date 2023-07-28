# Authorization

## Abstract Voter

The `AbstractVoter` class is an essential part of the SymBlaze Http bundle, which provides security and authorization
features for Symfony-based applications. It serves as a foundation for creating custom voters to control access to
various actions or resources in your application.

To make use of the `AbstractVoter` class, you need to create a new class that extends it and implement configure
it to work with your resource. The following example shows how to create a voter for a `Tweet` resource.

```php
<?php
declare(strict_types=1);

namespace App\Tweet;

use App\Entity\Tweet;

use Symblaze\Bundle\Http\Security\Authorization\AbstractVoter;

class TweetVoter extends AbstractVoter
{
    /**
     * @var string[] the attributes that this voter supports
     */
    protected array $supportedAttributes = [
        self::CREATE,
        // you can add more attributes here
    ];
    
    /**
     * @var class-string the subject that this voter supports
     */
    protected string $supportedClass = Tweet::class;
}
```

For each supported attribute, you need to implement a method that checks if the current user has access to the
resource. The method name should be the attribute name prefixed with `can`. For example, if you have an attribute
`create`, then the method name should be `canCreate`.

```php
<?php
declare(strict_types=1);

namespace App\Tweet;

use App\Entity\Tweet;
use Symblaze\Bundle\Http\Security\Authorization\AbstractVoter;
use Symfony\Component\Security\Core\User\UserInterface;

class TweetVoter extends AbstractVoter
{
    /**
     * @var string[] the attributes that this voter supports
     */
    protected array $supportedAttributes = [
        self::CREATE,
    ];
    
    /**
     * @var class-string the subject that this voter supports
     */
    protected string $supportedClass = Tweet::class;
    
    /**
     * Checks if the current user can create a tweet.
     *
     * @param Tweet $tweet the tweet to check
     * @param UserInterface $user the current user
     * @return bool true if the user can create a tweet, false otherwise
     */
    protected function canCreate(Tweet $tweet, UserInterface $user): bool
    {
        // your logic here
    }
}
```
