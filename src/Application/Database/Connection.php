<?php

namespace Application\Database;

use Application\ServiceContainer;

class Connection
{
    /**
     * @var ServiceContainer
     */
    protected $serviceContainer;

    /**
     * @param ServiceContainer $serviceContainer
     */
    public function __construct(ServiceContainer $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * @return \Application\ServiceContainer
     */
    public function getServiceContainer()
    {
        return $this->serviceContainer;
    }

    /**
     * @param $sql
     * @param array $params
     * @return \PDOStatement
     */
    public function execute($sql, array $params = array())
    {
        $fixedParams = $this->fixParams($params);

        $stmt = $this->getServiceContainer()->getPDO()->prepare($sql);
        $stmt->execute($fixedParams);
        return $stmt;
    }

    /**
     * @param $sql
     * @param array $params
     * @return mixed
     */
    public function fetchColumn($sql, array $params = array())
    {
        $stmt = $this->execute($sql, $params);
        $column = $stmt->fetchColumn();
        $stmt->closeCursor();

        return $column;
    }

    /**
     * @param $sql
     * @param array $params
     * @param string $className
     * @return mixed
     */
    public function fetchOneObject($sql, array $params = array(), $className = '\stdClass')
    {
        $stmt = $this->execute($sql, $params);
        $object = $stmt->fetchObject($className);
        $stmt->closeCursor();

        return $object;
    }

    /**
     * @param $sql
     * @param array $params
     * @return array
     */
    public function fetchOne($sql, array $params = array())
    {
        $stmt = $this->execute($sql, $params);
        $row = $stmt->fetch();
        $stmt->closeCursor();

        return $row;
    }

    /**
     * @param $sql
     * @param array $params
     * @param string $className
     * @return array
     */
    public function fetchAllObjects($sql, array $params = array(), $className = '\stdClass')
    {
        $stmt = $this->execute($sql, $params);
        $objects = $stmt->fetchAll(\PDO::FETCH_CLASS, $className);
        $stmt->closeCursor();

        return $objects;
    }

    /**
     * @param $sql
     * @param array $params
     * @return array
     */
    public function fetchAll($sql, array $params = array())
    {
        $stmt = $this->execute($sql, $params);
        $rows = $stmt->fetchAll();
        $stmt->closeCursor();

        return $rows;
    }

    /**
     * For UPDATE, DELETE, INSERT queries.
     *
     * <code>
     * $sql = 'UPDATE user SET a = :a, b = :b WHERE id = :id';
     *
     * $params = array(
     *     'id' => 1,
     *     'a' => 2,
     *     'b' => 3
     * );
     *
     * $affectedRows = $connection->exec('user', $params);
     * </code>
     *
     * @param $sql
     * @param array $params
     * @return int
     */
    public function exec($sql, array $params = array())
    {
        $stmt = $this->execute($sql, $params);
        $affectedRows = $stmt->rowCount();
        $stmt->closeCursor();

        return $affectedRows;
    }

    /**
     * <code>
     * $params = array(
     *     'a' => 1,
     *     'b' => 'test'
     * );
     * $lastInsertId = $connection->insertWrapper('user', $params);
     * </code>
     *
     * @param $tableName
     * @param $params
     * @return int
     */
    public function insertWrapper($tableName, $params)
    {
        $sql = $this->createPreparedInsertSql($tableName, $params);
        $stmt = $this->execute($sql, $params);
        $stmt->closeCursor();

        return $this->getServiceContainer()->getPDO()->lastInsertId();
    }

    /**
     * <code>
     * $params = array(
     *     'a' => 1,
     *     'b' => 'test'
     * );
     * $sql = $connection->createPreparedInsertSql('user', $params);
     * echo $sql; // INSERT INTO user(`a`, `b`) VALUES (:a, :b);
     * </code>
     *
     * @param $tableName
     * @param $params
     * @return string
     */
    public function createPreparedInsertSql($tableName, $params)
    {
        $columns = array();
        $values = array();

        foreach ($params as $column => $value) {
            $columns[] = '`' . $column . '`';
            $values[] = ':' . $column;
        }

        $columns = implode($columns, ', ');
        $values = implode($values, ', ');
        $sql = 'INSERT INTO `' . $tableName . '`(' . $columns . ') VALUES (' . $values . ')';

        return $sql;
    }

    /**
     * <code>
     * $params = array(
     *     'a' => 1,
     *     'b' => 2
     * );
     *
     * $fixedParams = $connection->fixParams($params);
     * // array(
     * //     ':a' => 1,
     * //     ':b' => 2,
     * // )
     * </code>
     *
     * @param array $params
     * @return array
     */
    public function fixParams(array $params)
    {
        $values = array();

        foreach ($params as $column => $value) {
            $values[':' . $column] = $value;
        }

        return $values;
    }
}
