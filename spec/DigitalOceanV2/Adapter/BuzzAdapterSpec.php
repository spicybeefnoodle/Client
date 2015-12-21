<?php

namespace spec\DigitalOceanV2\Adapter;

use Buzz\Browser;
use Buzz\Listener\ListenerInterface;
use Buzz\Message\Response;

class BuzzAdapterSpec extends \PhpSpec\ObjectBehavior
{
    function let(Browser $browser, ListenerInterface $listener)
    {
        $browser->addListener($listener)->shouldBeCalled();

        $this->beConstructedWith('my_access_token', $browser, $listener);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DigitalOceanV2\Adapter\BuzzAdapter');
    }

    function it_returns_json_content($browser, Response $response)
    {
        $browser->get('http://sbin.dk')->willReturn($response);

        $response->isSuccessful()->willReturn(true);
        $response->getContent()->willReturn('{"foo":"bar"}');

        $this->get('http://sbin.dk')->shouldBe('{"foo":"bar"}');
    }

    function it_throws_an_runtime_exception($browser, Response $response)
    {
        $browser->get('http://sbin.dk')->willReturn($response);

        $response->isSuccessful()->willReturn(false);
        $response->getStatusCode()->willReturn(404);
        $response->getContent()->willReturn('{"id":"error id", "message":"error message"}');

        $this->shouldThrow(new \RuntimeException('[404] error message (error id)'))->duringGet('http://sbin.dk');
    }

    function it_can_delete($browser, Response $response)
    {
        $browser->delete('http://sbin.dk/123', ['foo' => 'bar'])->willReturn($response);

        $response->isSuccessful()->willReturn(true);

        $this->delete('http://sbin.dk/123', ['foo' => 'bar']);
    }

    function it_throws_an_runtime_exception_if_cannot_delete($browser, Response $response)
    {
        $browser->delete('http://sbin.dk/123', [])->willReturn($response);

        $response->isSuccessful()->willReturn(false);
        $response->getStatusCode()->willReturn(500);
        $response->getContent()->willReturn('{"id":"error id", "message":"error message"}');

        $this->shouldThrow(new \RuntimeException('[500] error message (error id)'))->duringDelete('http://sbin.dk/123');
    }

    function it_can_put($browser, Response $response)
    {
        $browser->put('http://sbin.dk/456', ['foo' => 'bar'], '{"foo":"bar"}')->willReturn($response);

        $response->isSuccessful()->willReturn(true);
        $response->getContent()->willReturn('{"foo":"bar"}');

        $this->put('http://sbin.dk/456', ['foo' => 'bar'], '{"foo":"bar"}')->shouldBe('{"foo":"bar"}');
    }

    function it_throws_an_runtime_exception_if_cannot_update($browser, Response $response)
    {
        $browser->put('http://sbin.dk', [], '{"foo":"bar"}')->willReturn($response);

        $response->isSuccessful()->willReturn(false);
        $response->getStatusCode()->willReturn(500);
        $response->getContent()->willReturn('{"id":"error id", "message":"error message"}');

        $this->shouldThrow(new \RuntimeException('[500] error message (error id)'))->duringPut('http://sbin.dk', [], '{"foo":"bar"}');
    }

    function it_can_post($browser, Response $response)
    {
        $browser->post('http://sbin.dk', ['foo' => 'bar'], '{"foo":"bar"}')->willReturn($response);

        $response->isSuccessful()->willReturn(true);
        $response->getContent()->willReturn('{"foo":"bar"}');

        $this->post('http://sbin.dk', ['foo' => 'bar'], '{"foo":"bar"}')->shouldBe('{"foo":"bar"}');
    }

    function it_throws_an_runtime_exception_if_cannot_create($browser, Response $response)
    {
        $browser->post('http://sbin.dk', [], '{"foo":"bar"}')->willReturn($response);

        $response->isSuccessful()->willReturn(false);
        $response->getStatusCode()->willReturn(500);
        $response->getContent()->willReturn('{"id":"error id", "message":"error message"}');

        $this->shouldThrow(new \RuntimeException('[500] error message (error id)'))->duringPost('http://sbin.dk', [], '{"foo":"bar"}');
    }

    function it_returns_last_response_header($browser, Response $response)
    {
        $browser->getLastResponse()->willReturn($response);

        $response->getHeader('RateLimit-Limit')->willReturn(1200);
        $response->getHeader('RateLimit-Remaining')->willReturn(1100);
        $response->getHeader('RateLimit-Reset')->willReturn(1402425459);

        $this->getLatestResponseHeaders()->shouldBeArray();
        $this->getLatestResponseHeaders()->shouldHaveCount(3);
    }
}
