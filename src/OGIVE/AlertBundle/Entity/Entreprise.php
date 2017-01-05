<?php

namespace OGIVE\AlertBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Entreprise
 *
 * @ORM\Table(name="entreprise")
 * @ORM\Entity(repositoryClass="OGIVE\AlertBundle\Repository\EntrepriseRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Entreprise
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string", length=255)
     */
    private $phoneNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255)
     */
    private $logo;
    
    /**
     * @Assert\File(maxSize="6000000") 
    */
    private $file;
    
    private $temp;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;
    
    /**
     * @var int
     *
     * @ORM\Column(name="state", type="integer")
     */
    private $state;
    
    /**
     * @var \Domain
     *
     * @ORM\ManyToOne(targetEntity="Domain")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="domain", referencedColumnName="id")
     * })
     */
    private $domain;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="OGIVE\AlertBundle\Entity\HistoricalAlertEntreprise", mappedBy="subscriber", cascade={"remove", "persist"})
     */
    private $historicalAlertEntreprises;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="OGIVE\AlertBundle\Entity\Subscriber", mappedBy="subscriber", cascade={"remove", "persist"})
     */
    private $subscribers;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="datetime")
     */
    private $createDate;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_update_date", type="datetime")
     */
    private $lastUpdateDate;
    
    
    /**
     * @var \Adress 
     * @ORM\OneToOne(targetEntity="Adress",cascade={"persist"})
     * @ORM\JoinColumn(name="adress", referencedColumnName="id")
     */
    private $adress;

    /** 
     * Constructor
     */
    public function __construct() {
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
     * Set name
     *
     * @param string $name
     *
     * @return Entreprise
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return Entreprise
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set logo
     *
     * @param string $logo
     *
     * @return Entreprise
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Entreprise
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Entreprise
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    function getState() {
        return $this->state;
    }
    
    function setState($state) {
        $this->state = $state;
    }


    /**
     * @param UploadedFile $file
     * @return object
     */
    public function setFile(UploadedFile $file = null) {
        $this->file = $file;
        // check if we have an old image logo
        if (isset($this->logo)) {
            // store the old name to delete after the update
            $this->temp = $this->logo;
            $this->logo = null;
        } else {
            $this->logo = 'initial';
        }
    }
    /**
     * Get the file used for profile picture uploads
     * 
     * @return UploadedFile
     */
    public function getFile() {
        return $this->file;
    }
    protected function getUploadRootDir() {
        return __DIR__ . '/../../../../web/uploads/Materiels';
    }
    /**
     * @ORM\PrePersist() 
     * @ORM\PreUpdate() 
     */
    public function preUpload() {
        if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->logo = $filename . '.' . $this->getFile()->guessExtension();
        }
    }
    /**
     * Generates a 32 char long random filename
     * 
     * @return string
     */
    public function generateRandomProfilePictureFilename() {
        $count = 0;
        do {
            $generator = new SecureRandom();
            $random = $generator->nextBytes(16);
            $randomString = bin2hex($random);
            $count++;
        } while (file_exists($this->getUploadRootDir() . '/' . $randomString . '.' . $this->getFile()->guessExtension()) && $count < 50);
        return $randomString;
    }
    /**
     * @ORM\PostPersist() 
     * @ORM\PostUpdate() 
     */
    public function upload() {
        if (null === $this->getFile()) {
            return;
        }
        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->logo);
        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            //unlink($this->getUploadRootDir().'/'.$this->temp);
            //ou je renomme
            rename($this->getUploadRootDir() . '/' . $this->temp, $this->getUploadRootDir() . '/old' . $this->temp);
            // clear the temp image logo
            $this->temp = null;
        }
        $this->file = null;
    }

    /**
     * Set domain
     *
     * @param OGIVE\AlertBundle\Entity\Domain $domain
     *
     * @return Entreprise
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get domain
     *
     * @return OGIVE\AlertBundle\Entity\Domain
     */
    public function getDomain()
    {
        return $this->domain;
    }
    
    /**
     * Add historicalAlertEntreprise
     *
     * @param OGIVE\AlertBundle\Entity\HistoricalAlertEntreprise $historicalAlertEntreprise
     * @return Entrprise
     */
    public function addHistoricalAlertEntreprise(OGIVE\AlertBundle\Entity\HistoricalAlertEntreprise $historicalAlertEntreprise) {
        $this->historicalAlertEntreprises[] = $historicalAlertEntreprise;
        return $this;
    }

    /**
     * Get historicalAlertEntreprises
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHistoricalAlertEntreprises() {
        return $this->historicalAlertEntreprises;
    }

    /**
     * Set historicalAlertEntreprises
     *
     * @param \Doctrine\Common\Collections\Collection $historicalAlertEntreprises
     * @return Entreprise
     */
    public function setHistoricalAlertEntreprises(\Doctrine\Common\Collections\Collection $historicalAlertEntreprises = null) {
        $this->historicalAlertEntreprises = $historicalAlertEntreprises;

        return $this;
    }

    /**
     * Remove historicalAlertEntreprises
     *
     * @param OGIVE\AlertBundle\Entity\HistoricalAlertEntreprise $historicalAlertEntreprise
     * @return Entreprise
     */
    public function removeHistoricalAlertEntreprise(OGIVE\AlertBundle\Entity\HistoricalAlertEntreprise $historicalAlertEntreprise) {
        $this->historicalAlertEntreprises->removeElement($historicalAlertEntreprise);
        return $this;
    }
    
    
    /**
     * Add subscriber
     *
     * @param OGIVE\AlertBundle\Entity\Subscriber $subscriber 
     * @return Entreprise
     */
    public function addSubscriber(OGIVE\AlertBundle\Entity\Subscriber $subscriber) {
        $this->subscribers[] = $subscriber;
        return $this;
    }

    /**
     * Get subscribers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubscribers() {
        return $this->subscribers;
    }

    /**
     * Set subscribers
     *
     * @param \Doctrine\Common\Collections\Collection $subscribers
     * @return Entreprise
     */
    public function setSubscribers(\Doctrine\Common\Collections\Collection $subscribers = null) {
        $this->subscribers = $subscribers;

        return $this;
    }

    /**
     * Remove subscriber
     *
     * @param OGIVE\AlertBundle\Entity\Subscriber $subscriber
     * @return Entreprise
     */
    public function removeSubscriber(OGIVE\AlertBundle\Entity\Subscriber $subscriber) {
        $this->subscribers->removeElement($subscriber);
        return $this;
    }
    
    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return Entreprise
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }
    
    /**
     * Set lastUpdateDate
     *
     * @param \DateTime $lastUpdateDate
     *
     * @return Entreprise
     */
    public function setLastUpdateDate($lastUpdateDate)
    {
        $this->lastUpdateDate = $lastUpdateDate;

        return $this;
    }

    /**
     * Get lastUpdateDate
     *
     * @return \DateTime
     */
    public function getLastUpdateDate()
    {
        return $this->lastUpdateDate;
    }
    
    /**
     * Set adress
     *
     * @param OGIVE\AlertBundle\Entity\Adress $adress
     * @return Entreprise
     */
    public function setAdress(\OGIVE\AlertBundle\Entity\Adress $adress = null)
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * Get adress
     *
     * @return OGIVE\AlertBundle\Entity\Adress 
     */
    public function getAdress()
    {
        return $this->adress;
    }
    
    /**
     * @ORM\PreUpdate() 
     */
    public function preUpdate()
    {
        $this->lastUpdateDate = new \DateTime();
    }

    /**
     * @ORM\PrePersist() 
     */
    public function prePersist()
    {
        $this->createDate = new \DateTime();
        $this->lastUpdateDate = new \DateTime();
        $this->state = 0;
        $this->status = 1;
    }
}

