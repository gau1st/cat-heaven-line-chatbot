<?php defined('BASEPATH') OR exit('No direct script access allowed');

// SDK for create bot
use \LINE\LINEBot;
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;

// SDK for build message
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use \LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use \LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;


// SDK for build button and template action
use \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;


class Webhook extends CI_Controller {

   private $events;
   private $signature;
   private $bot;
   private $user;
   private $quizNumber;


   function __construct() {
      parent::__construct();
      // create bot object
      $httpClient = new CurlHTTPClient($_ENV['CHANNEL_ACCESS_TOKEN']);
      $this->bot  = new LINEBot($httpClient, ['channelSecret' => $_ENV['CHANNEL_SECRET']]);
      $this->load->model('catheaven_m');
   }

   function quizNumRandomGen($min, $max, $quantity) {
      $numbers = range($min, $max);
      shuffle($numbers);
      return array_slice($numbers, 0, $quantity);
   }


   public function index(){
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         $tips = $this->catheaven_m->getTips();
         print_r($tips);
         echo "Hello Coders!";
         header('HTTP/1.1 400 Only POST method allowed');

         exit;
      }

      // get request
      $body = file_get_contents('php://input');
      $this->signature = isset($_SERVER['HTTP_X_LINE_SIGNATURE']) ? $_SERVER['HTTP_X_LINE_SIGNATURE'] : "-";
      $this->events = json_decode($body, true);
      $this->catheaven_m->log_events($this->signature, $body);

