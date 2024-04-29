<?php

namespace App\Services;
use Mailjet\Client;
use Mailjet\Resources;

class Mail 
{
  private $api_key = "6b1a8d123761aaa5026a023faced5061";
  private $api_secret_key = "65f9fb3bda435d000a7011a26a76302a";

  public function send($to_email, $to_name, $subject, $content)
  {
    $mj = new Client($this->api_key, $this->api_secret_key, true, ['version' => 'v3.1']);
    //$mj = new \Mailjet\Client('****************************1234','****************************abcd',true,['version' => 'v3.1']);
    $body = [
      'Messages' => [
        [
          'From' => [
            'Email' => "solofoniainarakotoharimanana@gmail.com",
            'Name' => "Magasin"
          ],
          'To' => [
            [
              'Email' => $to_email,
              'Name' => $to_name
            ]
          ],
          'TemplateID' => 5909850,
          'TemplateLanguage' => true,
          //'HTMLPart' => "<h3>Dear passenger 1, welcome to <a href='https://www.mailjet.com/'>Mailjet</a>!</h3><br />May the delivery force be with you!",
          'Subject' => $subject,
          'variables' => [
            'content' => $content
          ]
        ]
      ]
    ];
      $response = $mj->post(Resources::$Email, ['body' => $body]);
      $response->success() && dd($response->getData());
    }
}