<?php

namespace OGIVE\AlertBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * AlertProcedure
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
class AlertProcedure {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255)
     */
    protected $reference;
    
    /**
     * @var string
     *
     * @ORM\Column(name="url_details", type="string", length=255, nullable=true)
     */
    protected $urlDetails;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="publication_date", type="datetime")
     */
    protected $publicationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deadline", type="datetime", nullable=true)
     */
    protected $deadline;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="opening_date", type="datetime", nullable=true)
     */
    protected $openingDate;

    /**
     * @var string
     *
     * @ORM\Column(name="object", type="text")
     */
    protected $object;

    /**
     * @var string
     *
     * @ORM\Column(name="owner", type="string", length=255, nullable=true)
     */
    protected $owner;

    /**
     * @var string
     *
     * @ORM\Column(name="abstract", type="text")
     */
    protected $abstract;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    protected $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="state", type="integer")
     */
    protected $state;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sending_date", type="datetime")
     */
    protected $sendingDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="datetime")
     */
    protected $createDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_update_date", type="datetime")
     */
    protected $lastUpdateDate;

    /**
     * @var \Domain
     *
     * @ORM\ManyToOne(targetEntity="Domain")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="domain", referencedColumnName="id")
     * })
     */
    protected $domain;

    /**
     * @var \SubDomain
     *
     * @ORM\ManyToOne(targetEntity="SubDomain")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subDomain", referencedColumnName="id")
     * })
     */
    protected $subDomain;
    
    /**
     * @var \OGIVE\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\OGIVE\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id")
     * })
     */
    protected $user;
    
    /**
     * @var \OGIVE\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\OGIVE\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="updated_user", referencedColumnName="id")
     * })
     */
    protected $updatedUser;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    protected $piecesjointes;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    protected $originalpiecesjointes;

    /**
     * @var ArrayCollection
     */
    public $uploadedFiles;

    public function __construct() {
        $this->state = 0;
        $this->piecesjointes = array();
        $this->originalpiecesjointes = array();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return AlertProcedure
     */
    public function setType($type) {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return AlertProcedure
     */
    public function setReference($reference) {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference() {
        return $this->reference;
    }
    
    /**
     * Set url_Details
     *
     * @param string $urlDetails
     *
     * @return AlertProcedure
     */
    public function setUrlDetails($urlDetails) {
        $this->urlDetails = $urlDetails;

        return $this;
    }

    /**
     * Get urlDetails
     *
     * @return string
     */
    public function getUrlDetails() {
        return $this->urlDetails;
    }

    /**
     * Set publicationDate
     *
     * @param \DateTime $publicationDate
     *
     * @return AlertProcedure
     */
    public function setPublicationDate($publicationDate) {
        $this->publicationDate = new \DateTime(date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $publicationDate))));

        return $this;
    }

    /**
     * Get publicationDate
     *
     * @return \DateTime
     */
    public function getPublicationDate() {
        return $this->publicationDate ? $this->publicationDate->format('d-m-Y'): $this->publicationDate;
        
    }

    /**
     * Set deadline
     *
     * @param \DateTime $deadline
     *
     * @return AlertProcedure
     */
    public function setDeadline($deadline) {
        $this->deadline = new \DateTime(date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $deadline))));

        return $this;
    }

    /**
     * Get deadline
     *
     * @return \DateTime
     */
    public function getDeadline() {
        return $this->deadline ? $this->deadline->format('d-m-Y H:i'):$this->deadline;
       
    }

    /**
     * Set object
     *
     * @param string $object
     *
     * @return AlertProcedure
     */
    public function setObject($object) {
        $this->object = $object;

        return $this;
    }

    /**
     * Get object
     *
     * @return string
     */
    public function getObject() {
        return $this->object;
    }

    /**
     * Set owner
     *
     * @param string $owner
     *
     * @return AlertProcedure
     */
    public function setOwner($owner) {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return string
     */
    public function getOwner() {
        return $this->owner;
    }

    /**
     * Set abstract
     *
     * @param string $abstract
     *
     * @return AlertProcedure
     */
    public function setAbstract($abstract) {
        $this->abstract = $abstract;

        return $this;
    }

    /**
     * Get abstract
     *
     * @return string
     */
    public function getAbstract() {
        return $this->abstract;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return AlertProcedure
     */
    public function setStatus($status) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set state
     *
     * @param integer $state
     *
     * @return AlertProcedure
     */
    public function setState($state) {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return int
     */
    public function getState() {
        return $this->state;
    }

    /**
     * Set sendingDate
     *
     * @param \DateTime $sendingDate
     *
     * @return AlertProcedure
     */
    public function setSendingDate($sendingDate) {
        $this->sendingDate = $sendingDate;

        return $this;
    }

    /**
     * Get sendingDate
     *
     * @return \DateTime
     */
    public function getSendingDate() {
        return $this->sendingDate;
    }

    /**
     * Set domain
     *
     * @param \OGIVE\AlertBundle\Entity\Domain $domain
     *
     * @return AlertProcedure
     */
    public function setDomain(\OGIVE\AlertBundle\Entity\Domain $domain = null) {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get status
     *
     * @return \OGIVE\AlertBundle\Entity\Domain
     */
    public function getDomain() {
        return $this->domain;
    }
    
    /**
     * Set user
     *
     * @param \OGIVE\UserBundle\Entity\User $user
     *
     * @return AlertProcedure
     */
    public function setUser(\OGIVE\UserBundle\Entity\User $user = null) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \OGIVE\UserBundle\Entity\User
     */
    public function getUser() {
        return $this->user;
    }
    
    /**
     * Set updatedUser
     *
     * @param \OGIVE\UserBundle\Entity\User $user
     *
     * @return AlertProcedure
     */
    public function setUpdatedUser(\OGIVE\UserBundle\Entity\User $user = null) {
        $this->updatedUser = $user;

        return $this;
    }

    /**
     * Get updatedUser
     *
     * @return \OGIVE\UserBundle\Entity\User
     */
    public function getUpdatedUser() {
        return $this->updatedUser;
    }

    /**
     * Set domain
     *
     * @param \OGIVE\AlertBundle\Entity\SubDomain $subDomain
     *
     * @return AlertProcedure
     */
    public function setSubDomain(\OGIVE\AlertBundle\Entity\SubDomain $subDomain = null) {
        $this->subDomain = $subDomain;

        return $this;
    }

    /**
     * Get domain
     *
     * @return \OGIVE\AlertBundle\Entity\SubDomain
     */
    public function getSubDomain() {
        return $this->subDomain;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return AlertProcedure
     */
    public function setCreateDate($createDate) {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime
     */
    public function getCreateDate() {
        return $this->createDate;
    }

    /**
     * Set lastUpdateDate
     *
     * @param \DateTime $lastUpdateDate
     *
     * @return AlertProcedure
     */
    public function setLastUpdateDate($lastUpdateDate) {
        $this->lastUpdateDate = $lastUpdateDate;

        return $this;
    }

    /**
     * Get lastUpdateDate
     *
     * @return \DateTime
     */
    public function getLastUpdateDate() {
        return $this->lastUpdateDate;
    }

    /**
     * Set openingDate
     *
     * @param \DateTime $openingDate
     *
     * @return AlertProcedure
     */
    public function setOpeningDate($openingDate) {
        $this->openingDate = new \DateTime(date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $openingDate))));

        return $this;
    }

    /**
     * Get openingDate
     *
     * @return \DateTime
     */
    public function getOpeningDate() {
        return $this->openingDate ? $this->openingDate->format('d-m-Y H:i'): $this->openingDate;
        
    }

    /**
     * Set piecesjointes
     *
     * @param string $piecesjointes
     * @return Ressource
     */
    public function setPiecesjointes($piecesjointes) {
        $this->piecesjointes = $piecesjointes;

        return $this;
    }

    /**
     * Get piecesjointes
     *
     * @return array 
     */
    public function getPiecesjointes() {
        return $this->piecesjointes;
    }

    /**
     * Get piecesjointes
     *
     * @return array 
     */
    public function getOriginalpiecesjointes() {
        return $this->originalpiecesjointes;
    }

    public function getUploadRootDir() {
        return __DIR__ . '/../../../../web/uploads/procedures';
    }

    /**
     * Sets uploadedFiles.
     *
     * @param array $uploadedFiles
     */
    public function setUploadedFiles(array $uploadedFiles = null) {
        $this->uploadedFiles = $uploadedFiles;
    }

    /**
     * Get uploadedFiles.
     *
     * @return array
     */
    public function getUploadedFiles() {
        return $this->uploadedFiles;
    }

    /**
     * @ORM\PreFlush()
     */
    public function uploadPiecesjointes() {
        if ($this->uploadedFiles) {
            $this->piecesjointes = array();
            $this->originalpiecesjointes = array();
            foreach ($this->uploadedFiles as $file) {
                $info = pathinfo($file->getClientOriginalName());
                $file_name = basename($file->getClientOriginalName(), '.' . $info['extension']);
                array_push($this->originalpiecesjointes, $file_name);
                $path = sha1(uniqid(mt_rand(), true)) . '.' . $file->guessExtension();
                array_push($this->piecesjointes, $path);
                if (!is_dir($this->getUploadRootDir())) {
                    mkdir($this->getUploadRootDir());
                }
                $file->move($this->getUploadRootDir(), $path);
                unset($file);
            }
        }

        $this->uploadedFiles = array();
    }

    /**
     * @ORM\PreUpdate() 
     */
    public function preUpdate() {
        $this->lastUpdateDate = new \DateTime('now');
        $this->sendingDate = new \DateTime('now');
    }

    /**
     * @ORM\PrePersist() 
     */
    public function prePersist() {
        $this->createDate = new \DateTime('now');
        $this->lastUpdateDate = new \DateTime('now');
        $this->sendingDate = new \DateTime('now');
        $this->status = 1;
    }
    
    //public abstract function getAbstractForSmsNotification();

}
