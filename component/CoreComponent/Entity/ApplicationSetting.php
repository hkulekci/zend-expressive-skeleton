<?php
/**
 * Application Setting entity.
 *
 * @since     July 2017
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace CoreComponent\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="uygulama_ayarlari")
 */
class ApplicationSetting extends Base
{
    /**
     * @var integer $id
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(name="anahtar", type="string", length=100, unique=true)
     * @var string $key
     */
    protected $key;

    /**
     * @ORM\Column(name="deger", type="string", length=100, nullable=true)
     * @var string $value
     */
    protected $value;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    public function toArray()
    {
        return [
            'id'    => $this->getId(),
            'key'   => $this->getKey(),
            'value' => $this->getValue(),
        ];
    }
}
