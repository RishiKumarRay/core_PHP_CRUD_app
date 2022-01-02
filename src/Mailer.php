<?php

namespace App;

final class Mailer {

    public static function sendHTMLEmail( string $recipient, string $subject, string $contents ): bool {
        $headers  = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type: text/html; charset=utf8" . "\r\n";
        return mail( $recipient, $subject, $contents, $headers );
    }

}