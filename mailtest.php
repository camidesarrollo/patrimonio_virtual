<?PHP
$sender = 'rsilva@biblioredes.gob.cl';
$recipient = 'rsilva@biblioredes.gob.cl';

$subject = "php mail test";
$message = "php test message";
$headers = 'From:' . $sender;
print "<pre>\n";
print_r($_SERVER);
print "<br><br>\n\n";

if (mail($recipient, $subject, $message, $headers))
{
    echo "Message accepted";
}
else
{
    echo "Error: Message not accepted";
}
?>
