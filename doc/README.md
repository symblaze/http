# Documentation

SymBlaze HTTP bundle provides a set of tools to build RESTful APIs with Symfony. The following documentation will guide
you through the usage of the bundle.

## API Controller

The bundle provides a base controller class to [build API controllers](./api-controller.md). The base controller class
is located at `\Symblaze\Bundle\Http\Controller\ApiController`. It provides all methods provided by the Symfony's base
controller class and adds some additional methods to help you build your API controllers.

## HTTP Requests

The bundle provides two types of HTTP requests, both of which are based on the Symfony's HTTP request. The first type
provides methods to retrieve data from the request in a more simple way. The second type provides an automatic
validation of the request data. You can read more about the requests in
the [HTTP requests documentation](./requests.md).

## HTTP Exceptions

All exceptions thrown by the bundle are based on `\Symblaze\Bundle\Http\Exception\HttpException` which extends the
Symfony's Http exception. This makes it easy for you handle all exceptions if required. In Addition,
an `\Symblaze\Bundle\Http\Exception\RenderableExceptionInterface` interface allows you to render create Http responses
from your [exceptions](./exceptions.md).
