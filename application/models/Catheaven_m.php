<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Catheaven_m extends CI_Model {

   function __construct() {
      parent::__construct();
      $this->load->database();
   }

   function log_events($signature, $body) {
      $this->db->set('signature', $signature)
         ->set('events', $body)
         ->insert('eventlog');

      return $this->db->insert_id();
   }

   function getUser($userId)
   {
      $data = $this->db->where('user_id', $userId)
         ->get('users')->row_array();
      if(count($data) > 0) return $data;
      return false;
   }

   function saveUser($profile)
   {
      $this->db->set('user_id', $profile['userId'])
         ->set('display_name', $profile['displayName'])
         ->insert('users');

      return $this->db->insert_id();
   }

   function getQuestion($questionNum)
   {
      $data = $this->db->where('number', $questionNum)
         ->get('questions')
         ->row_array();
      if(count($data)>0) return $data;

      return false;
   }

   function getTips()
   {
      $data = $this->db->query('select * from tips')->result_array();
      if(count($data)>0) return $data;

      return false;
   }

   function isAnswerEqual($number, $answer)
   {
      $this->db->where('number', $number)
         ->where('answer', $answer);
         if(count($this->db->get('questions')->row()) > 0) return true;

      return false;
   }

   function setUserProgress($user_id, $newNumber)
   {
      $this->db->set('number', $newNumber)
         ->where('user_id', $user_id)
         ->update('users');

      return $this->db->affected_rows();
   }

   function setScore($user_id, $score)
   {
      $this->db->set('score', $score)
         ->where('user_id', $user_id)
         ->update('users');

      return $this->db->affected_rows();
   }

   function setQuizNumber($numbers)
   {
      for ($i=0; $i < count( $numbers ); $i++) {
         $this->db->set('number', $numbers[$i]['number'])
            ->where('id', $numbers[$i]['id'])
            ->update('quiznumber');
      }

      return $this->db->affected_rows();
   }

   function getQuizNumber($id)
   {
      $data = $this->db->where('id', $id)
         ->get('quiznumber')
         ->row_array();
      if(count($data)>0) return $data;

      return false;
   }
}
