<?php

namespace AdminTest\Model;

use Admin\Model\AdminTable;
use Admin\Model\Admin;
use PHPUnit_Framework_TestCase as TestCase;
use RuntimeException;
use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\TableGateway\TableGatewayInterface;

class AdminTableTest extends TestCase
{
    protected function setUp()
    {
        $this->tableGateway = $this->prophesize(TableGatewayInterface::class);
        $this->adminTable = new AdminTable($this->tableGateway->reveal());
    }

    public function testFetchAllReturnsAllAdmins()
    {
        $resultSet = $this->prophesize(ResultSetInterface::class)->reveal();
        $this->tableGateway->select()->willReturn($resultSet);

        $this->assertSame($resultSet, $this->adminTable->fetchAll());
    }
    public function testCanDeleteAnAdminByItsId()
{
    $this->tableGateway->delete(['id' => 123])->shouldBeCalled();
    $this->adminTable->deleteAdmin(123);
}

public function testSaveAdminWillInsertNewAdminsIfTheyDontAlreadyHaveAnId()
{
    $adminData = [
        'artist' => 'The Military Wives',
        'title'  => 'In My Dreams'
    ];
    $admin = new Admin();
    $admin->exchangeArray($adminData);

    $this->tableGateway->insert($adminData)->shouldBeCalled();
    $this->adminTable->saveAdmin($admin);
}

public function testSaveAdminWillUpdateExistingAdminsIfTheyAlreadyHaveAnId()
{
    $adminData = [
        'id'     => 123,
        'artist' => 'The Military Wives',
        'title'  => 'In My Dreams',
    ];
    $admin = new Admin();
    $admin->exchangeArray($adminData);

    $resultSet = $this->prophesize(ResultSetInterface::class);
    $resultSet->current()->willReturn($admin);

    $this->tableGateway
        ->select(['id' => 123])
        ->willReturn($resultSet->reveal());
    $this->tableGateway
        ->update(
            array_filter($adminData, function ($key) {
                return in_array($key, ['artist', 'title']);
            }, ARRAY_FILTER_USE_KEY),
            ['id' => 123]
        )->shouldBeCalled();

    $this->adminTable->saveAdmin($admin);
}

public function testExceptionIsThrownWhenGettingNonExistentAdmin()
{
    $resultSet = $this->prophesize(ResultSetInterface::class);
    $resultSet->current()->willReturn(null);

    $this->tableGateway
        ->select(['id' => 123])
        ->willReturn($resultSet->reveal());

    $this->setExpectedException(
        RuntimeException::class,
        'Could not find row with identifier 123'
    );
    $this->adminTable->getAdmin(123);
}
}