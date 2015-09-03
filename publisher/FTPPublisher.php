<?php
namespace publisher;
use \Config as c;
/**
* 
*/
class FTPPublisher extends Publisher
{
	
	public function publish() {
		$ftp_server=c::get("ftpHost");
		$ftp_user_name=c::get("ftpLogin");
		$ftp_user_pass=c::get("ftpPass");
		$file = c::get("workDir")."/".c::get("filename");
		$remote_file = "/web/ipcam/cam.jpg";
		
		// set up basic connection
		$conn_id = ftp_connect($ftp_server);
		
		// login with username and password
		$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
		
		// upload a file
		if (ftp_put($conn_id, $remote_file, $file, FTP_ASCII)) {
			$this->log("successfully uploaded $file");
		} else {
			$this->log("There was a problem while uploading $file");
		}
		// close the connection
		ftp_close($conn_id);
		
	}
}
?>