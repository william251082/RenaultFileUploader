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
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('admin', ['action' => 'add']);
        }

        // Retrieve the admin with the specified id. Doing so raises
        // an exception if the admin is not found, which should result
        // in redirecting to the landing page.
        try {
            $admin = $this->table->getAdmin($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('admin', ['action' => 'index']);
        }

        $form = new AdminForm();
        $form->bind($admin);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (! $request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($admin->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        $this->table->saveAdmin($admin);

        // Redirect to admin list
        return $this->redirect()->toRoute('admin', ['action' => 'index']);
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->table->deleteAdmin($id);
            }

            // Redirect to list of admins
            return $this->redirect()->toRoute('admin');
        }

        return [
            'id'    => $id,
            'admin' => $this->table->getAdmin($id),
        ];
    }
}