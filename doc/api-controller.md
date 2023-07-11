# Api Controller

The SymBlaze http bundle is designed around RESTful JSON APIs. The Api controller is a PHP class that reads the request
and creates a JSON response. While - Generally speaking - a controller could be any callable, the examples below shows
how to use the Api controller for a specific resource in your application. The Api controller provides all the
functionality provided by
the [AbstractController](https://symfony.com/doc/current/controller.html#the-base-controller-class-services)
class from Symfony.

## Standard methods

One of the goals of the SymBlaze bundles is to provide a standard way of building RESTful JSON APIs, in few words, to
help Symfony developers to design simple, consistent and **Resource-Oriented** APIs. Based on this, try to use standard
methods for your resources: `list`, `get`, `create`, `update` and `delete`.

The following table describes how to map standard methods to HTTP methods:

| Standard method | HTTP mapping                  | Http Request Body | Http Response Body |
|-----------------|-------------------------------|-------------------|--------------------|
| list            | GET `<collection URL>`        | No                | Resource* list     |
| get             | GET `<resource URL>`          | No                | Resource*          |
| create          | POST `<collection URL>`       | Resource          | Resource*          |
| update          | PUT or PATCH `<resource URL>` | Resource          | Resource*          |
| delete          | DELETE `<resource URL>`       | No                | No or Resource*    |

## Create JSON responses

The Api controller provides a simple way to create JSON responses with the standard HTTP status codes. The following
methods are available:

| Method name | HTTP status code | Related standard method   |
|-------------|------------------|---------------------------|
| ok          | 200              | list, get, create, update |
| created     | 201              | create                    |
| badRequest  | 400              | create, update            |

And more methods will be added in the future.

Each method expects the `$data` as the first parameter, and an optional second parameter `$groups` to define the
serialization groups.

## Example

Below is an example of an Article controller that uses the Api controller:

```php
<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Article;
use App\Article\CreateArticleRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

class ArticleController extends ApiController
{
    public function get(Article $article): JsonResponse
    {
        return $this->ok($article, ['article:read']);
    }
    
    public function create(CreateArticleRequest $request): JsonResponse
    {
       // authorize and validate the request
        
       // create the article $article
       
       // ...
       
       return $this->created($article, ['article:read']);  
    }
}
```
