<?php
    namespace xTend\Blueprints;
    /**
    * The BaseRespondController is the most 'complicated'
    * controller to extend from
    */
    class BaseRespondController extends BaseDataController
    {
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
            $this->_app->getRequestHandler()->getRequest()->setContentType('json');
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
                        $object = $this->_app->getStatusCodeHandler()->findStatus($stat);
                        $reply['status']['codes'][] = $object->getCode();
                        $reply['status']['hex'][] = $object->getHexCode();
                        $reply['status']['names'][] = $object->getName();
                        $reply['status']['messages'][] = $object->getReadableName();
                    }
                } else {
                    $object = $this->_app->getStatusCodeHandler()->findStatus($status);
                    $reply['status']['code'] = $object->getCode();
                    $reply['status']['hex'] = $object->getHexCode();
                    $reply['status']['name'] = $object->getName();
                    $reply['status']['message'] = $object->getReadableName();
                }
            }
            if($data!==false) {
                $reply['data'] = $data;
            }
            return $reply;
        }
    }
