<?php

namespace Admin\Controller;

// Add the following import:
use Admin\Form\AdminForm;
use Admin\Model\Admin;
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
        $form = new AdminForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $admin = new Admin();
        $form->setInputFilter($admin->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $admin->exchangeArray($form->getData());
        $this->table->saveAdmin($admin);
        return $this->redirect()->toRoute('admin');
    }

    public function editAction()
    {
    }

    public function deleteAction()
    {
    }
}