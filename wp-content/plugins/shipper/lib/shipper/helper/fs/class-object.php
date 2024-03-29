<?php
/**
 * A helper class to read and write to files
 *
 * @package shipper
 */

/**
 * Class Shipper_Helper_Fs_Object
 *
 * @extends SplFileObject
 */
class Shipper_Helper_Fs_Object extends SplFileObject {
	/**
	 * File read.
	 *
	 * @param int $length number of bytes.
	 *
	 * @return string|false
	 */
	#[\ReturnTypeWillChange]
	public function fread( $length ) {
		if ( $length < 1 ) {
			return false;
		}

		return parent::fread( $length );
	}

	/**
	 * Write to file.
	 *
	 * @param string $str string to write.
	 * @param null   $length number of bytes.
	 *
	 * @return int|false
	 */
	#[\ReturnTypeWillChange]
	public function fwrite( $str, $length = NULL ) {
		try {
			if ( $length ) {
				return parent::fwrite( $str, $length );
			}
			return parent::fwrite( $str );
		} catch ( Exception $e ) {
			return false;
		}
	}
}