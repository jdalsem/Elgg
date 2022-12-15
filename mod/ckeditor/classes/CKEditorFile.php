<?php

/**
 * A CKEditor uploaded file
 *
 * @since 5.0
 */
class CKEditorFile extends \ElggFile {
	
	protected $fs;
	
	/**
	 * Return the system filestore based on dataroot.
	 *
	 * @return \ElggDiskFilestore
	 */
	protected function getFilestore() {
		
		if (!isset($this->fs)) {
			 $this->fs = new CKEditorFilestore();
		}
		
		return $this->fs;
	}
}
