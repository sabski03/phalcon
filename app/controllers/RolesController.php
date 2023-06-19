<?php
declare(strict_types=1);

namespace PSA\Controllers;

use Phalcon\Http\Response;
use Phalcon\Tag;
use PSA\Forms\RolesForm;
use PSA\Helpers\Datatables;
use PSA\Models\Roles;
use PSA\Models\Permissions;
use PSA\Models\Employee;

class RolesController extends ControllerBase
{


    public function initialize()
    {
        $this->view->setTemplateBefore('private');
        $this->tag->setTitle('Roles');
    }

    //see all the roles and all the statuses
    public function indexAction()
    {
        $datatable = new Datatables;
        $this->view->css = $datatable->css();
        $js = $datatable->jsData();
        $js .= "<script type='text/javascript' language='javascript'>
        function deleteRole(id) {
            $.post('/roles/delete/' + id, function(data){
                $('#modal-delete').html(data);
            })
        }
        </script>";
        $this->view->js = $js;
        $this->view->breadcrumbs = "
        <li class='breadcrumb-item'><a href='/dashboard'><i class='fas fa-fw fa-tachometer-alt'></i> Dashboard</a></li>
        <li class='breadcrumb-item active'><i class='fas fa-layer-group'></i> Roles</li>
        ";
        $roles = Roles::find();
        $this->view->roles = $roles;
    }

    //create a role
    public function createAction()
    {
        $form = new RolesForm(null);
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost()) == false) {
                foreach ($form->getMessages() as $message) {
                    $this->flashSession->error((string)$message);
                }
                return $this->response->redirect('/roles/create');
            } else {
                $role = new Roles([
                    'name' => $this->request->getPost('name', 'striptags'),
                    'active' => $this->request->getPost('active')
                ]);
                if ($role->save()) {
                    $this->flashSession->success("Role was created successfully");
                }
            }
            $this->acl->rebuild();
            return $this->response->redirect('/roles');
        }
        $this->view->breadcrumbs = "
        <li class='breadcrumb-item'><a href='/dashboard'><i class='fas fa-fw fa-tachometer-alt'></i> Dashboard</a></li>
        <li class='breadcrumb-item'><a href='/roles'><i class='fas fa-layer-group'></i> Roles</a></li>
        <li class='breadcrumb-item active'><i class='fas fa-plus-circle'></i> Create</li>
        ";
        $this->view->form = $form;
    }

    /**
     * @param int $id
     */
    //edit a role
    public function editAction($id)
    {
        $role = Roles::findFirstById($id);
        if (!$role) {
            $this->flash->error("Role was not found");
            return $this->dispatcher->forward([
                'action' => 'index'
            ]);
        }
        $form = new RolesForm($role);
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost()) == false) {
                foreach ($form->getMessages() as $message) {
                    $this->flashSession->error((string)$message);
                }
            } else {
                $role->assign([
                    'name' => $this->request->getPost('name', 'striptags'),
                    'active' => $this->request->getPost('active')
                ]);
                if ($role->save()) {
                    $this->acl->rebuild();
                    $this->flashSession->success("Role was updated successfully");
                    return $this->response->redirect('/roles');
                }
            }
            return $this->response->redirect('/roles/edit/' . $id);
        }
        $this->view->breadcrumbs = "
        <li class='breadcrumb-item'><a href='/dashboard'><i class='fas fa-fw fa-tachometer-alt'></i> Dashboard</a></li>
        <li class='breadcrumb-item'><a href='/roles'><i class='fas fa-layer-group'></i> Roles</a></li>
        <li class='breadcrumb-item active'><i class='fas fa-edit'></i> Edit</li>
        ";
        $this->view->form = $form;
        $this->view->role = $role;
    }

    //edit a permission
    public function editPermissionAction($id)
    {
        $role = Roles::findFirstById($id);
        if (!$role) {
            $this->flash->error("Role was not found");
            return $this->dispatcher->forward([
                'action' => 'index'
            ]);
        }

        if ($this->request->isPost()) {
            $role->getPermissions()->delete();
            foreach ($this->request->getPost('permissions') as $permission) {
                $parts = explode('.', $permission);
                $permission = new Permissions();
                $permission->roleID = $role->id;
                $permission->resource = $parts[0];
                $permission->action = $parts[1];
                $permission->save();
            }
            $this->acl->rebuild();
            $this->flashSession->success('Permissions were updated with success');
            return $this->response->redirect('/roles');
        }
        $this->view->breadcrumbs = "
        <li class='breadcrumb-item'><a href='/dashboard'><i class='fas fa-fw fa-tachometer-alt'></i> Dashboard</a></li>
        <li class='breadcrumb-item'><a href='/roles'><i class='fas fa-layer-group'></i> Roles</a></li>
        <li class='breadcrumb-item active'><i class='fas fa-edit'></i> Edit</li>
        ";
        $this->acl->rebuild();
        $this->view->permissions = $this->acl->getPermissions($role);
        $this->view->role = $role;
    }

    //add a new employee and his role "working on that"
    public function employeeAction(){

        $role = Roles::find();

    }

    /**
     * @param int $id
     */
    //delete a role
    public function deleteAction($id)
    {
        if ($this->request->getPost('delete')) {
            $role = Roles::findFirstById($id);
            if (!$role) {
                $this->flashSession->error("Role was not found");
                return $this->response->redirect('/roles');
            }
            if (!$this->security->checkToken($this->security->getTokenKey(), $this->request->getPost('csrf'))) {
                $this->flashSession->error('CSRF validation failed');
                return $this->response->redirect('/roles');
            }

            if (!$role->delete()) {
                if ($role->getMessages()) {
                    foreach ($role->getMessages() as $message) {
                        $this->flashSession->error((string)$message);
                    }
                } else {
                    $this->flashSession->error("An error has occurred");
                }
            } else {
                $this->acl->rebuild();
                $this->flashSession->success("Role was deleted");
            }
            return $this->response->redirect('/roles');
        }

        $this->view->disable();
        $resData = "Oops! Something went wrong. Please try again later.";
        $response = new Response();
        $response->setStatusCode(400, "Bad Request");

        if ($this->request->isPost() && $this->request->isAjax()) {
            $form = new RolesForm();
            $resData = '<form method="post" action="/roles/delete/' . $id . '">';
            $resData .= '<div class="modal-body">';
            $resData .= '<label>Are you sure you want to delete the role?!</label>';
            $resData .= '</div>';
            $resData .= '<div class="modal-footer">';
            $resData .= Tag::submitButton(['name' => 'delete', 'class' => 'btn btn btn-danger btn-sm', 'value' => 'Delete']);
            $resData .= $form->render('id');
            $resData .= $form->render('csrf', ['value' => $form->getCsrf()]);
            $resData .= '</div>';
            $resData .= '</form>';
            $response->setStatusCode(200);
        }
        $response->setJsonContent($resData);
        $response->send();
        exit;
    }


}
