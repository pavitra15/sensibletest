    <?php
        include('../connect.php');
        $date = new DateTime("now", new DateTimeZone('Asia/Kolkata') );
        $created_by_date=$date->format('Y-m-d H:i:s');
        $message=$_POST['message'];
        $email=$_POST['email'];
        $subject=$_POST['subject'];
        $id=$_POST['id'];
        $status='pending';
        $st=$db->prepare("insert into change_request(subject, email, message, login_id, status, created_by_id, created_by_date) values('$subject','$email','$message','$id','$status','$id','$created_by_date')");
        $st->execute();
        $cn=$st->rowCount();
        if($cn>0)
        {
            $query=$db->prepare("select request_id from change_request where subject='$subject' and email='$email' and status='$status'");
            $query->execute();
            if($data=$query->fetch())
            {
                do
                {
                    $request_id=$data['request_id'];
                }
                while($data=$query->fetch());
                include ('../mailin-smtp-api-master/Mailin.php');
                $message='<div width="100%" style="background: #eceff4; padding: 50px 20px; color: #514d6a;">
                    <div style="max-width: 700px; margin: 0px auto; font-size: 14px">
                        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px">
                            <tr>
                                <td style="vertical-align: top;">
                                    <a href="https://www.sensibleconnect.com"><img src="https://www.sensibleconnect.com/img/logo-sensible.png" alt="Sensible Connect" style="height: 70px" /></a>
                                </td>
                                <td style="text-align: right; vertical-align: middle;">
                                    <span style="color: #a09bb9;">
                                        POSiBILL
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <div style="padding: 40px 40px 20px 40px; background: #fff;">
                            <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td>
                                            <p>Hi,</p>
                                            <p>Your request id is '.$request_id.'</p>
                                            <p>Our Customer Support Heroes contact you shortly</p>
                                            <p>Thank you,<br>The Sensible Connect Solutions Team</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div style="text-align: center; font-size: 12px; color: #a09bb9; margin-top: 20px">
                            <p>
                                Sensible Connect solutions Pvt Ltd, Pune, 411009
                                <br />
                                Dont like these emails? <a href="javascript: void(0);" style="color: #a09bb9; text-decoration: underline;">Unsubscribe</a>
                                <br />
                                © 2017 Sensible Connect solutions pvt Ltd. All Rights Reserved.
                            </p>
                        </div>
                    </div>
                </div>';
                $mailin = new Mailin('info@sensibleconnect.com', 'QUT6g8qdZ7XmVn49');
                $mailin->
                    addTo($email, 'Sensible Connect')->
                    setFrom('info@sensibleconnect.com', 'Sensible Connect')->
                    setReplyTo('info@sensibleconnect.com','Sensible Connect')->
                    setSubject('your request successfully generated')->
                    setText($message)->
                    setHtml($message);
                $mailin->send();
            } 
        }
    ?>