<?php
declare(strict_types=1);

namespace PSA\Controllers;

use Phalcon\Http\Response;
use Phalcon\Tag;
use PSA\Forms\TransferredForm;
use PSA\Helpers\Datatables;
use PSA\Models\Comments;
use PSA\Models\Transferred;
use PSA\Models\Users;

class TransferredController extends ControllerBase
{
    public function initialize(){
        $this->view->setTemplateBefore('private');
        $this->tag->setTitle("Transferred Users");
    }

    //all transfered users
    public function indexAction()
    {
        $transfer = Transferred::find();
        $users = Users::find();
        $forms = new TransferredForm();



        $this->view->transfer = $transfer;
        $this->view->user = $users;
        $this->view->forms = $forms;


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

    }


    //transfers a user
    public function usersAction($id)
    {
        $transfer = Transferred::find();
        $comment = Comments::find();
        $forms = new TransferredForm();

        if (!$transfer && !$comment) {
            $this->flash->error("User was not transferred");
            return $this->dispatcher->forward([
                'action' => 'index'
            ]);
        }

        if (!$forms->isValid($this->request->getPost()) == false) {
            foreach ($forms->getMessage() as $message) {
                $this->flashSession->error((string)$message);
            }
        } else {
            $this->db->begin();

            $operator = $this->auth->getUser()->name;
            $string = "No Internet Connection!";
            $transferString = "Transferred";
            $transferComment = "Transferred Because Of Internet Issues";

            $comment = new Comments([
                'comment_type' => $transferString,
                'comment' => $transferComment,
                'name' => $operator,
                'user_id' => $id,
            ]);

            $transfer = new Transferred([
                'user_id' => $id,
                'reason' => $string,
                'operator' => $operator,
            ]);

            if ($transfer->create() && $comment->create()) {
                $this->db->commit();
                $this->flashSession->success("A New User Has Been Transferred");
                return $this->response->redirect('/allUsers/viewUsers/' . $id);
            } else {
                $this->db->rollback();
                foreach ($transfer->getMessages() as $message) {
                    $this->flashSession->error((string)$message);
                }
                return $this->response->redirect('/allUsers/viewUsers/' . $id);
            }
        }

        $this->view->transfer = $transfer;
        $this->view->form = $forms;
    }
}