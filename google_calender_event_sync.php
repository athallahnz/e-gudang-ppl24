<?php 
session_start();
// Include Google calendar api handler class 
include_once 'GoogleCalendarApi.class.php'; 
     
// Include database configuration file 
require_once 'dbConfig.php'; 
require_once 'koneksi.php'; 
 
$statusMsg = ''; 
$status = 'danger'; 
$agent=$_SERVER['HTTP_USER_AGENT'];
$ip=$_SERVER['REMOTE_ADDR'];
$id_user = $_SESSION['id'];
date_default_timezone_set('Asia/Jakarta');
$tanggal_edit = date('d-m-Y, H:i:s');
if(isset($_GET['code'])){ 
    // Initialize Google Calendar API class 
    $GoogleCalendarApi = new GoogleCalendarApi(); 
     
    // Get event ID from session 
    $event_id        = $_SESSION['last_event_id'];
    $title           = $_SESSION['title'];
    $tanggal_rapat   = $_SESSION['tanggal_rapat'];
    $jam             = $_SESSION['jam'];
    $tanggal_selesai = $_SESSION['tanggal_selesai'];
    $jam_selesai     = $_SESSION['jam_selesai'];
    $tempat          = $_SESSION['tempat'];
    $keterangan      = $_SESSION['keterangan'];
    $link_rapat      = $_SESSION['link_rapat'];
    $sesi_rapat      = $_SESSION['sesi_rapat'];
    $persetujuan     = $_SESSION['persetujuan'];

    if(!empty($event_id)){ 
         
        // Fetch event details from database 
        // $sqlQ = "SELECT * FROM events WHERE id = ?"; 
        // $stmt = $db->prepare($sqlQ);  
        // $stmt->bind_param("i", $db_event_id); 
        // $db_event_id = $event_id; 
        // $stmt->execute(); 
        // $result = $stmt->get_result(); 
        // $eventData = $result->fetch_assoc(); 

        $result_rapat = mysqli_query($connect, "SELECT * FROM tbl_rapat WHERE id_rapat=$event_id");
		$row_rapat = mysqli_fetch_assoc($result_rapat);
        
        $result_attendees = mysqli_query($connect, "SELECT email FROM tbl_peserta_rapat a INNER JOIN tbl_user b on a.fk_id_peserta=b.id WHERE fk_id_rapat=$event_id");
            // while ($row_attendees = $result_attendees->fetch_object()) {
            //     // $row_attendees['email'];
            //     // array('email' => 'keuangan@stikes-yrsds.ac.id'),
            //     $email = $row_attendees->email;
            //     $attendees .= array('email' => 'keuangan@stikes-yrsds.ac.id');
            // }

            // while($row_attendees = $result_attendees->fetch_object()){
            //     // $email =  $row_attendees['email'];
            //      $email =  $row_attendees->email;
            //      // $email =  "aburisal@stikes-yrsds.ac.id";
            //     //$attendees .= array('email' => $email).", ";
            //     // $attendees .= $row_attendees->email;
            //     // $attendees = array('email' => $email); //sukses tapi bisa add 1 email
            //     $attendees[] .= array('email' => $email); //sukses tapi bisa add 1 email
            // }
        //$hasil = substr($attendees, 0, strlen($attendees) - 1);

        // while($row_attendees = $result_attendees->fetch_object()){
        //     $hasil3 = array (  
        //         array('email' => $row_attendees->email),
        //     );
        // }

        if(!empty($row_rapat)){ 
            // $hasil3 = array();
            while($row_attendees = $result_attendees->fetch_object()){
                $hasil3[] = array('email' => $row_attendees->email);
                // array_push($hasil3);
                // $calendar_event['attendees'][] = array (  
                //     array('email' => $row_attendees->email),
                // );
                
            }

            $calendar_event = array( 
                'summary' => $title, 
				'location' => $tempat, 
				'description' => $keterangan,
                'attendees' => $hasil3,
                // 'attendees' => array(
                //                 'email' => 'alief@stikes-yrsds.ac.id'),
                'reminders' => array(
                    'useDefault' => FALSE,
                    'overrides' => array(
                        array('method' => 'email', 'minutes' => 24 * 60),
                        array('method' => 'popup', 'minutes' => 60),
                    ),
                )

                // 'attendees' => array(
                //     array('email' => 'alief@stikes-yrsds.ac.id'),
                //     array('email' => 'keuangan@stikes-yrsds.ac.id'),
                // )
            ); 
            
            // foreach ($result_attendees as $user) {
            //     $calendar_event['attendees'][] = array (  
            //         'email' => $user->email,
            //     );
            // }

            $opts = array('sendNotifications' => true, 'conferenceDataVersion' => true); // send Notification immediately by Mail or Stop Hangout Call Link
             
            $event_datetime = array( 
                'event_date' => $tanggal_rapat, 
				'start_time' => $jam, 
				'end_time' => '16:00:00' 
            ); 
             
            // Get the access token 
            $access_token_sess = $_SESSION['google_access_token']; 
            if(!empty($access_token_sess)){ 
                $access_token = $access_token_sess; 
            }else{ 
                $data = $GoogleCalendarApi->GetAccessToken(GOOGLE_CLIENT_ID, REDIRECT_URI, GOOGLE_CLIENT_SECRET, $_GET['code']); 
                $access_token = $data['access_token']; 
                $_SESSION['google_access_token'] = $access_token; 
            } 
             
            if(!empty($access_token)){ 
                try { 
                    // Get the user's calendar timezone 
                    $user_timezone = $GoogleCalendarApi->GetUserCalendarTimezone($access_token); 
                 
                    // Create an event on the primary calendar 
                    $google_event_id = $GoogleCalendarApi->CreateCalendarEvent($access_token, 'primary', $calendar_event,  0, $event_datetime, $user_timezone, $opts); 
                     
                    //echo json_encode([ 'event_id' => $event_id ]); 
                     
                    if($google_event_id){ 
                        // Update google event reference in the database 
                        // $sqlQ = "UPDATE events SET google_calendar_event_id=? WHERE id=?"; 
                        // $stmt = $db->prepare($sqlQ); 
                        // $stmt->bind_param("si", $db_google_event_id, $db_event_id); 
                        // $db_google_event_id = $google_event_id; 
                        // $db_event_id = $event_id; 
                        // $update = $stmt->execute(); 
                         
                        $update = mysqli_query($connect, "UPDATE tbl_rapat SET kode_link='$link_rapat', 
                        sesi='$sesi_rapat', 
                        title='$title', 
                        start='$tanggal_rapat', 
                        tempat='$tempat', 
                        jam='$jam', 
                        ket_rapat='$keterangan', 
                        tgl_selesai='$tanggal_selesai', jam_selesai='$jam_selesai',
                        status='$persetujuan', 
                        edited_by=$id_user
                        WHERE id_rapat=$event_id");

                        // $update = mysqli_query($connect, "UPDATE tbl_rapat SET status='Telah Disetujui', edited_by='$id_user', last_edited='$tanggal_edit' WHERE id_rapat='$event_id'");
                        
			            $logs = mysqli_query($connect,"INSERT INTO tbl_userlog (fk_id_user, ip_address, agent, waktu, status_log, aplikasi, komponen, event_context) VALUES ($id_user, '$ip', '$agent', now(), 'User dengan ID ($id_user) menyetujui permintaan rapat dengan ID ($event_id)', 'sim-mutu', 'Permintaan Rapat', '$event_id')");
                        
                        $_SESSION['sukses'] = 'Data Berhasil Disimpan';
                        // $_SESSION['sukses'] =  $hasil3.' <= Hasil';

                        unset($id_rapat); 
                        unset($title);
                        unset($tanggal_rapat);
                        unset($jam);
                        unset($tanggal_selesai);
                        unset($jam_selesai);
                        unset($tempat);
                        unset($keterangan);
                        unset($link_rapat);
                        unset($sesi_rapat);
                        unset($persetujuan);
                        unset($_SESSION['google_access_token']); 
                        // $status = 'success'; 
                        // $statusMsg = '<p>Event #'.$event_id.' has been added to Google Calendar successfully!</p>'; 
                        // $statusMsg .= '<p><a href="https://calendar.google.com/calendar/" target="_blank">Open Calendar</a>'; 
                    } 
                } catch(Exception $e) { 
                    //header('Bad Request', true, 400); 
                    //echo json_encode(array( 'error' => 1, 'message' => $e->getMessage() )); 
                    $statusMsg = $e->getMessage(); 
                } 
            }else{ 
                $message = 'Failed to fetch access token!';
            } 
        }else{ 
            $message = 'Event data not found!';
        } 
    }else{ 
        $message = 'Event reference not found!';
    } 
     
    // $_SESSION['gagal'] = array('status' => $status, 'status_msg' => $statusMsg); 
    // $_SESSION['gagal'] = $message."= ".$statusMsg; 
     
    header("Location: on-user/menu-rapat"); 
    exit(); 
} 
?>