<?php

namespace App\Http\Helpers;

use App\Models\BasicSettings\Basic;
use Config;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class BasicMailer
{
  public static function sendMail($data)
  {
    // get the website title & mail's smtp information from db
    $info = Basic::select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
      ->first();

    // Check if SMTP is enabled
    if ($info->smtp_status != 1) {
      Session::flash('error', __('SMTP is not enabled. Please configure SMTP settings in admin panel.'));
      return false;
    }

    // if smtp status == 1, then set some value for PHPMailer
    try {
      $smtp = [
        'transport' => 'smtp',
        'host' => $info->smtp_host,
        'port' => $info->smtp_port,
        'encryption' => $info->encryption,
        'username' => $info->smtp_username,
        'password' => $info->smtp_password,
        'timeout' => null,
        'auth_mode' => null,
      ];
      Config::set('mail.mailers.smtp', $smtp);
    } catch (\Exception $e) {
      Session::flash('error', $e->getMessage());
      return false;
    }

    try {
      // Get the mailer and modify transport stream context to disable SSL verification
      // This fixes certificate CN mismatch errors (e.g., server.ictpro.nl vs localhost)
      $mailer = app('mail.manager')->mailer('smtp');
      $transport = $mailer->getSymfonyTransport();
      
      if (method_exists($transport, 'getStream')) {
        $stream = $transport->getStream();
        if (method_exists($stream, 'setStreamOptions')) {
          $streamOptions = $stream->getStreamOptions();
          if (!isset($streamOptions['ssl'])) {
            $streamOptions['ssl'] = [];
          }
          $streamOptions['ssl']['verify_peer'] = false;
          $streamOptions['ssl']['verify_peer_name'] = false;
          $streamOptions['ssl']['allow_self_signed'] = true;
          $stream->setStreamOptions($streamOptions);
        }
      }
      
      Mail::send([], [], function (Message $message) use ($data, $info) {
        $fromMail = $info->from_mail;
        $fromName = $info->from_name;
        $message->to($data['recipient'])
          ->subject($data['subject'])
          ->from($fromMail, $fromName)
          ->html($data['body'], 'text/html');

        if (array_key_exists('invoice', $data)) {
          $message->attach($data['invoice'], [
            'as' => 'Invoice',
            'mime' => 'application/pdf',
          ]);
        }
      });
      
      if (array_key_exists('sessionMessage', $data)) {
        Session::flash('success', $data['sessionMessage']);
      }
      
      return true;
    } catch (\Exception $e) {
      Session::flash('error', 'Mail could not be sent. Mailer Error: ' . $e->getMessage());
      return false;
    }
  }
}
