# Http Requests

Requests are vital part of any API. The bundle provides two types of requests, both of which are based on the Symfony's
HTTP request. Both types helps you retrieve data from the current HTTP request being handled by your application, this
includes the input, cookies, headers, files, etc.

## Accessing The Request

To obtain an instance of the current HTTP request via dependency injection, you should type-hint the action method with
the request class you want to use, either the simple request or the auto-validating request.

```php
<?php
declare(strict_types=1);

namespace App\Controller;

use Symblaze\Bundle\Http\Controller\ApiController;
use Symblaze\Bundle\Http\Request\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends ApiController
{
    public function store(Request $request): JsonResponse
    {
        $name = $request->input('name');
        
        // Store the user...
        
        return $this->created($user, ['user:create']);
    }
}
```

## Auto-Validating Requests

To maintain modularity and a component-based architecture, the bundle provides a request class that automatically
validates the request data. You still can use the
Symfony's [Constraints](https://symfony.com/doc/current/validation.html#constraints) that are provided by the Validator
component, or create [custom validation constraints](https://symfony.com/doc/current/validation/custom_constraint.html).

To create a request class, you should extend the `Symblaze\Bundle\Http\Request\ValidatableRequest` class and implement
the`rules() :array` method. The method should return an array of constraints.

Once the validation fails, the request will throw a `\Symblaze\Bundle\Http\Exception\ValidationFailedException`
exception which is a [renderable exception](exceptions.md#renderable-exceptions). This means that you can render a
response from the exception.

```php
<?php
declare(strict_types=1);

namespace App\Request;

use Symblaze\Bundle\Http\Request\ValidatableRequest;

class CreateUserRequest extends ValidatableRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                new NotBlank(),
                new Length(['min' => 3]),
            ],
            'email' => [
                new NotBlank(),
                new Email(),
            ],
        ];
    }
    
    public function allowExtraFields() : bool
    {
       return false;
    }
    
    public function getName(): string
    {
        return (string)$this->input('name');
    }
}
```

To use the request, you should type-hint the action method with the request class you want to use.

```php
<?php
declare(strict_types=1);

namespace App\Controller;

use App\Request\CreateUserRequest;

class UserController extends ApiController
{
    public function store(CreateUserRequest $request): JsonResponse
    {
        $name = $request->getName(); // Look, you can introduce getters!
        
        // Store the user...
        
        return $this->created($user, ['user:create']);
    }
}
```

### Disabling Extra Fields

By default, the request will validate only the fields that are defined in the `rules()` method, and will ignore any
extra fields. If you want to prevent receiving extra fields, you can override the `allowExtraFields() :bool` method and
return `false`.
