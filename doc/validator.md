# Validator

The bundle comes with a validator that allows you to validate entities and create validation errors. If the entity is
invalid, the validator will throw the `\Symblaze\Bundle\Http\Exception\ValidationFailedException` exception. The
exception contains a list of validation errors that can be used to create a response, or you can depend on the
`\Symblaze\Bundle\Http\Exception\RenderableExceptionInterface` interface to render the response automatically.

## Usage

To get instance of the validator, you can inject the `\Symblaze\Bundle\Http\Validation\ValidatorInterface` interface
into your controller or any other service. Then you can use the `abortUnlessValid` method to validate the entity.

### Example

Suppose we have a plain-old-PHP object that represents an author. The goal of the validator is to terminate the
execution and generate a response if the author's data is invalid. For this to work, you will configure a list of
constraints for the author's properties. For example, the author's name should be a string and should not be empty.

```php
namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Author
{   
    #[Assert\NotBlank]
    private string $name;
}
```

Now, you can inject the validator into your controller and use it to validate the author's data.

```php
// ...
use App\Entity\Author;
use Symblaze\Bundle\Http\Validation\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

// ...
public function author(ValidatorInterface $validator): JsonResponse
{
    $author = new Author();

    // ... do something to the $author object
    
    // Validate the author's data and throw an exception if the data is invalid.
    $validator->abortUnlessValid($author);
    
    // ... continue with the execution if the data is valid
    
    return $this->created($author); // see the API controller documentation
}
```

## Constraints

The validator uses the Symfony's validator component to validate the entities. This means that you can use all
constraints provided by the Symfony's validator component. You can read more about the constraints in the [Symfony's
documentation](https://symfony.com/doc/current/validation.html#constraints).
