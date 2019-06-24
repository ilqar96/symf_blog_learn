<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="post_like")
 */
class PostLike
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;



    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User"  , fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(nullable=true)
     */
    private $author;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post" , fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(nullable=true)
     */
    private $post;


    /**
     * @ORM\Column(type="boolean")
     */
    private $liked = true;


    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getPost(): ?Post
    {
        return $this->post;
    }

    /**
     * @param mixed $post
     */
    public function setPost($post)
    {
        $this->post = $post;
    }


    /**
     * @return boolean
     */
    public function getLiked(): ?bool
    {
        return $this->liked;
    }

    public function setLiked(bool $liked)
    {
        $this->liked = $liked;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }


}