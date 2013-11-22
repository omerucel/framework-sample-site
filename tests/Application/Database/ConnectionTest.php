<?php

namespace Application\Database;

use Application\ServiceContainer;

class ConnectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Connection
     */
    protected static $connection;

    public static function setUpBeforeClass()
    {
        static::$connection = new Connection(ServiceContainer::getInstance());
    }

    public function setUp()
    {
        $sql = 'CREATE TABLE user(id int, city int, username string);';
        ServiceContainer::getInstance()->getPDO()->exec($sql);
    }

    public function tearDown()
    {
        $sql = 'DROP TABLE user;';
        ServiceContainer::getInstance()->getPDO()->exec($sql);
    }

    public function testExecute()
    {
        $sql = 'INSERT INTO user(id, username) VALUES(:id, :username)';
        $params = array(
            'id' => 10,
            'username' => 'test'
        );

        $stmt = static::$connection->execute($sql, $params);
        $this->assertNotNull($stmt);
        $this->assertEquals(1, $stmt->rowCount());
    }

    public function testExec()
    {
        $sql = 'INSERT INTO user(id, city, username) VALUES(1, 4, "test1");';
        ServiceContainer::getInstance()->getPDO()->exec($sql);

        $sql = 'UPDATE user SET username = :username WHERE id = :id';
        $params = array(
            'id' => 1,
            'username' => 'test2'
        );

        $affectedRows = static::$connection->exec($sql, $params);
        $this->assertEquals(1, $affectedRows);
    }

    public function testFetchColumn()
    {
        $sql = 'INSERT INTO user(id, city, username) VALUES(1, 4, "test1");';
        ServiceContainer::getInstance()->getPDO()->exec($sql);

        $sql = 'SELECT username FROM user WHERE city = :city';
        $params = array(
            'city' => 4
        );

        $username = static::$connection->fetchColumn($sql, $params);
        $this->assertEquals('test1', $username);
    }

    public function testFetchOneObject()
    {
        $sql = 'INSERT INTO user(id, city, username) VALUES(1, 3, "test1");';
        ServiceContainer::getInstance()->getPDO()->exec($sql);

        $sql = 'SELECT username FROM user WHERE id = :id';
        $params = array(
            'id' => 1
        );

        $object = static::$connection->fetchOneObject($sql, $params);
        $this->assertObjectHasAttribute('username', $object);
    }

    public function testFetchOne()
    {
        $sql = 'INSERT INTO user(id, city, username) VALUES(1, 3, "test1");';
        ServiceContainer::getInstance()->getPDO()->exec($sql);

        $sql = 'SELECT username FROM user WHERE id = :id';
        $params = array(
            'id' => 1
        );

        $row = static::$connection->fetchOne($sql, $params);
        $this->assertArrayHasKey('username', $row);
    }

    public function testFetchAllObjects()
    {
        $sql = 'INSERT INTO user(id, city, username) VALUES(1, 1, "test1");';
        $sql.= 'INSERT INTO user(id, city, username) VALUES(1, 1, "test1");';
        $sql.= 'INSERT INTO user(id, city, username) VALUES(1, 1, "test1");';
        $sql.= 'INSERT INTO user(id, city, username) VALUES(1, 2, "test1");';
        $sql.= 'INSERT INTO user(id, city, username) VALUES(1, 3, "test1");';
        ServiceContainer::getInstance()->getPDO()->exec($sql);

        $sql = 'SELECT * FROM user WHERE city = :city';
        $params = array(
            'city' => 1
        );

        $objects = static::$connection->fetchAllObjects($sql, $params);
        $this->assertCount(3, $objects);
        $this->assertObjectHasAttribute('id', $objects[0]);
    }

    public function testFetchAll()
    {
        $sql = 'INSERT INTO user(id, city, username) VALUES(1, 1, "test1");';
        $sql.= 'INSERT INTO user(id, city, username) VALUES(1, 1, "test1");';
        $sql.= 'INSERT INTO user(id, city, username) VALUES(1, 1, "test1");';
        $sql.= 'INSERT INTO user(id, city, username) VALUES(1, 2, "test1");';
        $sql.= 'INSERT INTO user(id, city, username) VALUES(1, 3, "test1");';
        ServiceContainer::getInstance()->getPDO()->exec($sql);

        $sql = 'SELECT * FROM user WHERE city = :city';
        $params = array(
            'city' => 1
        );

        $objects = static::$connection->fetchAll($sql, $params);
        $this->assertCount(3, $objects);
        $this->assertArrayHasKey('id', $objects[0]);
    }

    public function testInsertWrapper()
    {
        $sql = 'SELECT COUNT(*) AS count FROM user';
        $count = ServiceContainer::getInstance()->getPDO()->query($sql)->fetchColumn();
        $this->assertEquals(0, $count);

        $params = array(
            'id' => 1,
            'username' => 'test1',
            'city' => 10
        );
        static::$connection->insertWrapper('user', $params);

        $sql = 'SELECT COUNT(*) AS count FROM user';
        $count = ServiceContainer::getInstance()->getPDO()->query($sql)->fetchColumn();
        $this->assertEquals(1, $count);
    }

    public function testCreatePreparedInsertSql()
    {
        $sql = "INSERT INTO `user`(`id`, `username`, `city`) VALUES (:id, :username, :city)";

        $params = array(
            'id' => 1,
            'username' => 'test',
            'city' => 10
        );

        $insertSQL = static::$connection->createPreparedInsertSql('user', $params);
        $this->assertEquals($sql, $insertSQL);
    }

    public function testFixParams()
    {
        $params = array(
            'id' => 1,
            'username' => 'test',
            'city' => 10
        );

        $check = array(
            ':id' => 1,
            ':username' => 'test',
            ':city' => 10
        );

        $result = static::$connection->fixParams($params);
        $this->assertEquals($check, $result);
    }
}
