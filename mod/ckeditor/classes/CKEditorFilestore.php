<?php

use Elgg\Exceptions\InvalidParameterException;

/**
 * CKEditor filestore
 *
 * @since 5.0
 */
class CKEditorFilestore extends \ElggDiskFilestore {
	
	/**
	 * Number of entries per matrix dir.
	 * You almost certainly don't want to change this.
	 */
	const BUCKET_SIZE = 500;
	
	/**
	 * {@inheritDoc}
	 * @see ElggDiskFilestore::getFilenameOnFilestore()
	 */
	public function getFilenameOnFilestore(\ElggFile $file) {
		
		$owner_guid = $file->getOwnerGuid();
		if (!$owner_guid) {
			$owner_guid = _elgg_services()->session->getLoggedInUserGuid();
		}
		
		if (!$owner_guid) {
			throw new InvalidParameterException("File {$file->getFilename()} is missing an owner!");
		}
		
		$filename = $file->getFilename();
		if (empty($filename)) {
			throw new InvalidParameterException("File {$file->getFilename()} is missing a filename!");
		}
		
		// Windows has different separators
		$filename = str_ireplace(DIRECTORY_SEPARATOR, '/', $filename);

		$trim = function($value) {
			return rtrim($value, '/\\');
		};
		$parts = array_map($trim, [
			$this->getUploadPath($owner_guid),
			$filename,
		]);
		
		$dirroot = elgg_extract('dir_root', $this->getParameters(), '');
		
		return $dirroot . implode('/', $parts);
	}
	
	/**
	 * Make the correct folder structure for an owner
	 *
	 * @param int $owner_guid the owner to generate for
	 *
	 * @return string
	 */
	protected function getUploadPath(int $owner_guid) {
		
		if (empty($owner_guid)) {
			$owner_guid = elgg_get_logged_in_user_guid();
		}
		
		$lower_bound = (int) max(floor($owner_guid / self::BUCKET_SIZE) * self::BUCKET_SIZE, 1);
		
		return implode('/', [
			'editor_images',
			$lower_bound,
			$owner_guid,
		]);
	}
}
