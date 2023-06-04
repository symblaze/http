<?php

declare(strict_types=1);

namespace Symblaze\Bundle\Http\Tests\Request;

use Symblaze\Bundle\Http\Request\Request;
use Symblaze\Bundle\Http\Tests\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestTest extends TestCase
{
    public static function allDataProvider(): array
    {
        return [
            'empty' => [
                'request' => SymfonyRequest::create(uri: '/'),
                'expected' => [],
            ],
            'query' => [
                'request' => SymfonyRequest::create(uri: '/?foo=bar'),
                'expected' => ['foo' => 'bar'],
            ],
            'request' => [
                'request' => SymfonyRequest::create(
                    uri: '/',
                    method: 'POST',
                    parameters: ['foo' => 'bar']
                ),
                'expected' => ['foo' => 'bar'],
            ],
            'query_and_request' => [
                'request' => SymfonyRequest::create(
                    uri: '/?foo=bar',
                    method: 'POST',
                    parameters: ['bar' => 'baz']
                ),
                'expected' => ['foo' => 'bar', 'bar' => 'baz'],
            ],
            'json_content' => [
                'request' => SymfonyRequest::create(
                    uri: '/',
                    method: 'POST',
                    content: '{"foo":"bar"}'
                ),
                'expected' => ['foo' => 'bar'],
            ],
            'json_content_with_query' => [
                'request' => SymfonyRequest::create(
                    uri: '/?bar=baz',
                    method: 'POST',
                    content: '{"foo":"bar"}'
                ),
                'expected' => ['foo' => 'bar', 'bar' => 'baz'],
            ],
        ];
    }

    public static function inputDataProvider(): array
    {
        return [
            'empty' => [
                'request' => SymfonyRequest::create(uri: '/'),
                'key' => 'foo',
                'expected' => null,
            ],
            'parameters' => [
                'request' => SymfonyRequest::create(
                    uri: '/',
                    method: 'POST',
                    parameters: ['foo' => 'bar']
                ),
                'key' => 'foo',
                'expected' => 'bar',
            ],
            'json_content' => [
                'request' => SymfonyRequest::create(
                    uri: '/',
                    method: 'POST',
                    content: '{"foo":"bar"}'
                ),
                'key' => 'foo',
                'expected' => 'bar',
            ],
        ];
    }

    /** @test */
    public function path(): void
    {
        $request = new SymfonyRequest();
        $requestStack = new RequestStack();
        $requestStack->push($request);
        $sut = new Request($requestStack);

        $actual = $sut->path();

        $this->assertEquals($request->getPathInfo(), $actual);
    }

    /** @test */
    public function method(): void
    {
        $request = new SymfonyRequest();
        $requestStack = new RequestStack();
        $requestStack->push($request);
        $sut = new Request($requestStack);

        $actual = $sut->method();

        $this->assertEquals($request->getMethod(), $actual);
    }

    /** @test */
    public function uri(): void
    {
        $request = new SymfonyRequest();
        $requestStack = new RequestStack();
        $requestStack->push($request);
        $sut = new Request($requestStack);

        $actual = $sut->uri();

        $this->assertEquals($request->getUri(), $actual);
    }

    /** @test */
    public function header(): void
    {
        $request = new SymfonyRequest();
        $requestStack = new RequestStack();
        $requestStack->push($request);
        $sut = new Request($requestStack);

        $actual = $sut->header('Content-Type');

        $this->assertEquals($request->headers->get('Content-Type'), $actual);
    }

    /** @test */
    public function header_default_value(): void
    {
        $request = new SymfonyRequest();
        $requestStack = new RequestStack();
        $requestStack->push($request);
        $sut = new Request($requestStack);

        $actual = $sut->header('Content-Type', 'application/json');

        $this->assertEquals('application/json', $actual);
    }

    /** @test */
    public function headers(): void
    {
        $request = new SymfonyRequest();
        $requestStack = new RequestStack();
        $requestStack->push($request);
        $sut = new Request($requestStack);

        $actual = $sut->headers();

        $this->assertSame($request->headers, $actual);
    }

    /**
     * @test
     *
     * @dataProvider allDataProvider
     */
    public function all(SymfonyRequest $request, array $expected): void
    {
        $requestStack = new RequestStack();
        $requestStack->push($request);
        $sut = new Request($requestStack);

        $actual = $sut->all();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     *
     * @dataProvider inputDataProvider
     */
    public function input(SymfonyRequest $request, string $key, ?string $expected): void
    {
        $requestStack = new RequestStack();
        $requestStack->push($request);
        $sut = new Request($requestStack);

        $actual = $sut->input($key);

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function query(): void
    {
        $request = SymfonyRequest::create('/?name=John%20Doe');
        $requestStack = new RequestStack();
        $requestStack->push($request);
        $sut = new Request($requestStack);

        $actual = $sut->query('name');

        $this->assertEquals('John Doe', $actual);
    }

    /** @test */
    public function cookie(): void
    {
        $request = new SymfonyRequest();
        $request->cookies->set('name', 'John Doe');
        $requestStack = new RequestStack();
        $requestStack->push($request);
        $sut = new Request($requestStack);

        $actual = $sut->cookie('name');

        $this->assertEquals('John Doe', $actual);
    }

    /** @test */
    public function file(): void
    {
        $uploadedFile = new UploadedFile(__FILE__, 'test.php', 'text/plain', null, true);
        $request = new SymfonyRequest();
        $request->files->set('file', $uploadedFile);
        $requestStack = new RequestStack();
        $requestStack->push($request);
        $sut = new Request($requestStack);

        $actual = $sut->file('file');

        $this->assertSame($uploadedFile, $actual);
    }

    /** @test */
    public function has(): void
    {
        $request = new SymfonyRequest();
        $request->request->set('name', 'John Doe');
        $requestStack = new RequestStack();
        $requestStack->push($request);
        $sut = new Request($requestStack);

        $this->assertTrue($sut->has('name'));
        $this->assertFalse($sut->has('email'));
    }

    /** @test */
    public function has_input(): void
    {
        $request = new SymfonyRequest();
        $request->request->set('name', 'John Doe');
        $requestStack = new RequestStack();
        $requestStack->push($request);
        $sut = new Request($requestStack);

        $this->assertTrue($sut->hasInput('name'));
        $this->assertFalse($sut->hasInput('email'));
    }

    /** @test */
    public function has_query(): void
    {
        $request = new SymfonyRequest();
        $request->query->set('name', 'John Doe');
        $requestStack = new RequestStack();
        $requestStack->push($request);
        $sut = new Request($requestStack);

        $this->assertTrue($sut->hasQuery('name'));
        $this->assertFalse($sut->hasQuery('email'));
    }

    /** @test */
    public function has_cookie(): void
    {
        $request = new SymfonyRequest();
        $request->cookies->set('name', 'John Doe');
        $requestStack = new RequestStack();
        $requestStack->push($request);
        $sut = new Request($requestStack);

        $this->assertTrue($sut->hasCookie('name'));
        $this->assertFalse($sut->hasCookie('email'));
    }

    /** @test */
    public function has_file(): void
    {
        $uploadedFile = new UploadedFile(__FILE__, 'test.php', 'text/plain', null, true);
        $request = new SymfonyRequest();
        $request->files->set('file', $uploadedFile);
        $requestStack = new RequestStack();
        $requestStack->push($request);
        $sut = new Request($requestStack);

        $this->assertTrue($sut->hasFile('file'));
        $this->assertFalse($sut->hasFile('email'));
    }

    /** @test */
    public function has_header(): void
    {
        $request = new SymfonyRequest();
        $request->headers->set('Content-Type', 'application/json');
        $requestStack = new RequestStack();
        $requestStack->push($request);
        $sut = new Request($requestStack);

        $this->assertTrue($sut->hasHeader('Content-Type'));
        $this->assertFalse($sut->hasHeader('Accept'));
    }

    /** @test */
    public function json(): void
    {
        $request = SymfonyRequest::create('/', 'POST', [], [], [], [], '{"name":"John Doe"}');
        $request->headers->set('Content-Type', 'application/json');
        $requestStack = new RequestStack();
        $requestStack->push($request);
        $sut = new Request($requestStack);

        $this->assertSame('John Doe', $sut->json('name'));
        $this->assertNull($sut->json('email'));
    }
}
