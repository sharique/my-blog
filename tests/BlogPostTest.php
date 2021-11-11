<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogPostTest extends WebTestCase
{
  public function testGuestUser(): void
  {
    $client = static::createClient();
    $crawler = $client->request('GET', '/');

    $this->assertResponseIsSuccessful();
    $this->assertSelectorTextContains('h1', 'My Blog');

    // New blog redirect user to login.
    $crawler = $client->request('GET', '/blog/new');
    $this->assertResponseStatusCodeSame(302);
    $client->followRedirects(true);
    $this->assertResponseRedirects('/login');
  }

  public function testBlogPost(): void
  {

  }
}
