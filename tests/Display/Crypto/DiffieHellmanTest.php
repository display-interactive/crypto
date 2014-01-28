<?php

use Display\Crypto\DiffieHellman;

class DiffieHellmanTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var int
     */
    protected $base;

    /**
     * @var DiffieHellman
     */
    protected $server;

    /**
     * @var DiffieHellman
     */
    protected $client;

    public function setUp()
    {
        $this->base = 16;
        $this->server = new DiffieHellman();
        $this->client = new DiffieHellman($this->server->getPrime($this->base), $this->server->getGenerator($this->base), $this->base);
    }

    public function testGetPrime()
    {
        $this->assertEquals($this->server->getPrime(), $this->client->getPrime());
        $this->assertEquals($this->server->getPrime($this->base), $this->client->getPrime($this->base));
    }

    public function testGetGenerator()
    {
        $this->assertEquals($this->server->getGenerator(), $this->client->getGenerator());
        $this->assertEquals($this->server->getGenerator($this->base), $this->client->getGenerator($this->base));
    }

    public function testGetPrivateKey()
    {
        $dh = new DiffieHellman(23, 5, 10, 6);
        $this->assertEquals(6, $dh->getPrivateKey());

        $dh = new DiffieHellman(23, 5, 10, 15);
        $this->assertEquals(15, $dh->getPrivateKey());
    }

    public function testGetPublicKey()
    {
        $dh = new DiffieHellman(23, 5, 10, 6);
        $this->assertEquals(8, $dh->getPublicKey());

        $dh = new DiffieHellman(23, 5, 10, 15);
        $this->assertEquals(19, $dh->getPublicKey());
    }

    public function testGetSecret()
    {
        $serverSecret = $this->server->getSecret($this->client->getPublicKey());
        $clientSecret = $this->client->getSecret($this->server->getPublicKey());

        $this->assertEquals($serverSecret, $clientSecret);
    }

    public function testSerialize() {
        $dh = new DiffieHellman(23, 5, 10, 6);
        $str = serialize($dh);

        /** @var DiffieHellman $dh2 */
        $dh2 = unserialize($str);

        $this->assertEquals($dh2->getPrime(16), '17');
        $this->assertEquals($dh2->getGenerator(16), '5');
        $this->assertEquals($dh2->getPrivateKey(16), '6');
        $this->assertEquals($dh2->getPublicKey(16), '8');
        //
        $this->assertEquals($dh2->getSecret(19, 16), '2');

    }
}