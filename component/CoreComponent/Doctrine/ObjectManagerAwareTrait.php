<?php
/**
 * Object manager aware trait.
 * Provides persistency-related methods like;
 * 
 *  - getObjectManager() - EM or DM instance
 *  - getDoctrineHydrator() - Doctrine object hydrator
 *
 * @since     Wed 2014
 * @author    M. Yilmaz SUSLU <yilmazsuslu@gmail.com>
 */

namespace CoreComponent\Doctrine;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

trait ObjectManagerAwareTrait
{
    protected $objectManager;

    /**
     * Set object manager.
     * 
     * @param  EntityManager $em
     * @return void
     */
    public function setObjectManager(EntityManager $em)
    {
        $this->objectManager = $em;
    }

    /**
     * Returns object manager.
     *
     * @throws \Exception
     * @return EntityManager
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }

    /**
     * Returns doctrine object hydrator.
     *
     * @param  bool $byValue
     * @return DoctrineObject
     * @throws \Exception
     */
    protected function getDoctrineHydrator($byValue = true)
    {
        return new DoctrineObject($this->getObjectManager(), $byValue);
    }

    /**
     * Start transaction for transaction demarcation.
     *
     * @access protected
     * @see http://doctrine-orm.readthedocs.org/en/latest/reference/transactions-and-concurrency.html
     * @return void
     * @throws \Exception
     */
    protected function beginTransaction()
    {
        $this->getObjectManager()->getConnection()->beginTransaction();
    }

    /**
     * Roll back.
     *
     * @return void
     * @throws \Doctrine\DBAL\ConnectionException
     */
    protected function rollback()
    {
        $this->getObjectManager()->getConnection()->rollback();
    }

    /**
     * Commit
     *
     * @return void
     * @throws \Doctrine\DBAL\ConnectionException
     */
    protected function commit()
    {
        $this->getObjectManager()->getConnection()->commit();
    }
}
