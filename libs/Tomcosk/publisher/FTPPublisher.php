<?php
namespace Tomcosk\publisher;
use Tomcosk\Config as c;

/**
* 
*/
class FTPPublisher extends Publisher
{

	protected $connId = null;

	function __construct() {
		$ftp_server=c::get("ftpHost");
		$ftp_user_name=c::get("ftpLogin");
		$ftp_user_pass=c::get("ftpPass");
		
		// set up basic connection
		$this->connId = ftp_connect($ftp_server);
		
		// login with username and password
		$login_result = ftp_login($this->connId, $ftp_user_name, $ftp_user_pass);

	}
	
	public function publish() {
		if($this->connId != null) {
			$remote_file = "/web/ipcam/cam.jpg";
			$file = c::get("workDir")."/".c::get("filename");
			
			// upload a file
			if (ftp_put($this->connId, $remote_file, $file, FTP_ASCII)) {
				$this->log("successfully uploaded $file");
			} else {
				$this->log("There was a problem while uploading $file");
			}
		} else {
			$this->log("No connection to ftp");
		}
		// close the connection
//		ftp_close($conn_id);
		
	}
}
?>