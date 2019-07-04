<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="post_tag")
 */
class PostTag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post" ,inversedBy="tags", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(nullable=true)
     */
    private $post;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tag" ,inversedBy="posts", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(nullable=true)
     */
    private $tag;



    public function getId(): ?int
    {
        return $this->id;
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
    public function setPost($post): self
    {
        $this->post = $post;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    /**
     * @param mixed $tag
     */
    public function setTag($tag): self
    {
        $this->tag = $tag;

        return $this;
    }


}