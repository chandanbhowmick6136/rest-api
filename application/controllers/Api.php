<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Api extends RestController {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
    }

    public function participants_get()
    {
        // participants from a data store e.g. database
        $id = $this->get( 'id' );
        if ( $id === null )
        {
            $participants = $this->db->get("participants")->result();
            // Check if the participants data store contains participants
            if ( $participants )
            {
                // Set the response and exit
                $this->response( $participants, 200 );
            }
            else
            {
                // Set the response and exit
                $this->response( [
                    'status' => false,
                    'message' => 'No participants were found'
                ], 404 );
            }
        }
        else
        {
           $participant = $this->db->get_where("participants", ['id' => $id])->row_array();
            if ($participant['id'] == $id )
            {
                $this->response( $participant, 200 );
            }
            else
            {
                $this->response( [
                    'status' => false,
                    'message' => 'No such participant found'
                ], 404 );
            }
        }
    }

    function participant_post()
    {
        // create a new partipant and respond with a status/errors
        $data = $this->input->post(NULL, TRUE);
        $valid = $this->valid($data);
        if($valid){
            $this->response( [
                'status' => false,
                'message' => "Kindly Check Your data!!"
            ], 404 );
        }else{
            $this->db->insert('participants',$data);
            $this->response([
                'message' => 'Participant added successfully!!'
            ], 200);
        }
    }
 
    function participant_put()
    {
        // update an existing participant and respond with a status/errors
        $data = $this->put();
        $valid = $this->valid($data);
        if($valid){
            $this->response( [
                'status' => false,
                'message' => "Kindly Check Your data!!"
            ], 404 );
        }else{
            $id = $this->put('id');
            $participant = $this->db->get_where("participants", ['id' => $id])->row_array();
            if ($participant['id'] == $id )
            {
                $this->db->update('participants', $data, array('id'=>$id));
                $this->response([
                    'message' => 'Participant Updated successfully!!'
                ], 200);
            }
            else
            {
                $this->response( [
                    'status' => false,
                    'message' => 'No such participant found'
                ], 404 );
            }
        }
    }

    function valid($input){
        $result = false;
        if(ucfirst($input['profession']) == 'Employed' || ucfirst($input['profession']) == 'Student'){

        }else{
            $result = true;
        }
        if($input['nog'] > 0 && $input['nog'] < 3){

        }else{
            $result = true;
        }
        if(strlen($input['address']) > 0 && strlen($input['address']) < 51){

        }else{
            $result = true;
        }
        return $result;
    }
 
}