<?php
/**
 * Application Setting entity.
 *
 * @since     July 2017
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace CoreComponent\Entity;

/**
 * Base entity class to share common functionalities
 * across application-wide derived entities.
 */
class Base implements \JsonSerializable
{
    /**
     * Returns unique id of the entity.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Array representation of entity.
     *
     * @return void|array
     * @throws \RuntimeException
     */
    public function toArray()
    {
        throw new \RuntimeException('All derived entities should implement their own toArray() methods');
    }

    public function getArrayCopy()
    {
        return $this->toArray();
    }

    /**
     * Implement native JsonSerializeable interface.
     *
     * @see http://php.net/manual/en/class.jsonserializable.php
     *
     * @throws \Exception
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
    
    public function dateStringify($date, $format = 'Y-m-d H:i:s')
    {
        if ($date instanceof \DateTime && $date->format('U') >= -62169962400) {
            return $date->format($format);
        }

        return null;
    }
}
