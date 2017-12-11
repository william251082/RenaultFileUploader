<?php

namespace AdminTest\Controller;

use Admin\Model\AdminTable;
use Zend\ServiceManager\ServiceManager;
use Admin\Controller\AdminController;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Admin\Model\Admin;
use Prophecy\Argument;

class AdminControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;
    protected $adminTable;

    public function setUp()
	{
    // The module configuration should still be applicable for tests.
    // You can override configuration here with test case specific values,
    // such as sample view templates, path stacks, module_listener_options,
    // etc.
    $configOverrides = [];

    $this->setApplicationConfig(ArrayUtils::merge(
        include __DIR__ . '/../../../../config/application.config.php',
        $configOverrides
    ));

    parent::setUp();

    $this->configureServiceManager($this->getApplicationServiceLocator());
	}
    public function testIndexActionCanBeAccessed()
	{
		$this->adminTable->fetchAll()->willReturn([]);

    	$this->dispatch('/admin');
    	$this->assertResponseStatusCode(200);
    	$this->assertModuleName('Admin');
    	$this->assertControllerName(AdminController::class);
    	$this->assertControllerClass('AdminController');
    	$this->assertMatchedRouteName('admin');
	}
	public function testAddActionRedirectsAfterValidPost()
	{
    $this->adminTable
        ->saveAdmin(Argument::type(Admin::class))
        ->shouldBeCalled();

    $postData = [
        'title'  => 'Led Zeppelin III',
        'artist' => 'Led Zeppelin',
        'id'     => '',
    ];
    $this->dispatch('/admin/add', 'POST', $postData);
    $this->assertResponseStatusCode(302);
    $this->assertRedirectTo('/admin');
	}
	public function testEditActionRedirectsAfterValidPost()
	{
    $this->adminTable
    	->getAdmin($id)
    	->willReturn(new Admin());

    $postData = [
        'title'  => 'Led Zeppelin III',
        'artist' => 'Led Zeppelin',
        'id'     => '',
    ];
    $this->dispatch('/admin/add', 'POST', $postData);
    $this->assertResponseStatusCode(302);
    $this->assertRedirectTo('/admin');
	}
	public function testDeleteActionRedirectsAfterValidPost()
	{
    $this->adminTable
        ->saveAdmin(Argument::type(Admin::class))
        ->shouldBeCalled();

    $postData = [
        'title'  => 'Led Zeppelin III',
        'artist' => 'Led Zeppelin',
        'id'     => '',
    ];
    $this->dispatch('/admin/delete', 'POST', $postData);
    $this->assertResponseStatusCode(302);
    $this->assertRedirectTo('/admin');
	}
	protected function configureServiceManager(ServiceManager $services)
	{
    $services->setAllowOverride(true);

    $services->setService('config', $this->updateConfig($services->get('config')));
    $services->setService(AdminTable::class, $this->mockAdminTable()->reveal());

    $services->setAllowOverride(false);
	}

	protected function updateConfig($config)
	{
    $config['db'] = [];
    return $config;
	}

	protected function mockAdminTable()
	{
    $this->adminTable = $this->prophesize(AdminTable::class);
    return $this->adminTable;
	}
}