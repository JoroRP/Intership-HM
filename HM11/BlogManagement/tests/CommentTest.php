<?php

namespace App\Tests\Entity;

use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Post;
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
    public function testContent()
    {
        $comment = new Comment();
        $content = "This is a test comment";
        $comment->setContent($content);

        $this->assertSame($content, $comment->getContent());
    }

    public function testRating()
    {
        $comment = new Comment();
        $rating = "5";
        $comment->setRating($rating);

        $this->assertSame($rating, $comment->getRating());
    }

    public function testUser()
    {
        $comment = new Comment();
        $user = new User();
        $user->setUsername("testuser");

        $comment->setUser($user);

        $this->assertSame($user, $comment->getUser());
    }

    public function testPost()
    {
        $comment = new Comment();
        $post = new Post();
        $post->setName("Test Post");

        $comment->setPost($post);

        $this->assertSame($post, $comment->getPost());
    }

    public function testId()
    {
        $comment = new Comment();
        $this->assertNull($comment->getId());
    }
}
