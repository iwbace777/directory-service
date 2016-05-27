<?php namespace DirectoryService\Queue;

use URL, Mail, Log;
use Subscriber as SubscriberModel;

class SendSubscriber {
    public function fire($j, $d)
    {
        $body = $d['body'];
        
        $subscribers = SubscriberModel::all();
        
        foreach ($subscribers as $subscriber) {
            $data = ['body' => $body];
            
            $info = [ 'reply_name'  => REPLY_NAME,
                      'reply_email' => REPLY_EMAIL,
                      'email'       => $subscriber->email,
                      'name'        => REPLY_NAME,
                      'subject'     => SITE_NAME." Newsletter",
            ];
            
            Mail::send('email.blank', $data, function($message) use ($info) {
                $message->from($info['reply_email'], $info['reply_name']);
                $message->to($info['email'], $info['name'])
                        ->subject($info['subject']);
            });            
        }        
        
               
        $j->delete();
    }
} 