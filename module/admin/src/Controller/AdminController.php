<?php

namespace Admin\Controller;

// Add the following import:
use Admin\Model\AdminTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class AdminController extends AbstractActionController
{
    // Add this property:
    private $table;

    // Add this constructor:
    public function __construct(AdminTable $table)
    {
        $this->table = $table;
    }
    public function indexAction()
    {
        return new ViewModel([
            'admins' => $this->table->fetchAll(),
        ]);
    }

    public function addAction()
    {
    }

    public function editAction()
    {
    }

    public function deleteAction()
    {
    }
}