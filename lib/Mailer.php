<?php
    /*
     * soll quasi das versenden von Mails ermöglichen direct/quequed
     * - direktes versenden geschieht über phpMail oder vorkonfiguriertem SMTP
     * - quequed messages, werden mit methode addToQueque() in Datenbank abgelegt um via Cronjob sheduled verschickt zu werden
     *      ( dient Newslettern und Rundmails ), jedem Anbieter werden so in einer bestimmten Zeitspann nur eine definierte Anzahl von Mails verschickt,
     *      evtl. noch nen PrioWert ... High, Mid, Low
     */
     
    namespace lib;

    class Mailer
    {
        public function __construct()
        {
            
        }
    
        public static function sendTestMail($to)
        {
            $oEmail = new email($to, 'Testbetreff', 'Testtext', 'TestServer <mahdi@genesis-projekt.net>', 'martin.fleckner@stud.fh-erfurt.de; martin.fleckner@web.de', 'admin@genesis-projekt.net');
            $oEmail->send();
        }
    
        public static function sendMail($to, $subject, $body, $from, $cc="", $bcc="", $attachedFiles=array())
        {
            $oEmail = new email($to, $subject, $body, $from, $cc, $bcc);
            foreach($attachedFiles as $k=>$v)
            {
                $oEmail->attachfile($v, $k);
            }
            $oEmail->send();
        }
    }