      foreach ($this->events['events'] as $event)
      {
         // skip group and room event
         if(! isset($event['source']['userId'])) continue;

         // get user data from database
         $this->user = $this->catheaven_m->getUser($event['source']['userId']);

         // get quiz number from database
         $this->quizNumber = $this->catheaven_m->getQuizNumber($this->user['number']);

         // respond event
         if($event['type'] == 'message'){
            if(method_exists($this, $event['message']['type'].'Message')){
               $this->{$event['message']['type'].'Message'}($event);
            }
         } else {
            if(method_exists($this, $event['type'].'Callback')){
               $this->{$event['type'].'Callback'}($event);
            }
         }
      }
   }

   private function followCallback($event){
      $res = $this->bot->getProfile($event['source']['userId']);

      if (!$this->catheaven_m->getUser($event['source']['userId'])) {
         $profile = $res->getJSONDecodedBody();
         // save user data
         $this->catheaven_m->saveUser($profile);
      }

      if ($res->isSucceeded()){
         $profile = $res->getJSONDecodedBody();
      }

      // send welcome message
      $message = "Halo, " . $profile['displayName'] . "!\n\n";
      $message .= "Pasti kamu pecinta kucing banget ya?\n\nMau Tips memelihara kucing dari kami?\n\nTetapi sebelumnya kamu harus lolos Kuis dengan skor minimal 60 dulu ya.\n\nsilakan ketik \"MEONG\" untuk memulai kuis.";
      $message2 = "Oiya, selain itu kamu juga bisa mendapatkan gambar kucing secara ekslusif dari kami.\n\nsilakan ketik \"MEONG MEONG\" untuk mendapatkan gambarnya.\n\n(Powered By placekitten.com)";

      $stickerMessageBuilder = new StickerMessageBuilder(2, 34);
      $this->bot->pushMessage($event['source']['userId'], $stickerMessageBuilder);
      $textMessageBuilder = new TextMessageBuilder($message);
      $this->bot->pushMessage($event['source']['userId'], $textMessageBuilder);
      $textMessageBuilder2 = new TextMessageBuilder($message2);
      $this->bot->pushMessage($event['source']['userId'], $textMessageBuilder2);
   }

   private function textMessage($event) {
      $userMessage = $event['message']['text'];
      if($this->user['number'] == 0) {
         if(strtolower($userMessage) == 'meong'){

            // reset score
            $this->catheaven_m->setScore($this->user['user_id'], 0);

            // update number progress
            $this->catheaven_m->setUserProgress($this->user['user_id'], 1);

            // send question no.1
            $quizNumRand = array();
            $quizNumRand = $this->quizNumRandomGen(1, 20, 10);
            $numbers = array(
               array(
                  'id' => 1,
                  'number' => $quizNumRand[0]
               ),
               array(
                  'id' => 2,
                  'number' => $quizNumRand[1]
               ),
               array(
                  'id' => 3,
                  'number' => $quizNumRand[2]
               ),
               array(
                  'id' => 4,
                  'number' => $quizNumRand[3]
               ),
               array(
                  'id' => 5,
                  'number' => $quizNumRand[4]
               ),
               array(
                  'id' => 6,
                  'number' => $quizNumRand[5]
               ),
               array(
                  'id' => 7,
                  'number' => $quizNumRand[6]
               ),
               array(
                  'id' => 8,
                  'number' => $quizNumRand[7]
               ),
               array(
                  'id' => 9,
                  'number' => $quizNumRand[8]
               ),
               array(
                  'id' => 10,
                  'number' => $quizNumRand[9]
               )
            );
            $this->catheaven_m->setQuizNumber($numbers);

            // get user data from database
            $this->user = $this->catheaven_m->getUser($event['source']['userId']);

            // get quiz number from database
            $this->quizNumber = $this->catheaven_m->getQuizNumber($this->user['number']);

            $this->sendQuestion($this->user['user_id'], $this->quizNumber['number']);

         } elseif (strtolower($userMessage) == 'meong meong') {
            $width = array();
            $height = array();
            $width = $this->quizNumRandomGen(300, 700, 1);
            $height = $this->quizNumRandomGen(300, 700, 1);

            $imageMessageBuilder = new ImageMessageBuilder('https://placekitten.com/g/'.$width[0].'/'.$height[0], 'https://placekitten.com/g/'.$width[0].'/'.$height[0]);
            $this->bot->pushMessage($event['source']['userId'], $imageMessageBuilder);

            // $imageUrl = 'https://pbs.twimg.com/profile_images/822943616838483968/uoYUQh63_400x400.jpg';
            // $columnTemplateBuilders = array();
            // $columnTitles = array('Komunitas Pecinta Kucing Jakarta',
            // 'KOMUNITAS PENCINTA KUCING INDONESIA',
            // 'Meong... Komunitas Pecinta Kucing');
            // $columnDesc = array('Komunitas pecinta kucing yang mencintai semua jenis kucing.',
            // 'Merupakan Grup Sarana Pencinta Kucing seluruh Indonesia.',
            // 'Welcome to Meong... Komunitas Pencinta Kucing.');
            // $columnURLs = array('https://www.facebook.com/groups/1544708822434808/',
            // 'https://www.facebook.com/groups/kucing123/',
            // 'https://www.facebook.com/groups/229792787094284/');
            //
            // for ($i=0; $i < count( $columnTitles ) ; $i++) {
            //    $columnTemplateBuilder = new CarouselColumnTemplateBuilder($columnTitles[$i], $columnDesc[$i], $imageUrl, [
            //        new UriTemplateActionBuilder('Klik Di Sini.', $columnURLs[$i]),
            //    ]);
            //    array_push($columnTemplateBuilders, $columnTemplateBuilder);
            // }
            //
            // $carouselTemplateBuilder = new CarouselTemplateBuilder($columnTemplateBuilders);
            // $templateMessage = new TemplateMessageBuilder('Button alt text', $carouselTemplateBuilder);
            // $this->bot->pushMessage($event['source']['userId'], $templateMessage);

         } else {
            $message = "Silakan ketik \"MEONG\" untuk memulai kuis atau \"MEONG MEONG\" untuk mendapatkan gambarnya.\n\n(Powered By placekitten.com)";
            $textMessageBuilder = new TextMessageBuilder($message);
            $this->bot->pushMessage($event['source']['userId'], $textMessageBuilder);
         }
         // if user already begin test
      } else {
         if(strtolower($userMessage) == 'exit'){
            $this->exitQuiz();
         } else {
            $this->checkAnswer($userMessage);
         }
      }
   }


   public function sendQuestion($user_id, $questionNum = 1){

      // get question from database
      $question = $this->catheaven_m->getQuestion($questionNum);

      // prepare answer options
      for($opsi = "a"; $opsi <= "d"; $opsi++) {
         if(!empty($question['option_'.$opsi]))
            $options[] = new MessageTemplateActionBuilder($question['option_'.$opsi], $question['option_'.$opsi]);
      }

      // prepare button template
      $buttonTemplate = new ButtonTemplateBuilder($this->user['number']."/10", $question['text'], $question['image'], $options);

      // build message
      $messageBuilder = new TemplateMessageBuilder("Gunakan mobile app untuk melihat soal", $buttonTemplate);

      // send message
      $response = $this->bot->pushMessage($user_id, $messageBuilder);
   }

   private function checkAnswer($message) {

      // if answer is true, increment score
      if($this->catheaven_m->isAnswerEqual($this->quizNumber['number'], $message)){
         $this->user['score']++;
         $this->catheaven_m->setScore($this->user['user_id'], $this->user['score']);
         $message = 'Jawaban Anda Benar...';
         $textMessageBuilder = new TextMessageBuilder($message);
         $this->bot->pushMessage($this->user['user_id'], $textMessageBuilder);
      } else {
         $message = 'Jawaban Anda Kurang Tepat...';
         $textMessageBuilder = new TextMessageBuilder($message);
         $this->bot->pushMessage($this->user['user_id'], $textMessageBuilder);
      }

      if($this->user['number'] < 10){

         // update number progress
         $this->catheaven_m->setUserProgress($this->user['user_id'], $this->user['number'] + 1);

         // get user data from database
         $this->user = $this->catheaven_m->getUser($this->user['user_id']);

         // get quiz number from database
         $this->quizNumber = $this->catheaven_m->getQuizNumber($this->user['number']);

         // send next number
         $this->sendQuestion($this->user['user_id'], $this->quizNumber['number']);
      } else {

         if ($this->user['score'] >= 6) {

            $stickerMessageBuilder = new StickerMessageBuilder(1, 14);
            $this->bot->pushMessage($this->user['user_id'], $stickerMessageBuilder);
            // show user score
            $message = "Selamat ya, total skor Anda " . $this->user['score']*10 . ". Karena skor anda lebih dari 60, berikut beberapa Tips memelihara kucing :";
            $textMessageBuilder = new TextMessageBuilder($message);
            $this->bot->pushMessage($this->user['user_id'], $textMessageBuilder);

            $imageUrl = 'https://pbs.twimg.com/profile_images/822943616838483968/uoYUQh63_400x400.jpg';
            $tips = $this->catheaven_m->getTips();
            $columnTemplateBuilders = array();

            for ($i=0; $i < count( $tips ) ; $i++) {
               $columnTemplateBuilder = new CarouselColumnTemplateBuilder(" ", $tips[$i]['title'], $imageUrl, [
                   new UriTemplateActionBuilder('Klik Di Sini.', $tips[$i]['url']),
               ]);
               array_push($columnTemplateBuilders, $columnTemplateBuilder);
            }

            $carouselTemplateBuilder = new CarouselTemplateBuilder($columnTemplateBuilders);
            $templateMessage = new TemplateMessageBuilder('Button alt text', $carouselTemplateBuilder);
            $this->bot->pushMessage($this->user['user_id'], $templateMessage);

         } else {

            $stickerMessageBuilder = new StickerMessageBuilder(2, 173);
            $this->bot->pushMessage($this->user['user_id'], $stickerMessageBuilder);
            // show user score
            $message = "Sayang sekali, skor Anda hanya " . $this->user['score']*10 . ". Silakan ketik \"MEONG\" untuk memulai kembali kuis";
            $textMessageBuilder = new TextMessageBuilder($message);
            $this->bot->pushMessage($this->user['user_id'], $textMessageBuilder);
         }

         $this->catheaven_m->setUserProgress($this->user['user_id'], 0);
      }
   }

   public function exitQuiz(){
      $message = 'Exit From Quiz';
      $textMessageBuilder = new TextMessageBuilder($message);
      $this->bot->pushMessage($this->user['user_id'], $textMessageBuilder);
      $this->catheaven_m->setUserProgress($this->user['user_id'], 0);
   }
}
