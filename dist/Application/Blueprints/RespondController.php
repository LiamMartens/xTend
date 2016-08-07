<?php
    namespace Application\Blueprints;
    use Application\Core\Request;
    use Application\Core\StatusCodeHandler;

    class RespondController extends Controller {
        /**
        * Respond function to echo json with status and data
        *
        * @param boolean $success Success status of the call
        * @param integer|boolean $status The StatusCode of the call
        * @param array|boolean $data Extra data
        *
        * @return array
        */
        protected function respond($success, $status = false, $data = false) {
            Request::contentType('application/json');
            $reply=[ 'success' => $success ];
            $reply['multiple']=false;
            $reply['status']=[];
            if($status!==false) {
                if(is_array($status)) {
                    $reply['multiple']=true;
                    $reply['status']['codes']=[];
                    $reply['status']['hex']=[];
                    $reply['status']['names']=[];
                    $reply['status']['messages']=[];
                    foreach($status as $stat) {
                        $object = StatusCodeHandler::find($stat);
                        $reply['status']['codes'][] = $object->code();
                        $reply['status']['hex'][] = $object->hex();
                        $reply['status']['names'][] = $object->name();
                        $reply['status']['messages'][] = $object->readable();
                    }
                } else {
                    $object = StatusCodeHandler::find($status);
                    $reply['status']['code'] = $object->code();
                    $reply['status']['hex'] = $object->hex();
                    $reply['status']['name'] = $object->name();
                    $reply['status']['message'] = $object->readable();
                }
            }
            if($data!==false) {
                $reply['data'] = $data;
            }
            return $reply;
        }
    }