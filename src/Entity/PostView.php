<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="post_view")
 */
class PostView
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
    private $user;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post" , inversedBy="postViews", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(nullable=true)
     */
    private $post;


    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $viewedAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $userIp;


    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
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
     * @return datetime
     */
    public function getViewedAt(): ?datetime
    {
        return $this->viewedAt;
    }

    public function setViewedAt(datetime $viewedAt)
    {
        $this->viewedAt = $viewedAt;
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


    public function getUserIp()
    {
        return $this->userIp;
    }

    public function setUserIp($userIp)
    {
        $this->userIp = $userIp;
    }


}