<?php
/**
 * Saves the data on the server side.
 * Requirements: PHP 5.2.1+
 * 
 * @example
 * $autosave = new Autosave();
 * $autosave->saveToFile('/path/to/file.txt');
 */

/**
 * The name of attribute with the "action" name.
 * @var string
 */
define('AUTOSAVE_ACTION', 'autosaveaction');
/**
 * The name of attribute with the name of CKEditor from which the request is coming.
 * @var string
 */
define('AUTOSAVE_EDITOR', 'ckeditorname');
/**
 * The name of attribute with the data to be saved.
 * @var string
 */
define('AUTOSAVE_CONTENT', 'content');

/**
 * The Autosave class saves the data coming from CKEditor 
 * and send back XML response.
 * 
 * To write your own Autosave connector, simply extend this class 
 * and overrite the saveContent() function.
 */
class Autosave {
	/**
	 * Whether to return error messages in the result.
	 * @var boolean
	 */
	public $debugMode = FALSE;
	/**
	 * Saves the content to a file. 
	 * @param string $path
	 * @return mixed Return FALSE if an error occured.
	 */
	public function saveToFile($path) {
		// Invalid requests
		if (!isset($_POST[AUTOSAVE_CONTENT], $_POST[AUTOSAVE_EDITOR], $_POST[AUTOSAVE_ACTION])) {
			$this->sendResponse(400);
			return FALSE;
		}
		if ($_POST[AUTOSAVE_ACTION] != 'draft') {
			$this->sendResponse(400);
			return FALSE;
		}

		// Save the content
		$result = file_put_contents( $path, $_POST[AUTOSAVE_CONTENT] );

		// OK
		if ($result !== FALSE) {
			$this->sendResponse(200);
			return $result;
		}

		// An error occurred
		if ($this->debugMode) {
			$error = error_get_last();
			if (!empty($error)) {
				$this->sendResponse(403, $error['message']);
				return $result;
			}
		}

		$this->sendResponse(403);
		return $result;
	}

	/**
	 * Send XML headers
	 */
	protected function sendXmlHeaders() {
		// Prevent the browser from caching the result.
		header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
		// always modified
		header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
		// HTTP/1.1
		header( 'Cache-Control: no-store, no-cache, must-revalidate' );
		header( 'Cache-Control: post-check=0, pre-check=0', FALSE );
		// HTTP/1.0
		header( 'Pragma: no-cache' );
		// Set the response format.
		header( 'Content-Type: text/xml; charset=utf-8' );
	}

	/**
	 * Prints XML response
	 * @param number $errorCode
	 * @param string $errorMessage
	 */
	protected function sendResponse($errorCode = 200, $errorMessage = "") {
		$this->sendXmlHeaders();
		if ($errorCode == 200) {
			echo '<result status="ok" />';
		}
		else {
			echo '<error statuscode="'.$errorCode.'" ';
			if (!empty($errorMessage)) {
				echo ' message="'.htmlspecialchars($errorMessage).'" ';
			}
			echo '/>';
		}
	}
}

// Uncomment the lines below to try the autosave plugin.

$autosave = new Autosave();
$autosave->debugMode = true;
$temp_file = '../temp/autosave_'.time().'.txt';
$autosave->saveToFile($temp_file);
?>