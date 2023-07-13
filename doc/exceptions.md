# Http Exceptions

All exceptions thrown by the bundle are `\Symblaze\Bundle\Http\Exception\HttpException` or its subclasses. This helps to
catch all exceptions thrown by the bundle if needed.

## Renderable Exceptions

All exceptions that implement the `\Symblaze\Bundle\Http\Exception\RenderableExceptionInterface` interface can create
a response object that can be sent to the client. For example,
the `\Symblaze\Bundle\Http\Exception\ValidationFailedException`
renders a response with the status code `400` and the validation errors as JSON.

If your application has a global exception handler, you can use the `RenderableExceptionInterface` to render the
response for the client.

```php
<?php
declare(strict_types=1);

namespace App\Exception;

use Symblaze\Bundle\Http\Exception\RenderableExceptionInterface;
use RuntimeException;

class UnauthorizedException extends RuntimeException implements RenderableExceptionInterface
{
    public function render(): Response;
    {
        return JsonResponse::create(['message' => 'You are not authorized to access this resource.'], 401);
    }
}
```
