<?php
declare(strict_types=1);

namespace PSA\Controllers;


use Phalcon\Http\Response;
use Phalcon\Tag;
use PSA\Forms\AllUsersCreateForm;
use PSA\Forms\AllUsersForm;
use PSA\Forms\CommentsForm;
use PSA\Helpers\Datatables;
use PSA\Models\Users;
use PSA\Models\Comments;
use PSA\Models\Tasks;
use PSA\Models\Departments;

class AllUsersController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateBefore('private');
        $this->tag->setTitle('All Users');

    }

    //view all users
    public function indexAction()
    {
        $users = Users::find();
        $this->view->users = $users;

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
    }

    //edit a user
    public function editAction($id){
        $users = Users::findFirstById($id);
        $this->tag->setTitle('Edit Users');


        if (!$users) {
            $this->flash->error("User was not found.");
            return $this->dispatcher->forward([
                'action' => 'index'
            ]);
        }
        $forms = new AllUsersForm($users);
        if ($this->request->isPost()) {
            if ($forms->isValid($this->request->getPost()) == false) {
                foreach ($forms->getMessages() as $message) {
                    $this->flashSession->error((string)$message);
                }
            } else {

                $this->db->begin();



                // save user info
                $users->name = $this->request->getPost('name', 'striptags');
                $users->email = $this->request->getPost('email', 'email');
                $users->phone = $this->request->getPost('phone', 'phone');
                $users->address = $this->request->getPost('address', 'address');
                if ($users->save()) {
                    $this->db->commit();
                    $this->flashSession->success("User was updated successfully.");
                    return $this->response->redirect('/allUsers');
                } else {
                    $this->db->rollback();
                    foreach ($users->getMessages() as $message) {
                        $this->flashSession->error((string)$message);
                    }
                    return $this->response->redirect('/allUsers');
                }


            }
            return $this->response->redirect('/allUsers');
        }


        $this->view->users = $users;
        $this->view->form = $forms;

    }

    //create a new user
    public function newUserAction(){

        $forms = new AllUsersCreateForm();

        if ($this->request->isPost()) {
            if ($forms->isValid($this->request->getPost()) == false) {
                foreach ($forms->getMessages() as $message) {
                    $this->flashSession->error((string)$message);
                }
            } else {

                $this->db->begin();

                $users = new Users([
                    'name' => $this->request->getPost('name', 'striptags'),
                    'email' => $this->request->getPost('email'),
                    'phone' => $this->request->getPost('phone'),
                    'address' => $this->request->getPost('address'),
                    'password' => $this->security->hash($this->request->getPost('password')),
                ]);

                if ($users->create()) {
                    $this->db->commit();
                    $this->flashSession->success("User was created successfully.");
                    return $this->response->redirect('/allUsers');
                } else {
                    $this->db->rollback();
                    foreach ($users->getMessages() as $message) {
                        $this->flashSession->error((string)$message);
                    }
                    return $this->response->redirect('/allUsers');
                }
            }
        }
        $this->view->form = $forms;
    }

    //delete a user
    public function deleteAction($id)
    {

            $users = Users::findFirstById($id);


            if (!$users->delete()) {
                    foreach ($users->getMessages() as $message) {
                        $this->flashSession->error((string)$message);
                    }

            } else {
                $this->flashSession->success("User was deleted successfully.");
                return $this->response->redirect('/allUsers');
            }

    }


    //view the user
    public function viewUsersAction($id){
        $this->tag->setTitle("User - $id");


        $users = Users::findFirstById($id);
        $comments = Comments::find([
            "conditions" => "user_id = :id:",
            "bind" => ["id" => $id],
            "order" => "created_at DESC"
        ]);
        $tasks = Tasks::find();
        $forms = new CommentsForm();



        if (!$users || !$comments || !$tasks) {
            $this->flash->error("User was not found.");
            return $this->dispatcher->forward([
                'action' => 'index'
            ]);
        }

        //count all active and closed tasks for the specific user
        $activeTasksCount = 0;
        $unActiveTasksCount = 0;
        foreach ($tasks as $task) {
            if ($task->userID == $id && $task->active == 1) {
                $activeTasksCount++;
            }
            if ($task->userID == $id && $task->active == 0) {
                $unActiveTasksCount++;
            }
        }

        // change the color if there is an active task
        if($activeTasksCount > 0){
            $this->view->activeTasks1 = "<span class='badge bg-warning'>" . $activeTasksCount . "/" . $unActiveTasksCount . "</span>";
        }else{
            $this->view->unActiveTasks1 = "<span class='badge bg-secondary'>" . $activeTasksCount . "/" . $unActiveTasksCount . "</span>";
        }

        if ($this->request->isPost()) {
            if ($forms->isValid($this->request->getPost()) == false) {
                foreach ($forms->getMessages() as $message) {
                    $this->flashSession->error((string)$message);
                }
            }else{
                $this->db->begin();

                $usersName = $this->auth->getUser()->name;

                $comments = new Comments([
                    'comment_type' => $this->request->getPost('comment_type'),
                    'comment' => $this->request->getPost('comment'),
                    'name' => $usersName,
                    'user_id' => $id
                ]);

                if($comments->create()){
                    $this->db->commit();
                    $this->flashSession->success("A New Comment Has Been Added");
                    return $this->response->redirect('/allUsers/viewUsers/' . $id);
                }else{
                    $this->db->rollback();
                    foreach($comments->getMessages() as $message){
                        $this->flashSession->error((string)$message);
                    }
                    return $this->response->redirect('/allUsers/viewUsers/' . $id);
                }
            }
        }



        $this->view->users = $users;
        $this->view->comments = $comments;
        $this->view->unActiveTasks = $unActiveTasksCount;
        $this->view->activeTasks = $activeTasksCount;
        $this->view->id = $id;
        $this->view->forms = $forms;

    }

    //this function bellow is used to check which department what tasks it has using ajax
    public function getTasksAction(){
        $department = $this->request->getQuery('department');
        $option_value = $this->request->getQuery('option_value');

        if ($department && $option_value) {
            $tasks = Task::find([
                'conditions' => [
                    'department = :department: AND option_value = :option_value:',
                    'bind' => [
                        'department' => $department,
                        'option_value' => $option_value
                    ]
                ]
            ]);
        } else {
            $tasks = [];
        }

        $this->view->tasks = $tasks;
        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);
        $this->view->disableLevel(\Phalcon\Mvc\View::LEVEL_LAYOUT);
        return $this->view->render('tasks', 'get-tasks');
    }

    //create a task
    public function createTasksAction($id){

        $department = Departments::find();
        $tasks = Tasks::find();
        $comments = Comments::find();

        if (!$tasks || !$department || !$comments) {
            $this->flash->error("User or department or comment was not found.");
            return $this->dispatcher->forward([
                'action' => 'index'
            ]);
        }
        if($this->request->isPost()){
            $this->db->begin();

            $departmentId = $this->request->getPost('department');
            $departmentTasks = $this->request->getPost('option_value');
            $createdComment = $this->request->getPost('comment');

            $tasks = new Tasks([
                'departments_tasks' => $departmentTasks,
                'departmentID' => $departmentId,
                'userID' => $id,
                'task_creator' => $this->auth->getUser()->name,
                'created_comment' => $createdComment,
            ]);



            if($departmentId == 1 && $departmentTasks == 'no internet connection'){
                $comments = new Comments([
                    'comment_type' => CallBack,
                    'comment' => 'no internet connection',
                    'name' => $this->auth->getUser()->name,
                    'user_id' => $id,
                ]);
            }elseif($departmentId == 1 && $departmentTasks == 'internet issues'){
                $comments = new Comments([
                    'comment_type' => CallBack,
                    'comment' => 'internet speed issues',
                    'name' => $this->auth->getUser()->name,
                    'user_id' => $id,
                ]);
            }else{
                echo "<h1> something went wrong </h1>";
                exit;
            }

            if($tasks->create() && $comments->create()){
                $this->db->commit();
                $this->flashSession->success("A New Task Has Been Created");
                return $this->response->redirect('/allUsers/viewUsers/' . $id);
            }else{
                $this->db->rollback();
                foreach($tasks->getMessages() as $message){
                    $this->flashSession->error((string)$message);
                }
                return $this->response->redirect('/allUsers/viewUsers/' . $id);
            }

        }


        $this->view->department = $department;
        $this->view->tasks = $tasks;

    }

    //view the tasks
    public function tasksAction($id){
        $this->tag->setTitle("All Tasks");

        $tasks = Tasks::find([
            "conditions" => "userID = :id:",
            "bind" => ["id" => (int)$id],
            "order" => "created_at DESC"
        ]);


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

        $this->view->task = $tasks;
    }


}