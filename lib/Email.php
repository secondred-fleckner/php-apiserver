<?php

    namespace lib;

    class email
    {
        const DEFAULT_CHARSET = 'utf-8';
        const CHARSET_UTF8 = 'utf-8';
        const CHARSET_LATIN = 'iso-8859-1';

        private $mailtemp_directory;

        public $id;
        public $to;
        public $subject;
        public $body;
        public $from;
        public $cc;
        public $bcc;
        public $attachedfiles;

        public $charset;

        public function __construct($to,$subject,$body,$from="",$cc="",$bcc="")
        {
            $this->id = md5(uniqid());
            $this->to = $to;
            $this->subject = $subject;
            $this->body = $body;
            $this->from = $from;
            $this->cc = $cc;
            $this->bcc = $bcc;
            $this->attachedfiles = array();

            $this->mailtemp_directory = BASE.DIRECTORY_SEPARATOR.'mailtemp'.DIRECTORY_SEPARATOR;
        }

        public function destroy()
        {
            if (file_exists($this->mailtemp_directory.$this->id))
            {
                for ($attachment=0;$attachment<count($this->attachedfiles);$attachment++)
                {
                    unlink($this->mailtemp_directory.$this->id.DIRECTORY_SEPARATOR.$this->attachedfiles[$attachment]);
                }
                rmdir($this->mailtemp_directory.$this->id);
            }
        }

        private function composemail()
        {
            $body = imap_8bit($this->body);
            $body .= "\n\n";
            /*for ($attachment=0;$attachment<count($this->attachedfiles);$attachment++)
                {
                    $body.="\t".imap_8bit("<<".$this->attachedfiles[$attachment].">>");
                }*/
            $boundary = "----".$this->id;
            $mail = "";
            $mail .= "Content-class: urn:content-classes:message";
            $mail .= "\nUser-Agent: Free WebMail";
            $mail .= "\nMIME-Version: 1.0";
            if (!empty($this->attachedfiles))
            {
                $mail .= "\nContent-Type: multipart/mixed;\n\tboundary=\"".$boundary."\"";
            }
            if (!empty($this->from))
            {
                $mail .= "\nFrom: ".$this->from;
            }
            if (!empty($this->cc))
            {
                $mail .= "\nCc: ".$this->cc;
            }
            if (!empty($this->bcc))
            {
                $mail .= "\nBcc: ".$this->bcc;
            }
            $mail .= "\nX-Priority: 3 (Normal)";
            $mail .= "\nImportance: Normal";
            if (!empty($this->attachedfiles))
            {
                $mail.="\n\n--".$boundary;
            }
            $mail .= "\nContent-Type: text/plain;\n\tcharset=\"".email::DEFAULT_CHARSET."\"";
            $mail .= "\nContent-Transfer-Encoding: quoted-printable";
            $mail .= "\nContent-Disposition: inline";
            $mail .= "\n\n".$body;
            if (!empty($this->attachedfiles))
            {
                $mail .= "\n\n--".$boundary;
            }
            for ($attachment=0;$attachment<count($this->attachedfiles);$attachment++)
            {
                $file = fopen($this->mailtemp_directory.$this->id."/".$this->attachedfiles[$attachment],"r");
                $content=fread($file,filesize($this->mailtemp_directory.$this->id."/".$this->attachedfiles[$attachment]));
                fclose($file);
                $encodedfile=chunk_split(base64_encode($content));
                $mail .= "\nContent-Type: application/octet-stream;\n\tname=\"".$this->attachedfiles[$attachment]."\"";
                $mail .= "\nContent-Transfer-Encoding: base64";
                $mail .= "\nContent-Description: ".$this->attachedfiles[$attachment];
                $mail .= "\nContent-Disposition: attachment;\n\tfilename=\"".$this->attachedfiles[$attachment]."\"";
                $mail .= "\n\n".$encodedfile."\n\n--".$boundary;
            }
            if (!empty($this->attachedfiles))
            {
                $mail .= "--";
            }
            return $mail;
        }

        public function attachfile($tempfile, $filename = null)
        {
            if (!file_exists(BASE.DIRECTORY_SEPARATOR.'mailtemp'.DIRECTORY_SEPARATOR.$this->id))
            {
                mkdir($this->mailtemp_directory.$this->id);
            }
            copy($tempfile,$this->mailtemp_directory.$this->id."/".$filename);
            $this->attachedfiles[] = $filename;
        }

        public function removeattachment($filename)
        {
            unlink($this->mailtemp_directory.$this->id."/".$filename);
            $newattachedfiles = array();
            for ($attachment=0;$attachment < count($this->attachedfiles);$attachment++)
            {
                if ($this->attachedfiles[$attachment]!=$filename)
                {
                    $newattachedfiles[] = $this->attachedfiles[$attachment];
                }
            }
            $this->attachedfiles = $newattachedfiles;
            unset($newattachedfiles);
        }

        public function showmail()
        {
            $mail = "To: ".$this->to."\n";
            $mail .= "Subject: ".$this->subject."\n";
            $mail .= $this->composemail();
            return $mail;
        }

        public function send( $bDestroy = true )
        {
            mail($this->to,$this->subject,"",$this->composemail());
            #imap_mail($this->to,$this->subject,"",$this->composemail());

            if ($bDestroy)
                $this->destroy();
        }
    }

    function mailstring($mailobject)
    {
        return rawurlencode(serialize($mailobject));
    }

    function mailobject($mailstring)
    {
        return unserialize(stripslashes(rawurldecode($mailstring)));
    }

