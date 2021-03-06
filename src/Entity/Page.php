<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PageRepository")
 */
class Page
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"page"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="alias", type="string", length=155)
     * @Groups({"page"})
     */
    private $alias;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="text", nullable=true)
     * @Groups({"page"})
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="thumbnail", type="text", nullable=true)
     * @Groups({"page"})
     */
    private $thumbnail;

    /**
     * @var string
     *
     * @ORM\Column(name="abstract", type="text", nullable=true)
     * @Groups({"page"})
     */
    private $abstract;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     * @Groups({"page"})
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="text", nullable=true)
     * @Groups({"page"})
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_keywords", type="text", nullable=true)
     * @Groups({"page"})
     */
    private $metaKeywords;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_title", type="text", nullable=true)
     * @Groups({"page"})
     */
    private $metaTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_description", type="text", nullable=true)
     * @Groups({"page"})
     */
    private $metaDescription;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Groups({"page"})
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     * @Groups({"page"})
     */
    private $updatedAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="isOnline", type="boolean", options={"default":0})
     * @Groups({"page"})
     */
    private $isOnline;

    /**
     * @var string
     *
     * @ORM\Column(name="extraCSS", type="text", nullable=true)
     * @Groups({"page"})
     */
    private $extraCSS;

    /**
     * @var string
     *
     * @ORM\Column(name="extraJS", type="text", nullable=true)
     * @Groups({"page"})
     */
    private $extraJS;

    /**
     * @ORM\ManyToOne(targetEntity="Template", inversedBy="pages")
     * @ORM\JoinColumn(name="template_id", referencedColumnName="id")
     * @Groups({"page"})
     */
    private $template;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Page", inversedBy="parents")
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    private $children;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Page", mappedBy="children")
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    private $parents;

    /**
     * Page constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->children = new ArrayCollection();
        $this->parents = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Page
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Page
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Page
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Page
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * @param string $metaDescription
     * @return $this
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;
        return $this;
    }

    /**
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * @param string $metaKeywords
     * @return $this
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Set isOnline
     *
     * @param boolean $isOnline
     *
     * @return Page
     */
    public function setIsOnline($isOnline)
    {
        $this->isOnline = $isOnline;

        return $this;
    }

    /**
     * Get isOnline
     *
     * @return boolean
     */
    public function getIsOnline()
    {
        return $this->isOnline;
    }

    /**
     * @return string
     */
    public function getExtraCSS()
    {
        return $this->extraCSS;
    }

    /**
     * @param string $extraCSS
     * @return Page
     */
    public function setExtraCSS($extraCSS)
    {
        $this->extraCSS = $extraCSS;
        return $this;
    }

    /**
     * @return string
     */
    public function getExtraJS()
    {
        return $this->extraJS;
    }

    /**
     * @param string $extraJS
     * @return Page
     */
    public function setExtraJS($extraJS)
    {
        $this->extraJS = $extraJS;
        return $this;
    }

    /**
     * @return string
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    /**
     * @param string $metaTitle
     * @return Page
     */
    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;
        return $this;
    }

    /**
     * @return string
     */
    public function getAbstract()
    {
        return $this->abstract;
    }

    /**
     * @param string $abstract
     * @return Page
     */
    public function setAbstract($abstract)
    {
        $this->abstract = $abstract;
        return $this;
    }

    /**
     * @return string
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * @param string $thumbnail
     * @return Page
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
        return $this;
    }

    /**
     * @return Template
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param mixed $template
     * @return Page
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->alias;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     * @return Page
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
        }

        return $this;
    }

    public function removeChild(self $child): self
    {
        if ($this->children->contains($child)) {
            $this->children->removeElement($child);
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getParents(): Collection
    {
        return $this->parents;
    }

    public function addParent(self $parent): self
    {
        if (!$this->parents->contains($parent)) {
            $this->parents[] = $parent;
            $parent->addChild($this);
        }

        return $this;
    }

    public function removeParent(self $parent): self
    {
        if ($this->parents->contains($parent)) {
            $this->parents->removeElement($parent);
            $parent->removeChild($this);
        }

        return $this;
    }
}
